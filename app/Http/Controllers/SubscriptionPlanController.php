<?php
namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Stripe\Stripe;


class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::latest()->paginate(10);
        return view('admin.subscription_plans.index', compact('plans'));
    }


    public function create()
    {
        return view('admin.subscription_plans.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'card_limit' => 'required|integer|min:1',
            'review_limit' => 'required|integer|min:-1',
            'has_ai_replies' => 'nullable|boolean',
        ]);

        $request->merge(['has_ai_replies' => $request->boolean('has_ai_replies')]);

        SubscriptionPlan::create($request->all());


        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan created successfully.');
    }


    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscription_plans.edit', compact('subscriptionPlan'));
    }


    public function update(Request $request, SubscriptionPlan $subscriptionPlan)


    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'card_limit' => 'required|integer|min:1',
            'review_limit' => 'required|integer|min:-1',
            'has_ai_replies' => 'nullable|boolean',
        ]);

        $request->merge(['has_ai_replies' => $request->boolean('has_ai_replies')]);

        $subscriptionPlan->update($request->all());


        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan updated successfully.');
    }


    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->delete();
        return redirect()->route('admin.subscription-plans.index')->with('success', 'Plan deleted.');
    }
    
    public function createStripeIntent(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id'
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $user = auth()->user();

        Stripe::setApiKey(config('services.stripe.secret'));

        $lastSubscription = $user->subscription()->latest()->first();

        if ($lastSubscription && $lastSubscription->stripe_customer_id) {
            $customer = \Stripe\Customer::retrieve($lastSubscription->stripe_customer_id);
        } else {
            $customer = \Stripe\Customer::create([
                'name' => $user->name,
                'email' => $user->email,
            ]);
        }

        // Create payment intent
        $intent = PaymentIntent::create([
            'amount' => $plan->price * 100, // in cents
            'currency' => 'eur',
            'description' => 'Subscription plan: ' . $plan->name,
            'customer' => $customer->id,
            'metadata' => [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
            ],
        ]);

        return response()->json([
            'clientSecret' => $intent->client_secret,
            'customerId' => $customer->id,
        ]);
    }


    public function showSubscriptionPage()
    {
        $user = auth()->user();
        $user->load('subscriptions.plan');

        $subscriptions = $user->subscriptions->sortByDesc('created_at');

        foreach ($subscriptions as $subscription) {
            if ($subscription->ends_at 
                && \Carbon\Carbon::parse($subscription->ends_at)->isPast() 
                && !in_array($subscription->status, ['expired', 'cancelled'])) {
                $subscription->status = 'expired';
                $subscription->save();
            }
        }

        $plans = \App\Models\SubscriptionPlan::all();

        $showSubscriptionWarning = $user->shouldShowSubscriptionNotification();
        $subscriptionExpired = $user->hasExpiredSubscription();
        $subscriptionExpiresIn24Hours = $user->subscriptionExpiresIn48Hours();
        $timeLeft = $user->getTimeLeftForExpiry();

        return view('subscription.index', compact(
            'subscriptions',
            'plans',
            'showSubscriptionWarning',
            'subscriptionExpired',
            'subscriptionExpiresIn24Hours',
            'timeLeft'
        ));
    }



    public function userUpdateSubscription(Request $request)
    {
        $request->validate([
            'payment_plan' => 'required|exists:subscription_plans,id',
            'payment_intent_id' => 'required|string'
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));
        $intent = \Stripe\PaymentIntent::retrieve($request->payment_intent_id);

        if ($intent->status !== 'succeeded') {
            return response()->json(['success' => false, 'message' => 'Payment not completed.'], 400);
        }

        $user = auth()->user();
        $selectedPlan = SubscriptionPlan::findOrFail($request->payment_plan);

        DB::transaction(function () use ($user, $selectedPlan, $intent) {
            // Expire all previous active subscriptions
            $user->subscriptions()
                ->where('status', 'active')
                ->where('ends_at', '>', now())
                ->update(['status' => 'expired', 'ends_at' => now()]);

            // Create new subscription
            $subscription = new Subscription();
            $subscription->user_id = $user->id;
            $subscription->subscription_plan_id = $selectedPlan->id;
            $subscription->stripe_payment_intent_id = $intent->id;
            $subscription->stripe_customer_id = $intent->customer ?? null;
            $subscription->stripe_status = $intent->status;
            $subscription->started_at = now();
            $subscription->ends_at = now()->addDays((int) $selectedPlan->duration_days);
            $subscription->status = 'active';
            $subscription->save();
        });
        
        session()->forget('subscription_notification_dismissed');

        return response()->json(['success' => true]);
    }
    
    public function dismissNotification()
    {
        // Store dismissed notification with timestamp
        session([
            'subscription_notification_dismissed' => true,
            'notification_dismissed_at' => now()->toDateTimeString(),
        ]);

        return response()->json(['success' => true]);
    }

    // New method to check subscription status (AJAX endpoint)
    public function checkSubscriptionStatus()
    {
        $user = auth()->user();

        return response()->json([
            'has_active' => $user->hasActiveSubscription(),
            'expires_in_48h' => $user->subscriptionExpiresIn48Hours(),
            'is_expired' => $user->hasExpiredSubscription(),
            'time_left' => $user->getTimeLeftForExpiry(),
            'should_show_warning' => $user->shouldShowSubscriptionNotification(),
        ]);
    }
}


