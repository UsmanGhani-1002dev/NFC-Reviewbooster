<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Mail\UserRegisteredAndSubscribed;
use App\Mail\UserWelcomeMail;
use App\Mail\PartnerApplicationSubmittedMail;
use App\Mail\PartnerStatusUpdatedMail;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\View\View;
use Illuminate\Support\Facades\Notification;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $plans = SubscriptionPlan::all();
        return view('auth.register', compact('plans'));
    }


    public function validateStep1(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'company_name' => ['nullable', 'string', 'max:255'],
                'partner_type' => ['nullable', 'string', 'in:standard,wholesaler,retailer,corporate'],
            ]);
    
            return response()->json(['success' => true, 'message' => 'Validation passed']);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function storePartner(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'company_name' => ['nullable', 'string', 'max:255'],
            'partner_type' => ['required', 'string', 'in:wholesaler,retailer,corporate'],
            'vat_number' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
                'role' => 'bussiness_owner',
                'company_name' => $request->company_name ?? null,
                'partner_type' => $request->partner_type,
                'partner_status' => 'pending',
                'vat_number' => $request->vat_number ?? null,
            ]);

            // Notification
            \App\Models\Notification::create([
                'type' => 'registration',
                'data' => [
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'partner_type' => $user->partner_type_label,
                    'success_message' => "New B2B Partner Application submitted: {$user->name} ({$user->partner_type_label})",
                    'timestamp' => now()->toISOString(),
                ],
            ]);

            // Send email to admins
            try {
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new PartnerApplicationSubmittedMail($user));
                }
            } catch (\Exception $mailEx) {
                Log::error('Partner Application Admin email error: ' . $mailEx->getMessage());
            }

            event(new Registered($user));
            Auth::login($user);

            session()->flash('success', "Welcome to Tap Review Cards! Your {$user->partner_type_label} application has been submitted and is under Admin review. Wholesale pricing will automatically unlock once approved.");

            return redirect(route('home'));

        } catch (\Exception $e) {
            \Log::error('Partner Registration error: ' . $e->getMessage());
            return back()->withErrors(['general' => 'Registration failed. Please try again.'])->withInput();
        }
    }

    public function store(Request $request): RedirectResponse
    {
        \Log::info('Request data:', $request->all());

        // If registering as a B2B partner, delegate to storePartner
        if ($request->filled('partner_type') && in_array($request->partner_type, ['wholesaler', 'retailer', 'corporate'])) {
            return $this->storePartner($request);
        }
    
        // Standard software subscription registration
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'payment_plan' => ['required', 'integer', 'exists:subscription_plans,id'],
                'payment_intent_id' => ['required', 'string'],
                'company_name' => ['nullable', 'string', 'max:255'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    
        $plan = SubscriptionPlan::findOrFail($request->payment_plan);
    
        Stripe::setApiKey(config('services.stripe.secret'));
    
        try {
            $intent = PaymentIntent::retrieve($request->payment_intent_id);
        } catch (\Exception $e) {
            return back()->withErrors(['payment' => 'Invalid payment intent. Please try again.'])->withInput();
        }
    
        if ($intent->status !== 'succeeded') {
            return back()->withErrors(['payment' => 'Payment failed. Please try again.'])->withInput();
        }
    
        $existingSubscription = Subscription::where('stripe_payment_intent_id', $intent->id)->first();
        if ($existingSubscription) {
            return back()->withErrors(['payment' => 'This payment has already been processed.'])->withInput();
        }
    
        $user = null;
    
        try {
            DB::transaction(function () use ($request, $intent, $plan, &$user) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => strtolower($request->email),
                    'password' => Hash::make($request->password),
                    'role' => 'bussiness_owner',
                    'company_name' => $request->company_name ?? null,
                    'partner_type' => 'standard',
                ]);
    
                $startedAt = now();
                $endsAt = $startedAt->copy()->addDays((int) $plan->duration_days);
    
                Subscription::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $plan->id,
                    'stripe_payment_intent_id' => $intent->id,
                    'stripe_customer_id' => $intent->customer ?? null,
                    'stripe_status' => $intent->status,
                    'started_at' => $startedAt,
                    'ends_at' => $endsAt,
                ]);
    
                \App\Models\Notification::create([
                    'type' => 'registration',
                    'data' => [
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'name' => $plan->name,
                        'price' => $plan->price,
                        'duration_days' => $plan->duration_days,
                        'success_message' => $this->getWelcomeMessage($user->name, $user->email, $plan->name),
                        'timestamp' => now()->toISOString(),
                    ],
                ]);
    
                event(new Registered($user));
                Auth::login($user);
            });
    
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new UserRegisteredAndSubscribed($user, $plan));
            }
            
            if ($user) {
                Mail::to($user->email)->send(new UserWelcomeMail($user, $plan));
            }
    
            session()->flash('success', 'Registration successful! Welcome to Tap Review Cards.');
    
            return redirect(route('home'));
            
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            return back()->withErrors(['general' => 'Registration failed. Please try again.'])->withInput();
        }
    }

    /**
     * Generate personalized welcome message
     */
    private function getWelcomeMessage($userName, $planName): string
    {
        $messages = [
        "🎉 Welcome aboard, {$userName}! Your {$planName} subscription is now active. Get ready to boost your business!",
        "🚀 Congratulations {$userName}! You've successfully activated your {$planName} plan. Time to supercharge your reviews!",
        "✨ Amazing choice, {$userName}! Your {$planName} subscription is live. Let's take your business to the next level!",
        "🎊 Welcome to the family, {$userName}! Your {$planName} plan is ready to help you grow your business.",
        "🌟 Fantastic, {$userName}! Your {$planName} subscription is active. Success starts now!"
        ];

        return $messages[array_rand($messages)];
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::with(['subscription.plan'])
            ->withCount('cards')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('admin.users.index', compact('users'));
    }



    public function toggleStatus(User $user)
{
    try {
        // Toggle the status
        $user->is_active = !$user->is_active;
        $user->save();
       
        // Prepare response message
        $status = $user->is_active ? 'enabled' : 'disabled';
        $message = "User {$user->name} has been {$status}";
       
        // Check if this is an AJAX request
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_active' => $user->is_active
            ]);
        }
       
        // For non-AJAX requests, redirect with flash message
        return redirect()->back()->with('success', $message);
    } catch (\Exception $e) {
        \Log::error('Error toggling user status: ' . $e->getMessage());
       
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user status'
            ]);
        }
       
        return redirect()->back()->with('error', 'Failed to update user status');
    }
}


  public function adminCreate()
{
    // Hardcode roles to ensure all options are always available, even if no users of that type exist yet.
    $roles = ['admin', 'bussiness_owner'];
    $plans = SubscriptionPlan::all();

    return view('admin.users.create', compact('roles', 'plans'));
}


