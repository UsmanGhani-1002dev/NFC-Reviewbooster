@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Business Directory</h1>
            <p class="text-gray-500 mt-2 font-medium">Manage and monitor your connected business locations</p>
        </div>
        <a href="{{ route('businesses.create') }}"
           class="inline-flex items-center justify-center px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-100 transition-all duration-300 transform hover:-translate-y-1">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Business
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-bold rounded-xl flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-indigo-600 text-white uppercase tracking-wider">
                        <th class="px-8 py-4 text-[12px]">#</th>
                        <th class="px-8 py-4 text-[12px]">Business Information</th>
                        <th class="px-8 py-4 text-[12px]">Status</th>
                        <th class="px-2 py-4 text-[12px]">NFC Cards</th>
                        <th class="px-8 py-4 text-[12px] text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($businesses as $business)
                        <tr class="group hover:bg-blue-50/30 transition-colors">
                            <td class="px-8 py-6 text-sm font-bold text-gray-400">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-base font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $business->business_name }}</span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-gray-500 font-medium">{{ $business->legal_business_name }}</span>
                                        <span class="text-[10px] text-gray-300">•</span>
                                        <span class="text-xs text-gray-400 font-medium">Joined {{ $business->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @if($business->status == 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-tight">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                                        Active
                                    </span>
                                @elseif($business->status == 'blocked')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-600 border border-rose-100 uppercase tracking-tight">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-2"></span>
                                        Blocked
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-gray-50 text-gray-600 border border-gray-100 uppercase tracking-tight">
                                        {{ $business->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-center">
                                <div class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-lg font-bold text-sm">
                                    {{ $business->cards_count }}
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('cards.index', ['business_id' => $business->id]) }}"
                                       class="p-2.5 bg-gray-50 text-gray-600 rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-transparent hover:border-emerald-100 shadow-sm group/btn"
                                       title="Manage Cards">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('businesses.edit', $business->id) }}"
                                       class="p-2.5 bg-gray-50 text-gray-600 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all border border-transparent hover:border-blue-100 shadow-sm"
                                       title="Edit Settings">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>

                                    <button type="button"
                                            onclick="confirmDelete({{ $business->id }}, {{ json_encode($business->business_name) }})"
                                            class="p-2.5 bg-gray-50 text-gray-600 rounded-xl hover:bg-rose-50 hover:text-rose-600 transition-all border border-transparent hover:border-rose-100 shadow-sm"
                                            title="Remove Business">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>

                                    <form id="delete-form-{{ $business->id }}" action="{{ route('businesses.destroy', $business->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300 mb-6">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0v-2a2 2 0 00-2-2H7a2 2 0 00-2 2v2m14 0V9a2 2 0 00-2-2H7a2 2 0 00-2 2v12" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-extrabold text-gray-900">No Businesses Registered</h3>
                                    <p class="text-gray-500 mt-2 max-w-xs mx-auto">Start your SaaS journey by connecting your first business location.</p>
                                    <a href="{{ route('businesses.create') }}" class="mt-8 px-8 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                                        Register Now
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($businesses as $business)
                <div class="p-5 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex flex-col space-y-2">
                            <span class="text-lg font-bold text-gray-900">{{ $business->business_name }}</span>
                            <span class="text-xs text-gray-500 font-medium">{{ $business->legal_business_name }}</span>
                            <span class="text-xs text-gray-500 font-medium">Joined {{ $business->created_at->format('d M Y') }}</span>
                        </div>
                        @if($business->status == 'active')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-tight">Active</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100 uppercase tracking-tight">{{ $business->status }}</span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between mt-6">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">NFC Cards:</span>
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-md font-bold text-xs">{{ $business->cards_count }}</span>
                        </div>
                        <div class="flex gap-2">
                             <a href="{{ route('cards.index', ['business_id' => $business->id]) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 shadow-sm"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg></a>
                             <a href="{{ route('businesses.edit', $business->id) }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-600 shadow-sm"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></a>
                             <button type="button" onclick="confirmDelete({{ $business->id }}, {{ json_encode($business->business_name) }})" class="p-2 bg-white border border-gray-200 rounded-lg text-rose-500 shadow-sm"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                             <form id="delete-form-{{ $business->id }}-mobile" action="{{ route('businesses.destroy', $business->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                             </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-gray-500 text-sm">No businesses found.</div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Confirm Deletion',
                html: `<p class="text-gray-700 text-sm">Are you sure you want to delete <strong class="text-red-600">${name}</strong>? This action cannot be undone.</p>`,
                icon: 'warning',
                showCancelButton: true,
                focusCancel: true,
                confirmButtonText: 'Yes, Delete it',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-xl shadow-xl',
                    confirmButton: 'bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 ml-2',
                    cancelButton: 'bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300'
                },
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    submitDeleteForm(id);
                }
            });
        } else {
            if (confirm('Are you sure you want to delete "' + name + '"?')) {
                submitDeleteForm(id);
            }
        }
    }

    function submitDeleteForm(id) {
        const form = document.getElementById(`delete-form-${id}`) || document.getElementById(`delete-form-${id}-mobile`);
        if (form) {
            form.submit();
        }
    }
</script>

@endsection
