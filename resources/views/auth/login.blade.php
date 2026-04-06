@extends('layouts.plain')

@section('content')
<div class="min-h-screen flex items-center justify-center md:px-4 px-2 py-8">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl py-10 md:px-8 px-4 text-center">
        
        {{-- Centered Logo --}}
        <div class="flex justify-center mb-4">
            <a href="{{ url('/') }}" class="flex items-center">
                <div class="block h-auto w-40 flex items-center justify-center text-white font-bold">
                    <x-application-logo class="block h-auto w-40 fill-current text-blue-500" />
                </div>
            </a>
        </div>

        <h2 class="text-xl font-semibold text-gray-900 mb-2">
            Log in your <span class="text-blue-600 font-bold">account</span>
        </h2>
        <p class="text-gray-800 mb-6">Welcome back !</p>

        <form method="POST" action="{{ route('login') }}" class="space-y-5 text-left">
            @csrf

            {{-- Email --}}
            <div>
                <x-input-label class="block text-sm font-medium text-gray-700 mb-1" for="email">
                {{ __('Email Address') }} <span class="text-red-600">*</span>
                </x-input-label>                
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none placeholder-gray-400"
                    placeholder="Email address">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-input-label class="block text-sm font-medium text-gray-700 mb-1" for="password">
                    {{ __('Password') }} <span class="text-red-600">*</span>
                </x-input-label>

                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none placeholder-gray-400 pr-10"
                        placeholder="Password">
                    <button type="button" onclick="togglePassword('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <script>
                function togglePassword(inputId, button) {
                    const input = document.getElementById(inputId);
                    const icon = button.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            </script>

            {{-- Forgot --}}
            <div class="flex justify-between items-center text-sm">
                <div>
                    <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-[#0284C7]">
                            <span class="ml-2 text-sm text-gray-500">Remember me</span>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">Forgot Your Password?</a>
                @endif
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-2 bg-black text-white rounded-lg font-semibold hover:bg-gray-800 transition">
                Login
            </button>
        </form>

        {{-- Google Review --}}
        <!-- <div class="mt-8">
            <img src="images/rating-design.png"
                 alt="Google Review"
                 draggable="false"
                 class="mx-auto w-[290px] mb-6">
        </div> -->

        {{-- Register link --}}
        <p class="text-sm text-gray-600 mt-6">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">Register</a>
        </p>
    </div>
</div>
@endsection