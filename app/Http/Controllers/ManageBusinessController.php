<?php

namespace App\Http\Controllers;
use App\Models\ManageBusiness;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class ManageBusinessController extends Controller
{
    // User Side
    public function index()
    {
        $user = auth()->user();
        $businesses = ManageBusiness::where('user_id', $user->id)->withCount('cards')->latest()->get();
        return view('manage_business.index', compact('businesses'));
    }

    public function create()
    {
        $user = auth()->user();
        $subscription = $user->subscription; // This uses the relationship in User model
        return view('manage_business.create', compact('subscription'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'legal_business_name' => 'required|string|max:255',
            'google_review_link' => 'nullable|string|url',
            'website' => 'nullable|string|url',
        ]);
    
        ManageBusiness::create([
            'user_id' => auth()->id(), // 👈 Assign logged-in user ID
            'business_name' => $request->business_name,
            'legal_business_name' => $request->legal_business_name,
            'google_review_link' => $request->google_review_link,
            'website' => $request->website,
        ]);
    
        return redirect()->route('businesses.index')->with('success', 'Business created successfully.');
    }

    public function edit($id)
    {
        $business = ManageBusiness::findOrFail($id);
        return view('manage_business.edit', compact('business'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'legal_business_name' => 'required|string|max:255',
            'website' => 'nullable|string|url',
        ]);

        $business = ManageBusiness::findOrFail($id);
        $business->update([
            'business_name' => $request->business_name,
            'legal_business_name' => $request->legal_business_name,
            'website' => $request->website,
        ]);

        return redirect()->route('businesses.index')->with('success', 'Business updated successfully.');
    }

    public function switch(Request $request)
    {
        $request->validate([
            'business_id' => 'required',
        ]);

        if ($request->business_id === 'all') {
            session()->forget('active_business_id');
            return redirect()->back()->with('success', 'Switched to All Businesses view');
        }

        $business = ManageBusiness::where('id', $request->business_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        session(['active_business_id' => $business->id]);

        return redirect()->back()->with('success', 'Switched to ' . $business->business_name);
    }

    public function destroy($id)
    {
        $business = ManageBusiness::findOrFail($id);
        $business->delete();

        return redirect()->route('businesses.index')->with('success', 'Business deleted successfully.');
    }

    // Admin Side
    public function admin_index()
    {
        $businesses = ManageBusiness::withCount('cards')->latest()->get();
        return view('admin.manage_business.index', compact('businesses'));
    }

    public function admin_view_business($id)
    {
         $business = ManageBusiness::with(['cards', 'user.subscription.plan'])->withCount('cards')->findOrFail($id);
         $plans = \App\Models\SubscriptionPlan::all();
        return view('admin.manage_business.view', compact('business', 'plans'));
    }

    public function admin_update_status(Request $request, $id)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'legal_business_name' => 'required|string|max:255',
            'status' => 'required|in:active,blocked',
            'plan_id' => 'nullable|exists:subscription_plans,id',
            'website' => 'nullable|string|url',
        ]);

        $business = ManageBusiness::findOrFail($id);
        $business->business_name = $request->input('business_name');
        $business->legal_business_name = $request->input('legal_business_name');
        $business->website = $request->input('website');
        $business->status = $request->input('status');
        $business->save();

        if ($request->filled('plan_id')) {
            $user = $business->user;
            $plan = \App\Models\SubscriptionPlan::find($request->plan_id);
            
            if ($user->subscription) {
                if ($user->subscription->subscription_plan_id != $plan->id) {
                    $user->subscription->update([
                        'subscription_plan_id' => $plan->id,
                        'ends_at' => now()->copy()->addDays((int) $plan->duration_days),
                        'status' => 'active',
                        'stripe_status' => 'succeeded',
                    ]);
                } else {
                    // Even if plan is same, ensure it's fully active if updated by admin
                    $user->subscription->update([
                        'status' => 'active',
                        'stripe_status' => 'succeeded',
                    ]);
                }
            } else {
                \App\Models\Subscription::create([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $plan->id,
                    'started_at' => now(),
                    'ends_at' => now()->copy()->addDays((int) $plan->duration_days),
                    'status' => 'active',
                    'stripe_status' => 'succeeded',
                ]);
            }
        }

        return redirect()
            ->route('admin.manage_business.view', $id)
            ->with('success', 'Business details and User Subscription Plan updated successfully.');
    }


    public function admin_delete($id)
    {
        $business = ManageBusiness::findOrFail($id);

        $business->delete();

        return redirect()->route('admin.manage_business.index')->with('success', 'Business deleted successfully.');
    }

    public function admin_create()
    {
        $users = \App\Models\User::where('role', 'bussiness_owner')->get();
        return view('admin.manage_business.create', compact('users'));
    }

    public function admin_store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'business_name' => 'required|string|max:255',
            'legal_business_name' => 'required|string|max:255',
            'google_review_link' => 'nullable|string|url',
            'website' => 'nullable|string|url',
        ]);
    
        ManageBusiness::create([
            'user_id' => $request->user_id,
            'business_name' => $request->business_name,
            'legal_business_name' => $request->legal_business_name,
            'google_review_link' => $request->google_review_link,
            'website' => $request->website,
        ]);
    
        return redirect()->route('admin.manage_business.index')->with('success', 'Business created successfully by Admin.');
    }
}