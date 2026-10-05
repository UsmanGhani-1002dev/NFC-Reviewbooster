<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Subscription;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PartnerApplicationSubmittedMail;
use Illuminate\Support\Facades\Notification as NotificationFacade; // Just in case, but Notification is a model here
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function downloadReport()
    {
        $user = auth()->user();
        if ($user->role !== 'bussiness_owner') {
            abort(403);
        }

        $activeBusinessId = session('active_business_id');
        $cardQuery = Card::where('user_id', $user->id);
        if ($activeBusinessId) {
            $cardQuery->where('business_id', $activeBusinessId);
        }
        
        $cardIds = $cardQuery->pluck('id');
        $totalReviews = Review::whereIn('card_id', $cardIds)->count();
        $positiveCount = Review::whereIn('card_id', $cardIds)->where('rating', '>=', 3)->count();
        $negativeCount = Review::whereIn('card_id', $cardIds)->where('rating', '<', 3)->count();
        $totalCards = $cardQuery->count();
        
        $recentReviews = Review::whereIn('card_id', $cardIds)
            ->with('card')
            ->latest()
            ->take(15)
            ->get();

        $cardStats = Review::whereIn('card_id', $cardIds)
            ->select('card_id', \DB::raw('COUNT(*) as review_count'))
            ->groupBy('card_id')
            ->orderByDesc('review_count')
            ->get()
            ->map(function ($reviewGroup) {
                $card = Card::find($reviewGroup->card_id);
                return [
                    'name' => $card?->name ?? 'N/A',
                    'review_count' => $reviewGroup->review_count,
                ];
            });

        $data = [
            'totalReviews' => $totalReviews,
            'positiveCount' => $positiveCount,
            'negativeCount' => $negativeCount,
            'totalCards' => $totalCards,
            'recentReviews' => $recentReviews,
            'cardStats' => $cardStats,
        ];

        $pdf = Pdf::loadView('reports.performance', $data);
        return $pdf->download('ReviewBooster_Report_' . now()->format('Y_m_d') . '.pdf');
    }

   public function index()
    {
        $user = auth()->user();
        $query = Review::query();

        $recentNotifications = Notification::unread()->latest()->take(5)->get();
        $showSubscriptionWarning = false;
        $subscriptionExpired = false;
        $subscriptionExpiresIn2Days = false;
        $timeLeft = null;

        $positiveReviews = 0;
        $negativeReviews = 0;
        $totalCards = 0;
        $userCards = [];
        $reviews = [];
        $totalReviewsAllTime = 0;
        $reviewsGroupedByCard = [];

        // Admin-specific data
        $latestUsers = [];
        $recentContacts = [];
        $usersWithCardStats = [];
        $adminStats = [];

        if ($user->role === 'bussiness_owner') {
            logger('Inside business block');

            $subscription = $user->subscription;
            if ($subscription) {
                $now = now();
                $endsAt = Carbon::parse($subscription->ends_at);

                if ($endsAt->isPast()) {
                    $showSubscriptionWarning = true;
                    $subscriptionExpired = true;
                } elseif ($endsAt->diffInDays($now) <= 3) {
                    $showSubscriptionWarning = true;
                    $subscriptionExpiresIn2Days = true;
                    $timeLeft = $endsAt->diffForHumans();
                }
            }

            $activeBusinessId = session('active_business_id');
            
            $cardQuery = Card::where('user_id', $user->id);
            if ($activeBusinessId) {
                $cardQuery->where('business_id', $activeBusinessId);
            }
            
            $cardIds = $cardQuery->pluck('id');
            $totalReviewsAllTime = Review::whereIn('card_id', $cardIds)->count();
            $thisMonth = Carbon::now()->startOfMonth();

            $positiveReviews = Review::whereIn('card_id', $cardIds)
                ->where('rating', '>=', 3)
                ->where('created_at', '>=', $thisMonth)
                ->count();

            $negativeReviews = Review::whereIn('card_id', $cardIds)
                ->where('rating', '<', 3)
                ->where('created_at', '>=', $thisMonth)
                ->count();

            $totalCards = $cardQuery->count();
            $userCards = $cardQuery->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->orderBy('created_at', 'desc')
                ->get();
            $reviews = $query->whereIn('card_id', $cardIds)->latest()->take(10)->get();

            $reviewsGroupedByCard = Review::whereIn('card_id', $cardIds)
                ->select('card_id', \DB::raw('COUNT(*) as review_count'), \DB::raw('AVG(rating) as avg_rating'))
                ->groupBy('card_id')
                ->orderByDesc('review_count')
                ->orderByDesc('avg_rating')
                ->take(5)
                ->get()
                ->map(function ($reviewGroup) {
                    $card = Card::find($reviewGroup->card_id);
                    return [
                        'name' => $card?->name ?? 'N/A',
                        'review_count' => $reviewGroup->review_count,
                        'avg_rating' => round($reviewGroup->avg_rating, 1),
                    ];
                });

            // --- Chart Data: Last 7 Days ---
            $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
            $reviewDailyCounts = Review::whereIn('card_id', $cardIds)
                ->where('created_at', '>=', $sevenDaysAgo)
                ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('COUNT(*) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('count', 'date');

            $chartLabels = [];
            $chartData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $chartLabels[] = Carbon::now()->subDays($i)->format('M d');
                $chartData[] = $reviewDailyCounts[$date] ?? 0;
            }

            // --- Chart Data: Review Type Distribution ---
            $positiveAllTime = Review::whereIn('card_id', $cardIds)->where('rating', '>=', 3)->count();
            $negativeAllTime = Review::whereIn('card_id', $cardIds)->where('rating', '<', 3)->count();
            $reviewStats = [$positiveAllTime, $negativeAllTime];

            $liveFeed = $this->getPreparedLiveFeed($user);
        } elseif ($user->role === 'admin') {
            // Default empty chart data for admin
            $chartLabels = [];
            $chartData = [];
            $reviewStats = [0, 0];

            $latestUsers = User::where('role', '!=', 'admin')
                ->with(['subscription.plan'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Fetch 5 latest contacts
            $recentContacts = ContactSubmission::orderBy('created_at', 'desc')->take(5)->get();

            $usersWithCardStats = User::with(['cards' => function ($q) {
                $q->select('id', 'user_id', 'name', 'created_at')->orderBy('created_at', 'desc');
            }])
                ->where('role', '!=', 'admin')
                ->has('cards')
                ->get();

            // Admin KPI stats
            $adminStats = [
                'totalUsers'         => User::where('role', '!=', 'admin')->count(),
                'totalBusinessOwners'=> User::where('role', 'bussiness_owner')->count(),
                'totalReviews'       => Review::count(),
                'totalOrders'        => \App\Models\Order::count(),
                'totalCards'         => Card::count(),
                'newUsersToday'      => User::where('role', '!=', 'admin')->whereDate('created_at', Carbon::today())->count(),
                'newUsersThisWeek'   => User::where('role', '!=', 'admin')->where('created_at', '>=', Carbon::now()->startOfWeek())->count(),
                'activeSubscriptions'=> Subscription::where('ends_at', '>', Carbon::now())->count(),
                'totalContacts'      => ContactSubmission::count(),
            ];

            $liveFeed = $this->getPreparedLiveFeed($user);
        }

        return view('dashboard', compact(
            'showSubscriptionWarning',
            'subscriptionExpired',
            'subscriptionExpiresIn2Days',
            'timeLeft',
            'recentNotifications',
            'positiveReviews',
            'negativeReviews',
            'totalCards',
            'userCards',
            'latestUsers',
            'recentContacts',
            'usersWithCardStats',
            'adminStats',
            'reviews',
            'totalReviewsAllTime',
            'reviewsGroupedByCard',
            'chartLabels',
            'chartData',
            'reviewStats',
            'liveFeed'
        ));
    }

    private function getPreparedLiveFeed($user)
    {
        if ($user->role === 'bussiness_owner') {
            $cardIds = $user->cards->pluck('id');
            return Review::whereIn('card_id', $cardIds)
                ->with('card')
                ->latest()
                ->take(8)
                ->get()
                ->toBase()
                ->map(function($review) {
                    return [
                        'type' => 'review',
                        'id' => 'review-' . $review->id,
                        'title' => 'New Feedback',
                        'description' => ($review->name ?? 'A customer') . " rated " . ($review->card->name ?? 'a card') . " " . $review->rating . " stars.",
                        'time' => $review->created_at->diffForHumans(),
                        'timestamp' => $review->created_at->toISOString(),
                        'icon' => 'star',
                        'rating' => $review->rating,
                        'name' => $review->name ?? 'Anonymous',
                        'color' => $review->rating >= 4 ? 'emerald' : ($review->rating >= 3 ? 'amber' : 'rose')
                    ];
                });
        } elseif ($user->role === 'admin') {
            $recentReviewsAct = Review::with('card')->latest()->take(5)->get()->toBase()->map(function($review) {
                return [
                    'type' => 'review',
                    'id' => 'review-' . $review->id,
                    'title' => 'Global Feedback',
                    'description' => ($review->name ?? 'A customer') . " rated " . ($review->card->name ?? 'a card') . " " . $review->rating . " stars.",
                    'time' => $review->created_at->diffForHumans(),
                    'timestamp' => $review->created_at->toISOString(),
                    'icon' => 'star',
                    'rating' => $review->rating,
                    'name' => $review->name ?? 'Anonymous',
                    'color' => 'blue'
                ];
            });

            $recentUsersActivity = User::where('role', '!=', 'admin')->latest()->take(5)->get()->toBase()->map(function($user) {
                return [
                    'type' => 'registration',
                    'id' => 'user-' . $user->id,
                    'title' => 'New Registration',
                    'description' => $user->name . " joined the platform.",
                    'time' => $user->created_at->diffForHumans(),
                    'timestamp' => $user->created_at->toISOString(),
                    'user_name' => $user->name,
                    'icon' => 'user-plus',
                    'color' => 'purple'
                ];
            });

            return $recentReviewsAct->merge($recentUsersActivity)->sortByDesc('timestamp')->values();
        }
        return collect([]);
    }

    public function getNotifications()
    {
        $user = auth()->user();
        $liveFeed = $this->getPreparedLiveFeed($user);
        return response()->json($liveFeed);
    }

    public function markNotificationAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    public function applyPartner(Request $request)
    {
        $request->validate([
            'partner_type' => 'required|in:wholesaler,retailer,corporate',
            'vat_number' => 'nullable|string|max:100',
        ]);

        $user = auth()->user();
        $user->partner_type = $request->partner_type;
        $user->partner_status = 'pending';
        if ($request->filled('vat_number')) {
            $user->vat_number = $request->vat_number;
        }
        $user->save();

        // Send email alert to admins
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new PartnerApplicationSubmittedMail($user));
            }
        } catch (\Exception $e) {
            \Log::error('Partner application email to admin error: ' . $e->getMessage());
        }

        return back()->with('success', "Your application for " . $user->partner_type_label . " status has been submitted successfully! Admin will review and approve it shortly.");
    }
}