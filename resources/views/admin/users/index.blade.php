@extends('layouts.app') <!-- Or your admin layout -->


@section('full_content')
    <style>
        @keyframes bounce-modal {


            0%,
            100% {
                transform: translateY(0);
            }


            25% {
                transform: translateY(-10px);
            }


            50% {
                transform: translateY(5px);
            }


            75% {
                transform: translateY(-5px);
            }
        }


        .animate-bounce-modal {
            animation: bounce-modal 0.5s ease;
        }
    </style>


    <div class="bg-white rounded-2xl shadow-xl p-4 sm:p-8 border border-gray-100">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-6">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">All Users</h2>
            
            <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-4 items-stretch sm:items-center">
                <!-- Search Bar -->
              <form method="GET" action="{{ route('admin.users.index') }}" class="w-full sm:w-80">
                    <label for="search" class="sr-only">Search subscriptions</label>
                    <div class="relative text-gray-400 focus-within:text-black">
                    <input
                        id="search"
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search user, email, role..."
                        class="block w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 focus:outline-none transition-all"
                    />
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1111 3a7.5 7.5 0 015.65 13.65z"></path>
                        </svg>
                    </div>
                    </div>
                </form>

                <!-- Add New Button -->
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New
                </a>
            </div>
        </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif


        <form id="user-table-form" class="rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            {{-- Desktop Table --}}
            <div class="hidden lg:block">
                <table class="w-full table-auto">
                    <thead class="bg-indigo-600 text-white text-sm uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4"><input type="checkbox" id="select-all"></th>
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Partner Tier</th>
                            <th class="px-6 py-4">Plan</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Cards</th>
                            <th class="px-6 py-4">Expire_at</th>
                            <th class="px-6 py-4">Subscription</th>
                            <th class="px-6 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="text-center border-t text-gray-700 hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-2">
                                    <input type="checkbox" name="ids[]" value="{{ $user->id }}" class="user-checkbox rounded text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-400 font-mono">{{ $user->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold border border-blue-100 shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-bold text-gray-900 truncate text-left">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}"
                                        class="inline toggle-status-form">
                                        @csrf
                                        <button type="submit" class="focus:outline-none">
                                            <div class="relative inline-block w-12 h-6 rounded-full transition-colors duration-200 ease-in-out cursor-pointer
                                                    {{ $user->is_active ? 'bg-gradient-to-r from-yellow-400 to-orange-500' : 'bg-gray-300' }}">
                                                <div class="absolute inset-y-0 right-0 flex items-center justify-center w-6 h-6 bg-white rounded-full transition-transform duration-200 ease-in-out transform
                                                        {{ $user->is_active ? 'translate-x-0' : 'translate-x-[-100%]' }}">
                                                    <div class="w-4 h-4 rounded-full {{ $user->is_active ? 'bg-orange-500' : 'bg-gray-400' }}"></div>
                                                </div>
                                            </div>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $user->partner_status_badge }}">
                                            {{ $user->partner_type_label }}
                                            @if(in_array($user->partner_type, ['wholesaler', 'retailer', 'corporate']))
                                                ({{ ucfirst($user->partner_status ?? 'pending') }})
                                            @endif
                                        </span>
                                        
                                        @if(in_array($user->partner_type, ['wholesaler', 'retailer', 'corporate']))
                                            <div class="flex items-center gap-1 mt-1">
                                                @if($user->partner_status !== 'approved')
                                                    <form method="POST" action="{{ route('admin.users.partner-status', $user) }}" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="partner_status" value="approved">
                                                        <button type="submit" class="px-2 py-0.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded shadow-sm">
                                                            Approve
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($user->partner_status !== 'rejected')
                                                    <form method="POST" action="{{ route('admin.users.partner-status', $user) }}" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="partner_status" value="rejected">
                                                        <button type="submit" class="px-2 py-0.5 bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-bold rounded shadow-sm">
                                                            Reject
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="capitalize font-bold text-blue-600">
                                    {{ $user->subscription && $user->subscription->plan ? $user->subscription->plan->name : 'No Plan' }}
                                </td>
                                <td class="px-4 py-2 capitalize">
                                    {{ $user->role === 'bussiness_owner' ? 'Business' : ucfirst($user->role) }}
                                </td>

                                <td class="px-4 py-2">
                                    {{ $user->cards_count }}
                                </td>

                               <td class="px-4 py-2 text-gray-700">
                                    @if ($user->subscription && $user->subscription->ends_at)
                                        {{ \Carbon\Carbon::parse($user->subscription->ends_at)->format('d M Y') }}
                                    @else
                                        <span class="text-gray-400 italic">No expiry</span>
                                    @endif
                                </td>

                                 <td class="px-4 py-2 text-gray-700">
                                    @if ($user->subscription)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border
                                            {{ $user->subscription->status === 'active' ? 'bg-green-100 text-green-700 border-green-300' :
                                            ($user->subscription->status === 'expired' ? 'bg-red-100 text-red-700 border-red-300' :
                                            'bg-yellow-100 text-yellow-700 border-yellow-300') }}">

                                            <span class="w-1.5 h-1.5 rounded-full mr-2 animate-pulse
                                                {{ $user->subscription->status === 'active' ? 'bg-green-500' :
                                                ($user->subscription->status === 'expired' ? 'bg-red-500' :
                                                'bg-yellow-500') }}">
                                            </span>

                                            {{ ucfirst($user->subscription->status) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic">No subscription</span>
                                    @endif
                                </td>


                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center space-x-3">
                                        <!-- Edit Image -->
                                        <a href="{{ route('admin.users.edit', $user->id) }}" title="Edit"
                                         class="p-2.5 bg-gray-50 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all border border-transparent hover:border-blue-100 shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <!-- Delete Image -->
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline-block delete-user-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" title="Delete" class="delete-user-btn p-2.5 bg-gray-50 text-rose-600 rounded-xl hover:bg-rose-50 hover:text-rose-600 transition-all border border-transparent hover:border-rose-100 shadow-sm">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card List --}}
            <div class="block lg:hidden divide-y divide-gray-100">
                @foreach ($users as $user)
                    <div class="p-6 space-y-4 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="ids[]" value="{{ $user->id }}" class="user-checkbox mt-1.5">
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 leading-tight">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-tight bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $user->role === 'bussiness_owner' ? 'Business' : ucfirst($user->role) }}
                                </span>
                                <div class="mt-2 text-xs font-bold text-gray-400 uppercase tracking-widest">ID #{{ $user->id }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 bg-gray-50/50 rounded-2xl p-4 border border-gray-100">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</p>
                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="toggle-status-form">
                                    @csrf
                                    <button type="submit" class="focus:outline-none">
                                        <div class="relative inline-block w-10 h-5 rounded-full transition-colors duration-200 ease-in-out cursor-pointer
                                                {{ $user->is_active ? 'bg-gradient-to-r from-yellow-400 to-orange-500' : 'bg-gray-300' }}">
                                            <div class="absolute inset-y-0 right-0 flex items-center justify-center w-5 h-5 bg-white rounded-full transition-transform duration-200 ease-in-out transform
                                                    {{ $user->is_active ? 'translate-x-0' : 'translate-x-[-100%]' }}">
                                                <div class="w-3 h-3 rounded-full {{ $user->is_active ? 'bg-orange-500' : 'bg-gray-400' }}"></div>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Plan</p>
                                <p class="text-sm font-bold text-blue-600 capitalize">
                                    {{ $user->subscription && $user->subscription->plan ? $user->subscription->plan->name : 'No Plan' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Subscription</p>
                                @if ($user->subscription)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[12px] font-bold border
                                        {{ $user->subscription->status === 'active' ? 'bg-green-50 text-green-700 border-green-200' :
                                        ($user->subscription->status === 'expired' ? 'bg-red-50 text-red-700 border-red-200' :
                                        'bg-yellow-50 text-yellow-700 border-yellow-200') }}">
                                        {{ ucfirst($user->subscription->status) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">None</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Expires On</p>
                                <p class="text-xs font-medium text-gray-600">
                                    @if ($user->subscription && $user->subscription->ends_at)
                                        {{ \Carbon\Carbon::parse($user->subscription->ends_at)->format('d M Y') }}
                                    @else
                                        <span class="italic text-gray-400">N/A</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                             <div class="flex items-center gap-3">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Cards:</span>
                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-sm">{{ $user->cards_count }}</span>
                             </div>
                             <div class="flex gap-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="p-3 bg-blue-50 text-blue-600 rounded-xl transition-all active:scale-95 shadow-sm border border-blue-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline delete-user-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-user-btn p-3 bg-rose-50 text-rose-600 rounded-xl transition-all active:scale-95 shadow-sm border border-rose-100">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                             </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.users.bulk-delete') }}" id="delete-form" class="mt-4">
        @csrf
        <input type="hidden" name="user_ids" id="user_ids">
        <button type="button" id="bulkDeleteBtn" class="px-8 py-3 bg-red-600 text-white font-bold rounded-2xl shadow-2xl hover:bg-red-700 transition-all active:scale-95 border-2 border-white hidden flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Delete Selected
        </button>
    </form>


    <!-- ✅ Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div id="deleteModalContent"
            class="bg-white p-6 rounded-xl shadow-lg max-w-md text-center animate-bounce-modal border-t-4 border-red-600">


            <div class="flex justify-center mb-4">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a1 1 0 00.86 1.5h18.64a1 1 0 00.86-1.5L13.71 3.86a1 1 0 00-1.72 0z" />
                </svg>
            </div>


            <h3 class="text-xl font-bold text-red-700 mb-2" id="deleteModalMessage">Are you sure?</h3>
            <p class="text-sm text-red-600">This action is permanent and cannot be undone.</p>


            <div class="flex justify-center gap-4 mt-6">
                <button id="confirmDeleteBtn"
                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-md font-semibold">
                    Yes, Delete
                </button>
                <button id="cancelDeleteBtn"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2 rounded-md font-semibold">
                    Cancel
                </button>
            </div>
        </div>
    </div>


    <!-- ✅ JavaScript -->
    <script>
        // Select All Checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = this.checked);
        });


        // Store form to be submitted
        let formToSubmit = null;


        // Single delete
        document.querySelectorAll('.delete-user-btn').forEach(button => {
            button.addEventListener('click', function() {
                formToSubmit = this.closest('form');
                document.getElementById('deleteModalMessage').textContent =
                    "Are you sure you want to delete this user?";
                document.getElementById('deleteModal').classList.remove('hidden');
            });
        });


        // Bulk delete
        document.getElementById('bulkDeleteBtn').addEventListener('click', function() {
            let selected = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);


            if (selected.length === 0) {
                alert("Please select at least one user to delete.");
                return;
            }


            document.getElementById('user_ids').value = selected.join(',');
            formToSubmit = document.getElementById('delete-form');
            document.getElementById('deleteModalMessage').textContent =
                "Are you sure you want to delete the selected users?";
            document.getElementById('deleteModal').classList.remove('hidden');
        });


        // Confirm delete
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (formToSubmit) formToSubmit.submit();
        });


        // Cancel delete
        document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
            formToSubmit = null;
        });

        function toggleBulkDeleteButton() {
            const anyChecked = document.querySelectorAll('.user-checkbox:checked').length > 0;
            const button = document.getElementById('bulkDeleteBtn');
            if (anyChecked) {
                button.classList.remove('hidden');
            } else {
                button.classList.add('hidden');
            }
        }


        // Call on individual checkbox change
        document.querySelectorAll('.user-checkbox').forEach(cb => {
            cb.addEventListener('change', toggleBulkDeleteButton);
        });


        // Also update on "select all" change
        document.getElementById('select-all').addEventListener('change', function() {
            document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = this.checked);
            toggleBulkDeleteButton();
        });

    </script>

    @push('scripts')
<script src="{{ asset('js/toggle-status.js') }}"></script>
@endpush
@endsection