public function storeAdminUser(Request $request)
{
    $request->validate([
        'company_name' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'required|string',
        'plan_id' => 'nullable|exists:subscription_plans,id',
    ]);


    $user = User::create([
        'company_name' => $request->company_name,
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'is_active' => true,
    ]);

    // Handle initial subscription plan
    if ($request->filled('plan_id')) {
        $plan = SubscriptionPlan::find($request->plan_id);
        $startsAt = now();
        $endsAt = $startsAt->copy()->addDays((int) $plan->duration_days);

        Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'started_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
            'stripe_status' => 'succeeded',
        ]);

        Log::info('Initial subscription created for user ID: ' . $user->id);
    }

    // Send a simple account-created / welcome email (no approval wording).
    try {
        Mail::to($user->email)->send(new \App\Mail\AccountCreatedMail($user));
    } catch (\Throwable $mailEx) {
        Log::error('Account created email failed: ' . $mailEx->getMessage());
    }

    return redirect()->route('admin.users.index')->with('success', 'User added successfully with subscription.');
}


    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->user_ids);
        User::whereIn('id', $ids)->delete();


        return redirect()->route('admin.users.index')->with('success', 'Selected users deleted.');
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }


public function edit(User $user)
{
   $user = User::with(['subscription', 'subscription.plan']) // Load both subscription and plan
            ->withCount('cards')
            ->findOrFail($user->id);

    $planName = $user->subscription && $user->subscription->plan
                ? $user->subscription->plan->name
                : 'No Plan';
                $plans = SubscriptionPlan::all();


    return view('admin.users.edit', compact('user','plans', 'planName'));
}


    public function updatePartnerStatus(Request $request, User $user)
    {
        $request->validate([
            'partner_status' => 'required|in:approved,rejected,pending',
            'partner_type' => 'nullable|in:standard,wholesaler,retailer,corporate',
        ]);

        $previousStatus = $user->partner_status;

        $user->partner_status = $request->partner_status;
        if ($request->filled('partner_type')) {
            $user->partner_type = $request->partner_type;
        }
        $user->save();

        // Email the user only when the status actually changed to approved/rejected,
        // and only for real partner tiers (never for standard accounts).
        $isPartnerTier = in_array($user->partner_type, ['wholesaler', 'retailer', 'corporate'], true);
        $statusChanged = $previousStatus !== $user->partner_status;
        try {
            if ($isPartnerTier && $statusChanged && in_array($user->partner_status, ['approved', 'rejected'], true)) {
                Mail::to($user->email)->send(new PartnerStatusUpdatedMail($user->fresh(), $user->partner_status));
            }
        } catch (\Exception $mailEx) {
            Log::error('Partner Status User email error: ' . $mailEx->getMessage());
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Partner status for {$user->name} updated to " . ucfirst($user->partner_status),
                'partner_status' => $user->partner_status,
            ]);
        }

        return back()->with('success', "Partner status for {$user->name} updated successfully.");
    }

    public function update(Request $request, User $user)
    {
        \Log::info('User update request:', $request->all());

        $request->validate([
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:bussiness_owner,admin',
            'is_active' => 'required|boolean',
            'password' => 'nullable|min:6',
            'plan_id' => 'nullable|exists:subscription_plans,id',
            'partner_type' => 'nullable|in:standard,wholesaler,retailer,corporate',
            'partner_status' => 'nullable|in:pending,approved,rejected',
            'partner_discount_override' => 'nullable|numeric|min:0|max:100',
            'vat_number' => 'nullable|string|max:100',
        ]);

        // Remember the status before saving so we only email on a real change.
        $previousStatus = $user->partner_status;

        // Update user data
        $data = [
            'company_name' => $request->company_name,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => (bool) $request->is_active,
            'partner_type' => $request->partner_type ?? 'standard',
            'partner_status' => $request->partner_status,
            'vat_number' => $request->vat_number,
            // Blank input clears the override so the tier default applies again.
            'partner_discount_override' => $request->filled('partner_discount_override')
                ? $request->partner_discount_override
                : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Only send the partner approval/rejection email when the status has
        // actually CHANGED to approved/rejected, and only for real partner tiers
        // (standard accounts are not partners and must never get this email).
        $isPartnerTier = in_array($user->partner_type, ['wholesaler', 'retailer', 'corporate'], true);
        $statusChanged = $previousStatus !== $user->partner_status;
        if ($isPartnerTier && $statusChanged && in_array($user->partner_status, ['approved', 'rejected'], true)) {
            try {
                Mail::to($user->email)->send(new PartnerStatusUpdatedMail($user->fresh(), $user->partner_status));
            } catch (\Exception $mailEx) {
                Log::error('Partner Status User email error in update: ' . $mailEx->getMessage());
            }
        }

        // Handle subscription plan update
        if ($request->filled('plan_id')) {
            $plan = SubscriptionPlan::find($request->plan_id);
            $startsAt = now();
            $endsAt = $startsAt->copy()->addDays((int) $plan->duration_days);

            if ($user->subscription) {
                $user->subscription->update([
                    'subscription_plan_id' => $plan->id,
                    'started_at' => $startsAt,
                    'ends_at' => $endsAt,
                    'status' => 'active',
                ]);
            } else {
                Subscription::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $plan->id,
                    'started_at' => $startsAt,
                    'ends_at' => $endsAt,
                    'status' => 'active',
                ]);
            }

            \Log::info('Subscription updated or created for user ID: ' . $user->id);
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
}
