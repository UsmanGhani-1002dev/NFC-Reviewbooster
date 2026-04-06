<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Models\Card;
use App\Models\ManageBusiness;
use App\Models\Review;
use Illuminate\Http\Request;

class CardController extends Controller
{

   public function index(Request $request)
    {
        $user = auth()->user();
    
        $latestSubscription = $user->subscription()->latest('ends_at')->first();
        $isSubscriptionExpired = !($latestSubscription && !in_array($latestSubscription->status, ['expired', 'cancelled']));
    
        $businessId = $request->get('business_id') ?? session('active_business_id');
        $selectedBusiness = null;
    
        if ($businessId) {
            $selectedBusiness = ManageBusiness::where('id', $businessId)
                ->where('user_id', $user->id)
                ->first();
    
            if ($selectedBusiness) {
                $cards = $user->cards->where('business_id', $selectedBusiness->id);
            }
        }
    
        // fallback if no business filter applied
        $cards = $cards ?? $user->cards;
    
        // 👉 Get count of reviews per card
        $reviewCounts = Review::whereIn('card_id', $cards->pluck('id'))
            ->get()
            ->groupBy('card_id')
            ->map(fn($group) => $group->count());
    
        return view('cards.index', compact('cards', 'isSubscriptionExpired', 'selectedBusiness', 'reviewCounts'));
    }

    
    public function create()
    {
        $user = auth()->user();
        $latestSubscription = $user->subscription()->latest('ends_at')->first();
        $isSubscriptionExpired = true;

        if ($latestSubscription && !in_array($latestSubscription->status, ['expired', 'cancelled'])) {
            $isSubscriptionExpired = false;
        }

        // Check Card Limit
        $plan = $latestSubscription ? $latestSubscription->plan : null;
        $cardLimit = $plan ? $plan->card_limit : 1;
        $currentCardsCount = $user->cards->count();

        if ($currentCardsCount >= $cardLimit) {
            return redirect()->route('cards.index')->with('error', "You have reached the card limit for your current plan ($cardLimit cards). Please upgrade to create more.");
        }

        $businesses = $user->businesses;
        $selectedBusinessId = session('active_business_id');
        return view('cards.create', compact('businesses', 'selectedBusinessId'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $latestSubscription = $user->subscription()->latest('ends_at')->first();
        $isSubscriptionExpired = true;

        if ($latestSubscription && !in_array($latestSubscription->status, ['expired', 'cancelled'])) {
            $isSubscriptionExpired = false;
        }

        if ($isSubscriptionExpired) {
            return redirect()->route('cards.index')->with('error', 'Your subscription has expired. Please renew to create new cards.');
        }

        // Check Card Limit
        $plan = $latestSubscription->plan;
        $cardLimit = $plan ? $plan->card_limit : 1;
        $currentCardsCount = $user->cards->count();

        if ($currentCardsCount >= $cardLimit) {
            return redirect()->route('cards.index')->with('error', "You have reached the card limit for your current plan ($cardLimit cards). Please upgrade to create more.");
        }

        $request->validate([
            'business_id' => 'required|exists:manage_businesses,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:google_review,facebook_page,instagram_page',
            'product_type' => 'required|in:card,sticker,stand',
            'url' => 'required|url',
        ]);
        
        $business = ManageBusiness::where('id', $request->business_id)
                           ->where('user_id', auth()->id())
                           ->first();
        
        if (!$business) {
            return back()->withErrors(['business_id' => 'Invalid business selected.']);
        }   

        if ($business->status === 'blocked') {
            return back()->withErrors([
                'business_id' => 'Your business is blocked. Please contact the administrator.'
            ])->withInput();
        }

        $token = Str::random(6); // e.g. Xa91Fz

        // Ensure token is unique
        while (Card::where('token', $token)->exists()) {
            $token = Str::random(6);
        }

        Card::create([
            'user_id' => auth()->id(),
            'business_id' => $request->business_id,
            'name' => $request->name,
            'google_review_link' => $request->url,
            'token' => $token,
            'type' => $request->type,
            'product_type' => $request->product_type,
        ]);

        return redirect()->route('cards.index')->with('success', 'Review Card created successfully.');
    }

    public function destroy(Card $card)
    {

        $card->delete();

        return redirect()->route('cards.index')->with('success', 'Review Card deleted successfully.');
    }

    public function redirectToReview($token)
    {
        $card = Card::where('token', $token)->firstOrFail();

        // Redirect user to the actual Google review link
        return redirect()->away($card->google_review_link);
    }
    
    public function edit(Card $card)
    {
        $types = [
            'google_review' => 'Google Review',
            'facebook_page' => 'Facebook Page',
            'instagram_page' => 'Instagram Page',
        ];

        return view('cards.edit', compact('card', 'types'));
    }

    public function update(Request $request, Card $card)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:google_review,facebook_page,instagram_page',
            'product_type' => 'required|in:card,sticker,stand',
            'url' => 'required|url',
        ]);


