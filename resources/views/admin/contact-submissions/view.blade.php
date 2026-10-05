@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto my-12 bg-white p-8 rounded-lg shadow-md">

    @if(session('success'))
        <div class="mb-4 text-green-700 bg-green-100 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif
    <div class="mb-6 flex items-start justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800 min-w-0">
            {{ $contacts->first_name }} {{ $contacts->last_name }} ({{ $contacts->subject }})
        </h2>

        <div class="bg-gray-200 p-2 rounded-lg shrink-0 whitespace-nowrap">
            Submission ID: <span class="text-blue-600">{{ $contacts->id }}</span>
        </div>
    </div>

    {{-- Contact Status Update Form --}}
    <form method="POST" action="{{ route('admin.contact-submissions.update', $contacts->id) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="full_name"
                value="{{ old('full_name', trim(($contacts->first_name ?? '') . ' ' . ($contacts->last_name ?? '')) ?: 'N/A') }}"
                readonly
                class="mt-1 w-full border border-gray-300 bg-gray-100 rounded-lg p-3" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="text" name="email" value="{{ old('email', $contacts->email ?? 'N/A') }}" readonly
                class="mt-1 w-full border border-gray-300 bg-gray-100 rounded-lg p-3" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $contacts->phone ?? '-') }}" readonly
                class="mt-1 w-full border border-gray-300 bg-gray-100 rounded-lg p-3" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Business Name</label>
            <input type="text" name="legal_business_name" value="{{ old('business_name', $contacts->business_name ?? '-') }}" readonly
                class="mt-1 w-full border border-gray-300 bg-gray-100 rounded-lg p-3" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Inquiry Type</label>
            <input type="text" name="inquiry_type" value="{{ old('inquiry_type', $contacts->inquiry_type) }}" readonly
                class="mt-1 w-full border border-gray-300 bg-gray-100 rounded-lg p-3 capitalize" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Subject</label>
            <input type="text" name="subject" value="{{ old('subject', $contacts->subject) }}" readonly
                class="mt-1 w-full border border-gray-300 bg-gray-100 rounded-lg p-3 capitalize" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Message</label>
            <textarea name="message" readonly
                class="mt-1 w-full border border-gray-300 bg-gray-100 rounded-lg p-3 capitalize"
                rows="5">{{ old('message', $contacts->message) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">IP Address</label>
            <input type="text" name="ip_address" value="{{ old('ip_address', $contacts->ip_address ?? '-') }}" readonly
                class="mt-1 w-full border border-gray-300 bg-gray-100 rounded-lg p-3 capitalize" />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status"
                    class="mt-1 w-full border border-gray-300 bg-white rounded-lg p-3">
                <option value="new" {{ $contacts->status === 'new' ? 'selected' : '' }}>New</option>
                <option value="in_progress" {{ $contacts->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ $contacts->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Subscription Started</label>
            <input type="text"
                value="{{ optional($contacts->created_at)->format('d F Y') ?? 'N/A' }}"
                readonly
                class="mt-1 w-full border border-gray-300 bg-gray-100 rounded-lg p-3 text-gray-700" />
        </div>

        {{-- Update & Cancel --}}
        <div class="flex flex-row justify-end items-center mt-6 pt-4 gap-3">
            <a href="{{ route('admin.contact-submissions.index') }}"
                class="w-1/2 md:w-auto px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition text-center">
                Cancel
            </a>
            <button type="submit"
                class="w-1/2 md:w-auto px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition text-center">
                Update
            </button>
        </div>
    </form>

    {{-- Conversation / Replies --}}
    <div class="mt-10 pt-6 border-t border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4-.83L3 20l1.17-3.5A7.9 7.9 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            Replies
            @if($contacts->replies->count())
                <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">{{ $contacts->replies->count() }}</span>
            @endif
        </h3>

        @forelse($contacts->replies as $reply)
            <div class="mb-3 bg-blue-50 border border-blue-100 rounded-xl p-4">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold text-blue-800">{{ $reply->subject }}</span>
                    <span class="text-[11px] text-gray-400">{{ $reply->created_at->format('d M Y, h:i A') }}</span>
                </div>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $reply->message }}</p>
                <div class="text-[11px] text-gray-400 mt-2">
                    Sent to {{ $contacts->email }}
                    @if($reply->admin) · by {{ $reply->admin->name }} @endif
                </div>
            </div>
        @empty
            <p class="text-sm text-gray-400 italic mb-4">No replies yet. Write your first reply below.</p>
        @endforelse

        {{-- Reply form --}}
        <form method="POST" action="{{ route('admin.contact-submissions.reply', $contacts->id) }}" class="mt-5 space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Your reply</label>
                <div class="text-xs text-gray-400 mb-2">
                    Email subject will be: <span class="font-mono font-semibold text-gray-600">RE: {{ $contacts->subject }}</span>
                    &nbsp;·&nbsp; will be emailed to <span class="font-semibold text-gray-600">{{ $contacts->email }}</span>
                </div>
                @php
                    $greetingName = trim(($contacts->first_name ?? '') . ' ' . ($contacts->last_name ?? '')) ?: 'there';
                    $defaultReply = 'Hi ' . $greetingName . ",\n\n";
                @endphp
                <textarea name="message" rows="6" required
                    class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none"
                    placeholder="Type your reply to {{ $contacts->first_name }}…">{{ old('message', $defaultReply) }}</textarea>
                @error('message')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2.5 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Send Reply
                </button>
            </div>
        </form>
    </div>

    {{-- Delete Button (Separate Form) --}}
    <div class="mt-8 pt-6 border-t border-gray-200">
        <form method="POST" action="{{ route('admin.contact-submissions.destroy', $contacts->id) }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                onclick="return confirm('Are you sure you want to delete this business?')"
                class="px-5 w-full md:w-auto py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                Delete Contact
            </button>
        </form>
    </div>
</div>
@endsection
