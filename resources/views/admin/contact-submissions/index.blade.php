@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
    <!-- Header Section -->
    <div class="mb-6 font-primary">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <!-- Title & Description -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full md:w-auto">
                <div class="p-2 sm:p-3 bg-indigo-50 rounded-2xl text-indigo-600 shadow-sm border border-indigo-100/50 shrink-0">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Contact Submissions</h1>
                    <p class="text-sm font-medium text-gray-400 mt-1">Manage and respond to user messages and inquiries</p>
                </div>
            </div>
        </div>

        <!-- Search Bar & Mobile Export -->
        <div class="mt-8 flex flex-col sm:flex-row items-center gap-4">
            <form method="GET" class="w-full sm:flex-1 flex flex-col sm:flex-row items-center gap-3">
                <div x-data="autocomplete()" x-init="init()" class="relative w-full sm:max-w-md">
                    <div class="relative text-gray-400 focus-within:text-black">
                        <input
                            type="text"
                            x-model="search"
                            @input.debounce.300ms="fetchSuggestions"
                            @keydown.escape="clearSuggestions"
                            placeholder="Search by name, email, message..."
                            class="block w-full rounded-xl border border-gray-300 bg-white py-3.5 pl-10 pr-10 text-sm placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 focus:outline-none transition-all"
                            autocomplete="off"
                        />
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1111 3a7.5 7.5 0 015.65 13.65z"></path>
                            </svg>
                        </div>

                        <input type="hidden" name="search" x-model="actualValue" />

                        <!-- Clear Button -->
                        <button type="button" @click="clearAll"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors"
                            x-show="search.length > 0"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Suggestions Dropdown -->
                    <div
                        x-show="suggestions.length"
                        class="absolute z-50 mt-2 w-full bg-white border border-gray-100 rounded-xl shadow-2xl max-h-64 overflow-auto py-2"
                        @click.away="clearSuggestions"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                    >
                        <template x-for="item in suggestions" :key="item.value">
                            <div
                                @click="selectSuggestion(item)"
                                class="px-5 py-3 text-sm text-gray-700 hover:bg-indigo-50 cursor-pointer font-medium transition-colors"
                                x-text="item.label"
                            ></div>
                        </template>
                    </div>
                </div>

                <button type="submit"
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3.5 rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-100 active:scale-95">
                    Search
                </button>
            </form>

            <!-- Export Button -->
            <div class="w-full sm:w-auto">
                <a href="{{ route('admin.contact-submissions.index', array_merge(request()->only('search'), ['export' => 'csv'])) }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-100 transition-all transform hover:-translate-y-1 active:scale-95">

                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 8v8m0 0l-3-3m3 3l3-3M6 20h12a2 2 0 002-2v-2"
                            stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>

                    Export CSV
                </a>
            </div>
        </div>
    </div>

    <script>
        function autocomplete() {
            return {
                search: @js(request('search', '')),
                actualValue: @js(request('search', '')),
                suggestions: [],
                init() {
                    if (this.search && this.search.length > 1) {
                        this.fetchSuggestions();
                    }
                },
                fetchSuggestions() {
                    if (this.search.length < 2) {
                        this.suggestions = [];
                        return;
                    }

                    fetch(`{{ route('admin.contact-submissions.suggestions') }}?term=${encodeURIComponent(this.search)}`)
                        .then(res => res.json())
                        .then(data => {
                            this.suggestions = data;
                        });
                },
                selectSuggestion(item) {
                    this.search = item.label;
                    this.actualValue = item.value;
                    this.suggestions = [];
                },
                clearSuggestions() {
                    this.suggestions = [];
                },
                clearAll() {
                    const hadFilter = !!this.actualValue;
                    this.search = '';
                    this.actualValue = '';
                    this.suggestions = [];

                    if (hadFilter) {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('search');
                        url.searchParams.delete('page');
                        window.location.href = url.toString();
                    }
                }
            };
        }
    </script>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Content Table / Cards -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        {{-- Desktop Table --}}
        <div class="hidden lg:block">
            <table class="min-w-full text-sm text-left text-gray-700 bg-white">
                <thead class="bg-indigo-600 text-white uppercase tracking-wider text-xs font-bold">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Inquiry</th>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Submitted</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($submissions as $submission)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold border border-blue-100 shrink-0">
                                        {{ strtoupper(substr($submission->first_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $submission->first_name }} {{ $submission->last_name }}</div>
                                        <div class="text-xs text-gray-500 font-medium">{{ $submission->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 capitalize font-semibold text-indigo-600 text-xs">
                                <span class="bg-indigo-50 px-2 py-1 rounded-md">{{ str_replace('_', ' ', $submission->inquiry_type) }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-medium">
                                {{ Str::limit($submission->subject, 45) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'new' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'in_progress' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'resolved' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    ];
                                    $dotColors = [
                                        'new' => 'bg-blue-500',
                                        'in_progress' => 'bg-amber-500',
                                        'resolved' => 'bg-emerald-500',
                                    ];
                                    $badgeStyle = $statusColors[$submission->status] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                    $dotStyle = $dotColors[$submission->status] ?? 'bg-gray-400';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold border uppercase tracking-tight {{ $badgeStyle }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-2 {{ $submission->status === 'new' ? 'animate-pulse' : '' }} {{ $dotStyle }}"></span>
                                    {{ str_replace('_', ' ', $submission->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 font-medium text-xs">
                                {{ $submission->created_at->format('d M Y') }}<br>
                                <span class="text-[10px]">{{ $submission->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.contact-submissions.view',$submission->id) }}"
                                        class="p-2 bg-gray-50 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all border border-transparent hover:border-blue-100 shadow-sm" title="View Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.contact-submissions.destroy', $submission->id) }}" class="inline delete-submission-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-submission-btn p-2 bg-gray-50 text-rose-600 rounded-xl hover:bg-rose-50 transition-all border border-transparent hover:border-rose-100 shadow-sm" title="Delete">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg> 
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 italic font-medium">No contact submissions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List --}}
        <div class="block lg:hidden divide-y divide-gray-100">
            @forelse ($submissions as $submission)
                <div class="p-6 space-y-4 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold border border-blue-100 shrink-0">
                                {{ strtoupper(substr($submission->first_name, 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900 leading-tight">{{ $submission->first_name }} {{ $submission->last_name }}</h3>
                                <p class="text-xs text-gray-500 font-medium">{{ $submission->email }}</p>
                            </div>
                        </div>
                        @php
                            $statusColors = [
                                'new' => 'bg-blue-50 text-blue-600 border-blue-100',
                                'in_progress' => 'bg-amber-50 text-amber-600 border-amber-100',
                                'resolved' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            ];
                            $badgeStyle = $statusColors[$submission->status] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border uppercase tracking-tight {{ $badgeStyle }}">
                            {{ str_replace('_', ' ', $submission->status) }}
                        </span>
                    </div>

                    <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100 space-y-3">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Inquiry Type</p>
                            <span class="text-xs font-bold text-indigo-600 capitalize bg-indigo-50 px-2 py-0.5 rounded-md">{{ str_replace('_', ' ', $submission->inquiry_type) }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Subject</p>
                            <p class="text-sm font-bold text-gray-800">{{ Str::limit($submission->subject, 60) }}</p>
                        </div>
                        <div class="pt-2 border-t border-gray-100 flex justify-between items-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Submitted</p>
                            <p class="text-[11px] font-bold text-gray-600">{{ $submission->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <a href="{{ route('admin.contact-submissions.view',$submission->id) }}"
                            class="flex items-center gap-2 px-6 py-2.5 bg-blue-50 text-blue-600 rounded-xl font-bold text-xs active:scale-95 transition-all border border-blue-100 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View Details
                        </a>
                        <form method="POST" action="{{ route('admin.contact-submissions.destroy', $submission->id) }}" class="inline delete-submission-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="delete-submission-btn p-2.5 bg-rose-50 text-rose-600 rounded-xl active:scale-95 transition-all border border-rose-100 shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg> 
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-gray-500 font-medium italic">No contact submissions found.</div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8 px-4 sm:px-0">
        {{ $submissions->links() }}
    </div>
</div>

    <!-- Delete Modal -->
    <style>
        @keyframes bounce-modal {
            0%, 100% { transform: translateY(0); }
            25% { transform: translateY(-10px); }
            50% { transform: translateY(5px); }
            75% { transform: translateY(-5px); }
        }
        .animate-bounce-modal {
            animation: bounce-modal 0.5s ease;
        }
    </style>
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div id="deleteModalContent" class="bg-white p-6 rounded-xl shadow-lg max-w-md text-center animate-bounce-modal border-t-4 border-red-600 mx-4">
            <div class="flex justify-center mb-4">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a1 1 0 00.86 1.5h18.64a1 1 0 00.86-1.5L13.71 3.86a1 1 0 00-1.72 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-red-700 mb-2">Are you sure?</h3>
            <p class="text-sm text-red-600">This will permanently delete the selected contact submission. This action cannot be undone.</p>
            <div class="flex justify-center gap-4 mt-6">
                <button id="confirmDeleteBtn" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg font-semibold transition-colors">
                    Yes, Delete
                </button>
                <button id="cancelDeleteBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2.5 rounded-lg font-semibold transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let formToSubmit = null;
            const modal = document.getElementById('deleteModal');
            
            // Show modal on delete click
            document.querySelectorAll('.delete-submission-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    formToSubmit = this.closest('form');
                    modal.classList.remove('hidden');
                });
            });

            // Confirm delete
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (formToSubmit) formToSubmit.submit();
            });

            // Cancel delete
            document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
                modal.classList.add('hidden');
                formToSubmit = null;
            });
            
            // Close when clicking outside modal
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    formToSubmit = null;
                }
            });
        });
    </script>
@endsection