        $card->update([
            'name' => $request->name,
            'type' => $request->type,
            'product_type' => $request->product_type,
            'google_review_link' => $request->url,
        ]);

        return redirect()->route('cards.index')->with('success', 'Card updated successfully.');
    }

    // Admin Side
    public function admin_create($business_id)
    {
        $business = ManageBusiness::findOrFail($business_id);
        $user = $business->user;
        
        $latestSubscription = $user->subscription()->latest('ends_at')->first();
        $cardLimit = 1;
        $currentCardsCount = $user->cards->count();
        $limitReached = false;

        if ($latestSubscription) {
            $plan = $latestSubscription->plan;
            $cardLimit = $plan ? $plan->card_limit : 1;
        }
        
        if ($currentCardsCount >= $cardLimit) {
            $limitReached = true;
        }

        $types = [
            'google_review' => 'Google Review',
            'facebook_page' => 'Facebook Page',
            'instagram_page' => 'Instagram Page',
        ];

        return view('admin.manage_business.create_card', compact('business', 'types', 'limitReached', 'cardLimit', 'currentCardsCount'));
    }

    public function admin_store(Request $request, $business_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:google_review,facebook_page,instagram_page',
            'product_type' => 'required|in:card,sticker,stand',
            'url' => 'required|url',
        ]);
        
        $business = ManageBusiness::findOrFail($business_id);
        $user = $business->user;
        $latestSubscription = $user->subscription()->latest('ends_at')->first();
        
        $sessionWarning = null;
        if ($latestSubscription) {
            $plan = $latestSubscription->plan;
            $cardLimit = $plan ? $plan->card_limit : 1;
            $currentCardsCount = $user->cards->count();

            if ($currentCardsCount >= $cardLimit) {
                $sessionWarning = "Note: User has already reached their plan limit ($cardLimit cards). Card created as an Admin override.";
            }
        }

        $token = Str::random(6); // e.g. Xa91Fz

        // Ensure token is unique
        while (Card::where('token', $token)->exists()) {
            $token = Str::random(6);
        }

        Card::create([
            'user_id' => $business->user_id,
            'business_id' => $business->id,
            'name' => $request->name,
            'google_review_link' => $request->url,
            'token' => $token,
            'type' => $request->type,
            'product_type' => $request->product_type,
        ]);

        $redirect = redirect()->route('admin.manage_business.view', $business->id)->with('success', 'Card created successfully by Admin.');
        if ($sessionWarning) {
            $redirect->with('warning', $sessionWarning);
        }
        return $redirect;
    }

    public function admin_edit($business_id, Card $card)
    {
        $business = ManageBusiness::findOrFail($business_id);
        $types = [
            'google_review' => 'Google Review',
            'facebook_page' => 'Facebook Page',
            'instagram_page' => 'Instagram Page',
        ];

        return view('admin.manage_business.edit_card', compact('business', 'card', 'types'));
    }

    public function admin_update(Request $request, $business_id, Card $card)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:google_review,facebook_page,instagram_page',
            'product_type' => 'required|in:card,sticker,stand',
            'url' => 'required|url',
        ]);

        $card->update([
            'name' => $request->name,
            'type' => $request->type,
            'product_type' => $request->product_type,
            'google_review_link' => $request->url,
        ]);

        return redirect()->route('admin.manage_business.view', $business_id)->with('success', 'Card updated successfully by Admin.');
    }

    public function admin_destroy($business_id, Card $card)
    {
        $card->delete();
        return redirect()->route('admin.manage_business.view', $business_id)->with('success', 'Card deleted successfully by Admin.');
    }
}
