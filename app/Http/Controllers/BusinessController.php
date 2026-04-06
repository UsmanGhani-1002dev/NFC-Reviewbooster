<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReviewContactedMail;

class BusinessController extends Controller
{
    public function businessReviews(Request $request)
    {
        $user = auth()->user();
    
        $query = Review::query();
    
        // Restrict to business owner's cards if applicable
        if ($user->role === 'bussiness_owner') {
            $activeBusinessId = session('active_business_id');
            $cardQuery = $user->cards();
            if ($activeBusinessId) {
                $cardQuery->where('business_id', $activeBusinessId);
            }
            $cardIds = $cardQuery->pluck('id');
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
    
        $negativeTodayQuery = Review::where('rating', '<', 3)
            ->whereDate('created_at', now()->toDateString());
    
            
        if ($user->role === 'bussiness_owner') {
            $negativeTodayQuery->whereIn('card_id', $cardIds);
        }

        $positiveTodayQuery = Review::where('rating', '>=', 3)
            ->whereDate('created_at', now()->toDateString());

        if ($user->role === 'bussiness_owner') {
            $positiveTodayQuery->whereIn('card_id', $cardIds);
        }

        $positiveTodayCount = $positiveTodayQuery->count();
    
        $negativeTodayCount = $negativeTodayQuery->count();

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
    
        return view('business.reviews.index', compact(
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


     public function businessAllReviews(Request $request)
    {
        $user = auth()->user();

        $query = Review::query();

        // If business owner, limit to their own cards' reviews
        if ($user->role === 'bussiness_owner') {
            $activeBusinessId = session('active_business_id');
            $cardQuery = $user->cards();
            if ($activeBusinessId) {
                $cardQuery->where('business_id', $activeBusinessId);
            }
            $cardIds = $cardQuery->pluck('id'); // Get IDs of cards owned by the user
            $query->whereIn('card_id', $cardIds);
        }

          $query->where('rating', '<', 3);
          
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

        // Apply search filter
        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('review', 'like', '%' . $request->search . '%');
            });
        }

        // Final list of filtered reviews
        $reviews = $query->get();

        // Calculate total and average rating for the filtered set
        $totalReviews = $reviews->count();
        $averageRating = $reviews->avg('rating');

        // Compare today's vs yesterday's average rating (also scoped for business_owner)
        $todayQuery = Review::query();
        $yesterdayQuery = Review::query();

        if ($user->role === 'bussiness_owner') {
            $todayQuery->whereIn('card_id', $cardIds);
            $yesterdayQuery->whereIn('card_id', $cardIds);
        }

        $currentDayAverageRating = $todayQuery->whereDate('created_at', now()->toDateString())->avg('rating');
        $previousDayAverageRating = $yesterdayQuery->whereDate('created_at', now()->subDay()->toDateString())->avg('rating');

        $ratingChangeThisDay = $currentDayAverageRating - $previousDayAverageRating;

        return view('business.reviews.allreview', compact(
            'reviews',
            'totalReviews',
            'averageRating',
            'ratingChangeThisDay'
        ));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $request->validate([
            'status' => 'required|in:contacted,resolved,active',
            'ai_message' => 'nullable|string',
        ]);

        $oldStatus = $review->status;
        $review->status = $request->status;
        if ($request->filled('ai_message')) {
            $review->ai_response = $request->ai_message; // Save it to the record too
        }
        $review->save();

        // Send email if status changed to 'contacted'
        if ($request->status === 'contacted' && $oldStatus !== 'contacted') {
            try {
                Mail::to($review->email)->send(new ReviewContactedMail($review, $request->ai_message));

                return back()->with('success', 'Review status updated!' . ($request->filled('ai_message') ? ' Custom AI response' : ' Generic email') . ' sent to customer!');
            } catch (\Exception $e) {
                // Log the error but don't fail the status update
                \Log::error('Failed to send review contacted email: ' . $e->getMessage());

                return back()->with('success', 'Review status updated! (Email sending failed - please check logs)');
            }
        }

        return back()->with('success', 'Review status updated!');
    }
}
