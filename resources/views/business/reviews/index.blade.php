@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header Section -->
    <div class="flex flex-col-reverse items-center text-center md:items-start md:text-left md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl md:text-4xl font-semibold text-gray-900">Customer Reviews</h1>
            <p class="text-sm font-medium text-gray-400 mt-1">Monitor and analyze all customer feedback across platforms</p>
        </div>
        <div class="flex items-center justify-center md:justify-start gap-3">
            <div class="px-4 py-2 bg-white border border-gray-200 rounded-xl shadow-sm flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-bold text-gray-600 uppercase tracking-widest">Live Feedback</span>
            </div>
        </div>
    </div>

   <!-- Rating summary card -->
   <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-8">
        <div class="flex flex-col-reverse md:flex-row">
            <!-- Left side: Rating distribution -->
            <div class="flex-grow p-4 sm:p-6 space-y-4">
                @php $currentTotalReviews = $reviews->count(); @endphp
                @foreach ([5, 4, 3, 2, 1] as $rating)
                    @php
                        $ratingCount = $reviews->where('rating', $rating)->count();
                        $percentage = $currentTotalReviews > 0 ? ($ratingCount / $currentTotalReviews) * 100 : 0;
                    @endphp
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        <span class="text-base sm:text-lg font-medium text-gray-700 w-6">{{ $rating }}</span>
                        <span class="text-yellow-400 text-base sm:text-lg">★</span>
                        <div class="relative flex-grow bg-gray-200 rounded-full h-2.5 min-w-[100px] sm:min-w-[150px]">
                            <div class="bg-yellow-400 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-base sm:text-lg font-medium text-gray-600 w-10 text-right">{{ $ratingCount }}</span>
                    </div>
                @endforeach
            </div>
            

            <!-- Right side: Average rating -->
            <div class="bg-yellow-50 p-8 md:w-64 flex flex-col items-center justify-center">
                {{-- Ensure $averageRating and $currentTotalReviews are passed correctly --}}
                <div class="text-5xl font-bold text-yellow-400 mb-4">{{ number_format($averageRating ?? 0, 1) }}</div>
                @php
                    $roundedRating = round($averageRating * 2) / 2; 
                    $fullStars = floor($roundedRating);
                    $halfStar = ($roundedRating - $fullStars) == 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                @endphp

                <div class="flex mb-3">
                    {{-- Full Stars --}}
                    @for ($i = 0; $i < $fullStars; $i++)
                        <span class="text-5xl text-yellow-400">★</span>
                    @endfor

                    {{-- Half Star --}}
                    @if ($halfStar)
                        <span class="text-5xl text-yellow-400 relative">
                            <span class="absolute left-0 top-0 overflow-hidden w-1/2">★</span>
                            <span class="text-gray-300">★</span>
                        </span>
                    @endif

                    {{-- Empty Stars --}}
                    @for ($i = 0; $i < $emptyStars; $i++)
                        <span class="text-5xl text-gray-300">★</span>
                    @endfor
                </div>
                <p class="text-gray-700 font-medium">{{ $currentTotalReviews }} Ratings</p>
            </div>
        </div>
    </div>

    <!-- START: New Statistics Dashboard Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">

        <!-- Box 1: Reviews Overview -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl shadow-xl border border-gray-200 p-6 text-white">
            <h2 class="text-lg font-semibold text-white-800 mb-4">Review Stats</h2>
            <div class="grid grid-cols-[1fr_1.5fr_1fr] gap-6">
                <!-- Total Reviews -->
                <div>
                    <h3 class="text-sm font-medium text-white-500 uppercase tracking-wider">Total Reviews</h3>
                    <p class="text-3xl font-bold text-white-600 mt-1">{{ $totalReviews ?? '0' }}</p>
                    <p class="text-xs text-white-400">Started at {{ $initialTotalReviews ?? '0' }}</p>
                </div>
                <!-- New Reviews All Time -->
                <div>
                    <h3 class="text-sm font-medium text-white-500 uppercase tracking-wider">New Reviews</h3>
                    <p class="text-3xl font-bold text-white-600 mt-1">{{ $newReviewsAllTime ?? '0' }}</p>
                    <p class="text-xs text-white-400">+{{ number_format($newReviewsAllTimePercentageChange ?? 0, 1) }}% All Time</p>
                </div>
                <!-- New Reviews This Month -->
                <div>
                    <h3 class="text-sm font-medium text-white-500 uppercase tracking-wider">This Month</h3>
                    <p class="text-3xl font-bold text-white-600 mt-1 flex items-center">
                        {{ $newReviewsThisMonth ?? '0' }}
                        <svg class="w-5 h-5 ml-1 text-white-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.3 4.3a12 12 0 0 1 5.8-5.5l2.75-1.2m0 0-5.95-2.3m5.95 2.3-2.3 5.95" />
                        </svg>
                    </p>
                    <p class="text-xs text-white-400">Growth</p>
                </div>
            </div>
        </div>
    
        <!-- Box 2: Rating Overview -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Ratings Summary</h2>
            <div class="grid grid-cols-[1fr_2fr_1fr] gap-6">
                <!-- Average Rating -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Avg Rating</h3>
                    <p class="text-3xl font-bold text-indigo-600 mt-1">{{ number_format($averageRating ?? 0, 1) }}</p>
                    <p class="text-xs text-gray-400">Started at {{ number_format($initialAverageRating ?? 0, 1) }}</p>
                </div>
    
                <!-- Rating Distribution -->
                <div class="pt-1">
                    @php $currentTotalReviews = $reviews->count(); @endphp
                    @foreach ([5, 4, 3, 2, 1] as $rating)
                        @php
                            $count = $reviews->where('rating', $rating)->count();
                            $percent = $currentTotalReviews > 0 ? ($count / $currentTotalReviews) * 100 : 0;
                        @endphp
                        <div class="flex items-center mb-1">
                            <span class="text-sm text-gray-600 w-5">{{ $rating }}</span>
                            <div class="flex-grow bg-gray-200 rounded-full h-2 mx-2">
                                <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
    
                <!-- Rating Change -->
                @php
                    $change = $ratingChangeThisDay ?? 0.1;
                    $isPositive = $change >= 0;
                @endphp
                <div>
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Today</h3>
                    <p class="text-3xl font-bold {{ $isPositive ? 'text-green-600' : 'text-red-600' }} mt-1 flex items-center">
                        {{ $isPositive ? '+' : '' }}{{ number_format($change, 1) }}
                        <svg class="w-5 h-5 ml-1 {{ $isPositive ? 'text-green-600 rotate-0' : 'text-red-600 rotate-180' }}" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.3 4.3a12 12 0 0 1 5.8-5.5l2.75-1.2m0 0-5.95-2.3m5.95 2.3-2.3 5.95" />
                        </svg>
                    </p>
                    <p class="text-xs text-gray-400">Rating change</p>
                </div>
            </div>
        </div>
    
        <!-- NEW BOX 5: Review Timeline -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Review Timeline</h2>
            <div class="h-48 w-full">
                <canvas id="reviewTimelineChart"></canvas>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('reviewTimelineChart').getContext('2d');
                    
                    const labels = @json($timelineLabels ?? []);
                    const data = {
                        labels: labels,
                        datasets: [{
                            label: 'Reviews',
                            data: @json($timelineData ?? []),
                            borderColor: 'rgb(99, 102, 241)',
                            backgroundColor: 'rgba(99, 102, 241, 0.2)',
                            tension: 0.3,
                            fill: true
                        }]
                    };
                    
                    new Chart(ctx, {
                        type: 'line',
                        data: data,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: true,
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        </div>
    
        <!-- NEW BOX 9: Sentiment Analysis -->
        @php
            $positiveCount = $reviews->where('rating', '>=', 4)->count(); // 4 & 5 stars
            $neutralCount = $reviews->where('rating', 3)->count();        // 3 stars
            $negativeCount = $reviews->where('rating', '<=', 2)->count(); // 1 & 2 stars
            $totalSentiments = $positiveCount + $neutralCount + $negativeCount;
        @endphp
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Sentiment Analysis</h2>
    
        <div class="h-48">
            <canvas id="sentimentChart"></canvas>
        </div>
    
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const positive = {{ $positiveCount }};
                const neutral = {{ $neutralCount }};
                const negative = {{ $negativeCount }};
                const total = positive + neutral + negative;
    
                const ctx = document.getElementById('sentimentChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Positive', 'Neutral', 'Negative'],
                        datasets: [{
                            data: [positive, neutral, negative],
                            backgroundColor: [
                                'rgba(34, 197, 94, 0.7)',   // Green
                                'rgba(59, 130, 246, 0.7)',  // Blue
                                'rgba(239, 68, 68, 0.7)'    // Red
                            ],
                            borderColor: [
                                'rgb(34, 197, 94)',
                                'rgb(59, 130, 246)',
                                'rgb(239, 68, 68)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
    
                // Update text values below the chart
                document.getElementById('positivePercentage').textContent = total > 0 ? ((positive / total) * 100).toFixed(1) + '%' : '0%';
                document.getElementById('neutralPercentage').textContent = total > 0 ? ((neutral / total) * 100).toFixed(1) + '%' : '0%';
                document.getElementById('negativePercentage').textContent = total > 0 ? ((negative / total) * 100).toFixed(1) + '%' : '0%';
            });
        </script>
    
        <div class="mt-2 grid grid-cols-3 text-center">
            <div>
                <p class="text-sm font-medium text-gray-500">Positive</p>
                <p id="positivePercentage" class="text-lg font-bold text-green-600">0%</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Neutral</p>
                <p id="neutralPercentage" class="text-lg font-bold text-blue-600">0%</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Negative</p>
                <p id="negativePercentage" class="text-lg font-bold text-red-600">0%</p>
            </div>
        </div>
    </div>
    
    </div>
    
    <!-- Make sure to include Chart.js for the charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    
     <!-- Reviews Table -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Latest Reviews</h2>

        <div class="bg-white rounded-xl shadow-md border border-gray-100 mb-8 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 hidden md:table">
                <thead>
                    <tr class="bg-black text-white">
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-50">Business</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-50">Customer</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-50">Rating</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-50">Comment</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-50">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-50">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-medium text-gray-50">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Ensure $reviews collection is passed --}}
                    @forelse ($reviews->sortByDesc('created_at')->take(10) as $review)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-8 py-6">
                            <span class="text-sm font-extrabold text-gray-900 group-hover:text-blue-600 transition-colors">
                                {{ $review->card->business->legal_business_name ?? 'N/A' }}
                            </span>
                            <p class="text-[10px] font-bold text-gray-400 uppercase mt-0.5 tracking-tighter">{{ optional($review->card)->name }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-800">{{ $review->name }}</span>
                                <span class="text-[11px] text-gray-400 font-medium">{{ $review->email }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 rating" data-rating="{{ $review->rating }}">
                            <div class="flex text-yellow-400 gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="text-base {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                                @endfor
                            </div>
                        </td>                    
                        <td class="px-8 py-6">
                            <div class="text-xs text-gray-600 line-clamp-2 max-w-xs font-medium leading-relaxed">
                                {{ $review->review }}
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($review->status == 'contacted')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Contacted
                                </span>
                            @elseif($review->status == 'resolved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Resolved
                                </span>
                            @elseif($review->status == 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($review->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-5 text-sm">
                            <div class="flex items-center space-x-3">
                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors duration-200" title="Delete Review">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                                <button type="button" class="text-blue-600 hover:text-blue-900 transition-colors duration-200 view-review-btn" data-review-id="{{ $review->id }}" title="View Full Review">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900">No reviews found</h3>
                                    <p class="text-gray-500 text-sm">You haven't received any reviews yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Responsive Card View for Mobile -->
            <div class="md:hidden space-y-3 px-3 py-2">
                @forelse ($reviews->sortByDesc('created_at')->take(10) as $review)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                    {{-- Card Header --}}
                    <div class="flex items-center justify-between px-4 pt-4 pb-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                <span class="text-indigo-600 font-semibold text-xs uppercase">
                                    {{ strtoupper(substr($review->card->business->legal_business_name ?? 'N', 0, 1)) }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate leading-tight">
                                    {{ $review->card->business->legal_business_name ?? 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-400 leading-tight">{{ $review->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        @php
                            $statusColors = [
                                'contacted' => 'bg-blue-100 text-blue-800',
                                'resolved'  => 'bg-green-50 text-green-700',
                                'active' => 'bg-yellow-100 text-yellow-800',
                            ];
                            $statusClass = $statusColors[strtolower($review->status)] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="ml-2 flex-shrink-0 text-xs font-medium px-2.5 py-1 rounded-full {{ $statusClass }}">
                            {{ ucfirst($review->status) }}
                        </span>
                    </div>

                    {{-- Divider --}}
                    <div class="mx-4 border-t border-gray-50"></div>

                    {{-- Reviewer Info + Stars --}}
                    <div class="flex items-center justify-between px-4 py-2.5">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-700 truncate">{{ $review->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $review->email }}</p>
                        </div>
                        <div class="flex items-center gap-0.5 flex-shrink-0 ml-3">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>

                    {{-- Review Text --}}
                    <div class="px-4 pb-3">
                        <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">{{ $review->review }}</p>
                    </div>

                    {{-- Card Footer / Actions --}}
                    <div class="flex items-center justify-end gap-1 px-3 py-6">
                        <button
                            type="button"
                            class="view-review-btn flex items-center gap-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors duration-150"
                            data-review-id="{{ $review->id }}"
                            title="View Full Review"
                        >
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            View
                        </button>

                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="flex items-center gap-1.5 text-xs font-medium text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors duration-150"
                                title="Delete Review"
                            >
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500">No reviews found</p>
                    <p class="text-xs text-gray-400 mt-1">Reviews will appear here once submitted</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    


<!-- Modal for viewing full review -->
<div id="reviewModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-auto p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center mb-3">
                <div id="modal-user-avatar" class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3 flex-shrink-0">
                    <span class="text-blue-600 font-medium text-sm"></span>
                </div>
                <div>
                    <h4 id="modal-user-name" class="text-xl font-black text-gray-900"></h4>
                    <span id="modal-email" class="text-sm text-gray-500 block"></span>
                    <span id="modal-date" class="text-xs font-medium text-gray-400 mt-0.5 tracking-tighter"></span>
                </div>
            </div>
            <button type="button" class="close-modal p-2.5 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-2xl transition-all relative top-[-20px]">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 mt-2">
            <div class="flex text-yellow-400 gap-1 mb-4" id="modal-rating"></div>
            <p id="modal-review-text" class="text-gray-700 text-sm font-medium leading-relaxed italic"></p>
        </div>

        {{-- AI RESPONDER SECTION (PREMIUM ONLY) --}}
        @if(auth()->user()->subscription && auth()->user()->subscription->plan && auth()->user()->subscription->plan->has_ai_replies)
        <div id="ai-responder-section" class="mt-4 border-t border-gray-100 pt-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-indigo-100 rounded-lg text-indigo-600">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                    </div>
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">AI Review Assistant</span>
                </div>
                <button type="button" id="generate-ai-reply" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1.5">
                    <i data-lucide="zap" class="w-3.5 h-3.5 bg-indigo-100 rounded-full p-0.5"></i>
                    Generate Smart Reply
                </button>
            </div>
            
            <div id="ai-loading" class="hidden py-4 text-center">
                <div class="inline-block animate-spin rounded-full h-5 w-5 border-2 border-indigo-600 border-t-transparent mb-2"></div>
                <p class="text-[10px] text-gray-400 font-medium">Crafting perfect response...</p>
            </div>

            <div id="ai-response-container" class="hidden">
                <textarea id="ai-response-text" class="w-full bg-indigo-50/50 border border-indigo-100 rounded-xl p-3 text-xs text-gray-700 focus:ring-1 focus:ring-indigo-300 transition-all font-medium leading-relaxed" rows="3"></textarea>
                <div class="flex justify-end mt-2">
                    <button type="button" id="copy-ai-response" class="flex items-center gap-1.5 text-[10px] font-bold text-indigo-600 bg-white border border-indigo-100 px-2.5 py-1.5 rounded-lg hover:bg-indigo-50 transition-colors">
                        <i data-lucide="copy" class="w-3 h-3"></i>
                        Copy Response
                    </button>
                </div>
            </div>
        </div>
        @endif

        <div class="mt-6 pt-4 border-t border-gray-200 flex justify-between items-center">
        <!-- Status Dropdown Section -->
        <div class="flex items-center space-x-3">
          <label for="statusDropdown" class="text-sm font-medium text-gray-700">Status:</label>
          <div class="relative">
            <select id="statusDropdown" class="appearance-none bg-white border border-gray-300 rounded-md px-8 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
              <option value="" disabled selected>Select Status</option>
              <option value="contacted">Contacted</option>
              <option value="resolved">Resolved</option>
              <option value="active">Active</option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </div>
          </div>
          <button id="updateStatusBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed" disabled>
            Update
          </button>
        </div>
        <!-- Close Button -->
        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 close-modal">Close</button>
      </div>
    </div>
  </div>
  
  
  <!-- JavaScript for Modal -->
  <script>
  document.addEventListener('DOMContentLoaded', function () {
          // Keep the modal pinned to the viewport. If it stays inside a parent
          // that has a CSS transform/filter, position:fixed anchors to that
          // parent instead of the screen, so it opens mid-page and needs
          // scrolling. Moving it to <body> guarantees true viewport centering.
          const _reviewModal = document.getElementById('reviewModal');
          if (_reviewModal && _reviewModal.parentElement !== document.body) {
              document.body.appendChild(_reviewModal);
          }

          const statusFilter = document.getElementById('statusFilter');
          const ratingFilter = document.getElementById('ratingFilter');
          const dateFrom = document.getElementById('dateFrom');
          const dateTo = document.getElementById('dateTo');
          const rows = document.querySelectorAll('tbody tr');
  
          function filterRows() {
              const statusValue = statusFilter.value.toLowerCase();
              const ratingValue = ratingFilter ? ratingFilter.value : '';
              const dateFromValue = dateFrom.value;
              const dateToValue = dateTo.value;
          
              let anyVisible = false;
          
              rows.forEach(row => {
                  const statusCell = row.querySelector('td:nth-child(5)');
                  const ratingCell = row.querySelector('td:nth-child(3)');
                  const dateCell = row.querySelector('td:nth-child(6)');
          
                  if (!statusCell || !ratingCell || !dateCell) return;
          
                  const date = new Date(dateCell.textContent);
                  const rating = parseInt(ratingCell.getAttribute('data-rating'));
          
                  const statusMatches = !statusValue || statusCell.textContent.toLowerCase().includes(statusValue);
                  const ratingMatches = !ratingValue || rating == ratingValue;
                  const dateMatches = (!dateFromValue || date >= new Date(dateFromValue)) && (!dateToValue || date <= new Date(dateToValue));
          
                  if (statusMatches && ratingMatches && dateMatches) {
                      row.classList.remove('hidden');
                      anyVisible = true;
                  } else {
                      row.classList.add('hidden');
                  }
              });
          
              const noResultsRow = document.getElementById('noResultsRow');
              if (noResultsRow) {
                  noResultsRow.classList.toggle('hidden', anyVisible);
              }
          }
  
          if (statusFilter) {
              statusFilter.addEventListener('change', filterRows);
          }
          if (ratingFilter) ratingFilter.addEventListener('change', filterRows);
          if (dateFrom) dateFrom.addEventListener('change', filterRows);
          if (dateTo) dateTo.addEventListener('change', filterRows);
      });
  
  
      document.addEventListener('DOMContentLoaded', function() {
          const modal = document.getElementById('reviewModal');
          const viewButtons = document.querySelectorAll('.view-review-btn');
          const closeButtons = document.querySelectorAll('.close-modal');
          const statusDropdown = document.getElementById('statusDropdown');
          const updateStatusBtn = document.getElementById('updateStatusBtn');
          let currentReviewId = null;
  
          // Review data - Make sure $reviews is properly JSON encoded
          const reviews = @json($reviews->keyBy('id')); // Key by ID for easier lookup
  
          // Enable/disable update button based on dropdown selection
          statusDropdown.addEventListener('change', function() {
              updateStatusBtn.disabled = !this.value;
          });
  
          // Handle status update
          updateStatusBtn.addEventListener('click', function() {
              if (!currentReviewId || !statusDropdown.value) return;
  
              const statusUrl = "{{ url('reviews') }}/" + currentReviewId + "/status";
              const aiTextarea = document.getElementById('ai-response-text');
              const aiMessage = aiTextarea ? aiTextarea.value : '';
              
              // Create and submit form
              const form = document.createElement('form');
              form.action = statusUrl;
              form.method = 'POST';
              let formHtml = `
                  @csrf
                  @method('PATCH')
                  <input type="hidden" name="status" value="${statusDropdown.value}">
              `;

              // If status is contacted, include the AI message
              if (statusDropdown.value === 'contacted' && aiMessage) {
                  formHtml += `<textarea name="ai_message" style="display:none;">${aiMessage}</textarea>`;
              }
              
              form.innerHTML = formHtml;
              document.body.appendChild(form);
              form.submit();
          });
  
          // Open modal with review data
          viewButtons.forEach(button => {
              button.addEventListener('click', function() {
                  const reviewId = this.getAttribute('data-review-id');
                  const review = reviews[reviewId]; // Access review by ID
                  currentReviewId = reviewId;
          
                  if (review) {
                      // Set modal data
                      document.getElementById('modal-user-name').textContent = review.name;
                      document.getElementById('modal-email').textContent = review.email; // <-- Added email here
                      document.getElementById('modal-date').textContent = new Date(review.created_at).toLocaleDateString('en-US', {
                          year: 'numeric', month: 'short', day: 'numeric'
                      });
                      document.getElementById('modal-user-avatar').querySelector('span').textContent = review.name.charAt(0).toUpperCase();
          
                      // Set rating stars
                      const ratingContainer = document.getElementById('modal-rating');
                      ratingContainer.innerHTML = ''; // Clear previous stars
                      for (let i = 1; i <= 5; i++) {
                          const star = document.createElement('span');
                          star.textContent = '★';
                          star.classList.add('text-xl');
                          if (i <= review.rating) {
                              star.classList.add('text-yellow-400');
                          } else {
                              star.classList.add('text-gray-200');
                          }
                          ratingContainer.appendChild(star);
                      }
          
                      document.getElementById('modal-review-text').textContent = review.review;
          
                      // Set current status in dropdown
                      statusDropdown.value = review.status || '';
                      updateStatusBtn.disabled = !statusDropdown.value;
          
                      // Show modal
                      modal.classList.remove('hidden');
                      modal.classList.add('flex');
                      document.body.style.overflow = 'hidden'; // Prevent background scroll
                  } else {
                      console.error('Review data not found for ID:', reviewId);
                  }
              });
          });
  
          // Function to close modal
        const generateAiBtn = document.getElementById('generate-ai-reply');
        const aiLoading = document.getElementById('ai-loading');
        const aiContainer = document.getElementById('ai-response-container');
        const aiTextarea = document.getElementById('ai-response-text');
        const copyAiBtn = document.getElementById('copy-ai-response');

        if (generateAiBtn) {
            generateAiBtn.addEventListener('click', function() {
                const reviewText = document.getElementById('modal-review-text').textContent;
                const ratingStars = document.getElementById('modal-rating').querySelectorAll('.text-yellow-400');
                const ratingCount = ratingStars.length;

                // Show loading
                generateAiBtn.disabled = true;
                aiLoading.classList.remove('hidden');
                aiContainer.classList.add('hidden');

                fetch("{{ route('ai.generate-response') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        review_content: reviewText,
                        rating: ratingCount
                    })
                })
                .then(response => response.json())
                .then(data => {
                    aiLoading.classList.add('hidden');
                    generateAiBtn.disabled = false;

                    if (data.success) {
                        aiTextarea.value = data.generated_text;
                        aiContainer.classList.remove('hidden');
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    } else {
                        alert(data.message || 'Failed to generate AI response.');
                    }
                })
                .catch(error => {
                    aiLoading.classList.add('hidden');
                    generateAiBtn.disabled = false;
                    console.error('Error:', error);
                    alert('An error occurred. Please check your API key.');
                });
            });
        }

        if (copyAiBtn) {
            copyAiBtn.addEventListener('click', function() {
                aiTextarea.select();
                document.execCommand('copy');
                
                const originalText = copyAiBtn.innerHTML;
                copyAiBtn.innerHTML = '<i data-lucide="check" class="w-3 h-3"></i> Copied!';
                if (typeof lucide !== 'undefined') lucide.createIcons();
                
                setTimeout(() => {
                    copyAiBtn.innerHTML = originalText;
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }, 2000);
            });
        }

        // Function to close modal
        const closeModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = ''; // Restore background scroll
            currentReviewId = null;
            statusDropdown.value = '';
            updateStatusBtn.disabled = true;

            // Clear AI response when closing
            if (aiContainer) aiContainer.classList.add('hidden');
            if (aiTextarea) aiTextarea.value = '';
            if (aiLoading) aiLoading.classList.add('hidden');
        };
  
          // Close modal listeners
          closeButtons.forEach(button => {
              button.addEventListener('click', closeModal);
          });
  
          // Close modal when clicking outside the modal content
          modal.addEventListener('click', function(e) {
              if (e.target === modal) {
                  closeModal();
              }
          });
  
          // Close modal on Escape key press
          document.addEventListener('keydown', function(e) {
              if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                  closeModal();
              }
          });
      });
  </script>
</div>
@endsection