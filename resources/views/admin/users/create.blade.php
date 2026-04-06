@extends('layouts.app')

@section('content')
<div class="p-8 bg-white rounded-2xl shadow-lg max-w-2xl mx-auto mt-10">
    <div class="flex justify-between items-center">
         <h2 class="text-3xl font-bold mb-6 text-gray-800">Add New User</h2>
         <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-800 transition font-medium">
            &larr; Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

   <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
    @csrf

    {{-- Company Name --}}
    <div>
        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
        <input 
            type="text" 
            name="company_name" 
            id="company_name" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition"
            value="{{ old('company_name') }}"
        />
    </div>

    {{-- Name --}}
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
        <input 
            type="text" 
            name="name" 
            id="name" 
            required 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition"
            value="{{ old('name') }}"
        />
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
        <input 
            type="email" 
            name="email" 
            id="email" 
            required 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition"
            value="{{ old('email') }}"
        />
    </div>

    {{-- Password --}}
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
        <input 
            type="password" 
            name="password" 
            id="password" 
            required 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition"
        />
    </div>

    {{-- Role --}}
    <div>
        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
        <select 
            name="role" 
            id="role" 
            required 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition"
        >
            @foreach ($roles as $role)
                <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Subscription Plan --}}
    <div>
        <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-1">Initial Subscription Plan</label>
        <select 
            name="plan_id" 
            id="plan_id" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition"
        >
            <option value="">-- No Plan (Trial/In-active) --</option>
            @foreach ($plans as $plan)
                <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                    {{ $plan->name }} (${{ $plan->price }}) - {{ $plan->duration_days }} Days
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500 italic">Assigning a plan will immediately create an active subscription for this user.</p>
    </div>

    {{-- Submit / Cancel --}}
    <div class="flex items-center justify-between pt-6">
        <a href="{{ route('admin.users.index') }}"
            class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
            Cancel
        </a>
        
        
        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow transition">
            Create User
        </button>
        
    </div>
</form>

</div>
@endsection
