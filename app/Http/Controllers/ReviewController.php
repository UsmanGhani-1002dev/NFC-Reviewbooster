<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class ReviewController extends Controller
{
    public function showForm()
    {
        return view('reviews.form');
    }

    public function GateReview()
    {
        return view('reviews.reviewgate');
    }

   public function show($token)
    {
        $card = Card::where('token', $token)->firstOrFail();

        // Check if business owner has an active subscription
        $user = $card->user;
        if (!$user || !$user->hasActiveSubscription()) {
            return redirect()->away($card->google_review_link);
        }

        // Check if review limit is reached for the current plan
        $plan = $user->subscription->plan ?? null;
        if ($plan && $plan->review_limit != -1) {
            $currentReviewsCount = $user->reviews()->count();
            if ($currentReviewsCount >= $plan->review_limit) {
                // Limit reached, bypass the gate and go directly to Google
                return redirect()->away($card->google_review_link);
            }
        }
    
        $rating = null;
        $reviewCount = null;
        $googleBusinessName = null;
        $businessPhoto = null;
    
        if ($card->google_review_link) {
            $placeId = $this->extractPlaceIdFromLink($card->google_review_link);
    
            if ($placeId) {
                $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                    'place_id' => $placeId,
                    'fields' => 'name,rating,user_ratings_total,photos',
                    'key' => env('GOOGLE_PLACES_API_KEY'),
                ]);
    
                Log::info('Google Places API response:', $response->json());
    
                if ($response->successful()) {
                    $data = $response->json();
    
                    if (isset($data['result']) && ($data['status'] ?? 'OK') === 'OK') {
                        $rating = $data['result']['rating'] ?? null;
                        $reviewCount = $data['result']['user_ratings_total'] ?? null;
                        $googleBusinessName = $data['result']['name'] ?? null;

                        // Build business photo URL from Google Places API
                        if (!empty($data['result']['photos'][0]['photo_reference'])) {
                            $photoRef = $data['result']['photos'][0]['photo_reference'];
                            $businessPhoto = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=200&photo_reference=' . $photoRef . '&key=' . env('GOOGLE_PLACES_API_KEY');
                        }
                    } else {
                        Log::warning('Google Places API returned error', ['response' => $data]);
                    }
                } else {
                    Log::error('Google Places API HTTP error', ['status' => $response->status()]);
                }
            } else {
                Log::warning('Failed to extract Place ID from google_review_link', ['link' => $card->google_review_link]);
            }
        }
    
        return view('reviews.reviewgate', compact('card', 'rating', 'reviewCount', 'googleBusinessName', 'businessPhoto'));
    }
    
    private function extractPlaceIdFromLink(string $link): ?string
    {
        $parts = parse_url($link);
        if (!isset($parts['query'])) {
            return null;
        }
    
        parse_str($parts['query'], $query);
    
        return $query['placeid'] ?? null;
    }



    public function badreview()
    {
        return view('reviews.feedback');
    }

    public function showFeedbackForm($token)
    {
        $card = Card::where('token', $token)->firstOrFail();
        return view('reviews.feedback', compact('card'));
    }

    public function trackGoogleReview($token)
    {
        $card = Card::where('token', $token)->firstOrFail();

        Review::create([
            'card_id' => $card->id,
            'name' => 'Google Reviewer',
            'email' => 'noreply@google.com',
            'review' => 'Redirected to Google for review.',
            'status' => 'resolved',
            'rating' => 5,
        ]);

        return redirect()->away($card->google_review_link);
    }

    public function feedbackstore(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);
    
        $review = new Review([
            'card_id' => $request->card_id, // associate review with the card
            'name' => $request->name,
            'email' => $request->email,
            'review' => $request->review,
            'status' => 'active',
            'rating' => $request->rating, 
        ]);
    
        $review->save();

        $website = null;
        if ($request->card_id) {
            $card = \App\Models\Card::with('business')->find($request->card_id);
            if ($card && $card->business) {
                $website = $card->business->website;
            }
        }
    
        return redirect()->route('reviews.success')->with('success', 'Review submitted successfully!')->with('business_website', $website);
    }

    public function success() 
    {
        return view('reviews.success');
    }

   public function adminReviews(Request $request)
    {
        $user = auth()->user();
    
        $query = Review::query();
    
        // Restrict to business owner's cards if applicable
        if ($user->role === 'bussiness_owner') {
            $cardIds = $user->cards()->pluck('id');
            $query->whereIn('card_id', $cardIds);
        }
    
        // Apply filters
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
    
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
    
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
    
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('review', 'like', '%' . $request->search . '%');
            });
        }
    
        // Get filtered reviews
        $reviews = $query->latest()->get();
    
        // Stats
        $totalReviews = $reviews->count();
        $averageRating = $reviews->avg('rating') ?: 0;

        // Calculate statistics for the dashboard
        $newReviewsAllTime = $totalReviews;
        
        $newReviewsThisMonthQuery = Review::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
        
        $previousReviewsQuery = Review::where('created_at', '<', now()->startOfMonth());

        if ($user->role === 'bussiness_owner') {
            $newReviewsThisMonthQuery->whereIn('card_id', $cardIds);
            $previousReviewsQuery->whereIn('card_id', $cardIds);
        }

        $newReviewsThisMonth = $newReviewsThisMonthQuery->count();
        $initialTotalReviews = $previousReviewsQuery->count();
        $initialAverageRating = $previousReviewsQuery->avg('rating') ?: 0;

        $newReviewsAllTimePercentageChange = 0;
        if ($initialTotalReviews > 0) {
            $newReviewsAllTimePercentageChange = (($totalReviews - $initialTotalReviews) / $initialTotalReviews) * 100;
        }

        // Rating change: today vs yesterday
        $todayQuery = Review::query();
        $yesterdayQuery = Review::query();
    
        if ($user->role === 'bussiness_owner') {
            $todayQuery->whereIn('card_id', $cardIds);
            $yesterdayQuery->whereIn('card_id', $cardIds);
        }
    
        $currentDayAverageRating = $todayQuery->whereDate('created_at', now()->toDateString())->avg('rating') ?: 0;
        $previousDayAverageRating = $yesterdayQuery->whereDate('created_at', now()->subDay()->toDateString())->avg('rating') ?: 0;
        $ratingChangeThisDay = $currentDayAverageRating - $previousDayAverageRating;
    
        // Today's negative reviews (rating < 3)
        $negativeTodayQuery = Review::where('rating', '<', 3)
            ->whereDate('created_at', now()->toDateString());
    
        if ($user->role === 'bussiness_owner') {
            $negativeTodayQuery->whereIn('card_id', $cardIds);
        }
    
        $negativeTodayCount = $negativeTodayQuery->count();

        $positiveTodayQuery = Review::where('rating', '>=', 3)
            ->whereDate('created_at', now()->toDateString());

        if ($user->role === 'bussiness_owner') {
            $positiveTodayQuery->whereIn('card_id', $cardIds);
        }

        $positiveTodayCount = $positiveTodayQuery->count();

        // Review Timeline Chart Data
        $timelineLabels = [];
        $timelineData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $timelineLabels[] = $month->format('M');
            
            $monthQuery = Review::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year);
            if ($user->role === 'bussiness_owner') {
                $monthQuery->whereIn('card_id', $cardIds);
            }
            $timelineData[] = $monthQuery->count();
        }

        return view('admin.reviews.index', compact(
            'reviews',
            'totalReviews',
            'averageRating',
            'ratingChangeThisDay',
            'negativeTodayCount',
            'positiveTodayCount',
            'newReviewsAllTime',
            'newReviewsThisMonth',
            'initialTotalReviews',
            'initialAverageRating',
            'newReviewsAllTimePercentageChange',
            'timelineLabels',
            'timelineData'
        ));
    }


    // public function showPositiveReviews(Request $request)
    // {
    //     $query = Review::where(function ($query) {
    //         $query->where('status', 'approved')
    //               ->orWhere('rating', '>=', 3);
    //     });

    //     $totalReviews = $query->count();
    //     $averageRating = $query->avg('rating');
    
    //     if ($request->filled('stars')) {
    //         $query->where('rating', '>=', $request->stars);
    //     }

    //     $reviews = $query->latest()->paginate(10);
    
    //     $reviews->appends($request->all());
    
    //     return view('reviews.positive', compact('reviews', 'totalReviews', 'averageRating'));
    // }
    

    public function approve(Review $review)
    {
        $review->status = 'resolved';
        $review->save();
        
        return redirect()->back()->with('success', 'Review approved successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->back()->with('success', 'Review deleted successfully');
    }

    public function showCardReviews(Card $card)
    {
        $this->authorize('view', $card); // optional for security
        $reviews = $card->reviews()->where('status', 'pending')->get();

        return view('dashboard.card-reviews', compact('card', 'reviews'));
    }
    
    public function updateStatus($id, $status)
    {
        $validStatuses = ['contacted', 'resolved', 'active'];
        
        if (!in_array($status, $validStatuses)) {
            return redirect()->back()->with('error', 'Invalid status selected.');
        }
    
        $review = Review::findOrFail($id);
        $review->status = $status;
        $review->save();
    
        return redirect()->back()->with('success', 'Review status updated successfully.');
    }
}
