@extends('layouts.plain')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="min-h-screen flex items-center justify-center px-2 py-10 md:px-4">

        <div class="w-full max-w-lg bg-white rounded-3xl shadow border xl:p-0">
            <div class="px-4 py-10 space-y-4 md:space-y-6 sm:p-8">

                <div class="text-center mb-4">
                    <a href="{{ url('/') }}" class="flex items-center justify-center">
                        <x-application-logo class="block h-auto w-40 fill-current text-blue-500" />
                    </a>   

                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl mt-4">
                        Create your account
                    </h1>
                </div>


             <form method="POST" action="{{ route('register') }}" id="multiStepForm" class="space-y-4 md:space-y-6">
                @csrf

                <!-- Step 1 -->
                <div id="step-1" class="step space-y-6 bg-white">
                    <!-- Account Purpose / Type -->
                    <div>
                        <label for="partner_type" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Account Type') }} <span class="text-red-600">*</span>
                        </label>
                        <select id="partner_type" name="partner_type" onchange="togglePartnerRegistration(this.value)"
                            class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black px-4 py-2.5 text-sm font-semibold bg-gray-50/50">
                            <option value="standard">Standard Customer / Business Owner</option>
                            <option value="wholesaler">Apply as Wholesaler</option>
                            <option value="retailer">Apply as Retailer</option>
                            <option value="corporate">Apply as Corporate Account </option>
                        </select>
                    </div>

                    <!-- B2B Partner Information Box -->
                    <div id="partner-notice-box" class="hidden p-4 bg-blue-50 border border-blue-200 rounded-xl space-y-3">
                        <p class="text-xs text-blue-900 leading-relaxed font-medium">
                            ℹ️ <strong>B2B Partner Registration:</strong> As a Wholesaler/Retailer, software subscription plan payment is <strong>skipped</strong>. Your account will be created and submitted to Admin for approval.
                        </p>
                        <div>
                            <label for="vat_number" class="block text-xs font-bold text-blue-900 mb-1">VAT / Tax Identification Number (Optional)</label>
                            <input type="text" id="vat_number" name="vat_number" placeholder="GB123456789" class="w-full border border-purple-200 rounded-md px-3 py-2 text-xs">
                        </div>
                    </div>

                    <!-- Company Name -->
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Company Name') }} <span class="text-red-600">*</span>
                        </label>
                        <x-text-input id="company_name" name="company_name" type="text"
                            class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black px-4 py-2"
                            :value="old('company_name')" required autocomplete="organization" placeholder="Company name"/>
                        <x-input-error :messages="$errors->get('company_name')" class="mt-2 text-sm text-red-600" />
                        <div class="text-red-500 text-sm mt-1" data-field="company_name"></div>
                    </div>
                
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Full Name') }} <span class="text-red-600">*</span>
                        </label>
                        <x-text-input id="name" name="name" type="text"
                            class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black px-4 py-2"
                            :value="old('name')" required autofocus autocomplete="name" placeholder="Full name"/>
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
                        <div class="text-red-500 text-sm mt-1" data-field="name"></div>
                    </div>
                
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Email') }} <span class="text-red-600">*</span>
                        </label>
                        <x-text-input id="email" name="email" type="email"
                            class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black px-4 py-2"
                            :value="old('email')" required autocomplete="username" placeholder="Email address"/>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                        <div class="text-red-500 text-sm mt-1" data-field="email"></div>
                    </div>
                
                    <!-- Password -->
                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Password') }} <span class="text-red-600">*</span>
                        </label>
                        <x-text-input id="password" name="password" type="password"
                            class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black px-4 py-2 pr-10"
                            required autocomplete="new-password" placeholder="Password"/>
                        <button type="button" onclick="togglePassword('password', this)" 
                            class="absolute right-3 top-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                        <div class="text-red-500 text-sm mt-1" data-field="password"></div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="relative">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Confirm Password') }} <span class="text-red-600">*</span>
                        </label>
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                            class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black px-4 py-2 pr-10"
                            required autocomplete="new-password" placeholder="Confirm password"/>
                        <button type="button" onclick="togglePassword('password_confirmation', this)" 
                            class="absolute right-3 top-1/2 text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
                        <div class="text-red-500 text-sm mt-1" data-field="password_confirmation"></div>
                    </div>
                
                    <!-- Next Button -->
                    <div class="pt-4">
                        <button type="button" id="toStep2"
                            class="w-full py-2.5 px-4 bg-black text-white rounded-lg font-medium hover:bg-gray-800 transition focus:ring-4 focus:ring-gray-300">
                            Next Step
                        </button>
                    </div>
                </div>


                <!-- Step 2: Payment Plan Option -->
                <div id="step-2" class="step hidden flex flex-col h-full">
                    <div class="text-center mb-6 mt-2">
                        <h2 class="text-xl font-bold text-gray-900">Select a Payment Plan <span class="text-red-500">*</span></h2>
                        <p class="text-sm text-gray-500 mt-2">Choose the plan that's right for your business.</p>
                    </div>

                    <div class="space-y-4">
                        @foreach ($plans->take(2) as $plan)
                            <label class="block p-5 border border-gray-200 rounded-2xl shadow-sm cursor-pointer hover:border-[#0284C7] transition-all duration-300 focus-within:ring-2 focus-within:ring-[#0284C7] bg-white group hover:shadow-md">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                    <div class="flex items-start gap-3 flex-1">
                                        <div class="flex items-center h-6 mt-1">
                                            <input type="radio" name="payment_plan" value="{{ $plan->id }}" class="mt-0.5 accent-[#0284C7] w-5 h-5 shrink-0 cursor-pointer" required>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-lg font-bold text-gray-900 capitalize group-hover:text-[#0284C7] transition-colors">{{ $plan->name }}</div>
                                            <div class="text-sm text-gray-600 leading-relaxed mt-2 [&>ul]:space-y-2 [&>p]:mb-2 [&_li]:flex [&_li]:items-start [&_li]:gap-2">
                                                {!! $plan->description !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sm:text-right pl-8 sm:pl-0 shrink-0">
                                        <div class="flex items-baseline sm:justify-end gap-1 text-[#142D63]">
                                            <span class="text-3xl font-extrabold tracking-tight">€{{ $plan->price }}</span>
                                        </div>
                                        <div class="text-sm font-medium text-gray-500 mt-1">
                                            / {{ $plan->duration_days }} days
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                        <div class="block">
                            <label class="block p-5 border border-gray-200 rounded-2xl shadow-sm cursor-pointer hover:border-[#0284C7] transition-all duration-300 focus-within:ring-2 focus-within:ring-[#0284C7] bg-white group hover:shadow-md">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                    <div class="flex items-start gap-3 flex-1">
                                        <div class="flex items-center h-6 mt-1">
                                            <input type="radio" name="payment_plan" value="custom" class="mt-0.5 accent-[#0284C7] w-5 h-5 shrink-0 cursor-pointer" required>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-lg font-bold text-gray-900 capitalize group-hover:text-[#0284C7] transition-colors">Enterprise Plan</div>
                                            <div class="text-sm text-gray-600 leading-relaxed mt-2 [&>ul]:space-y-2 [&>p]:mb-2 [&_li]:flex [&_li]:items-start [&_li]:gap-2">
                                                <ul>
                                                    <li class="flex items-center">🔷 Unlimited Cards & Businesses</li>
                                                    <li class="flex items-center">🔷 Advanced Dashboard with Insights</li>
                                                    <li class="flex items-center">🔷 Team Leaderboard & Staff Tracking</li>
                                                    <li class="flex items-center">🔷 Dedicated Account Manager</li>
                                                    <li class="flex items-center">🔷 AI Response Generator</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sm:text-right pl-8 sm:pl-0 shrink-0">
                                        <div class="flex items-baseline sm:justify-end gap-1 text-[#142D63]">
                                            <span class="text-3xl font-extrabold tracking-tight">Custom</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8 pt-6 border-t border-gray-100">
                        <button type="button" id="backToStep1" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-200 transition">
                            Back
                        </button>

                        <button type="button" id="toStep3" class="px-6 py-2.5 text-sm font-medium text-white bg-black rounded-lg hover:bg-gray-800 transition focus:ring-4 focus:ring-gray-300">
                            Continue to Payment
                        </button>
                    </div>
                </div>

                <!-- Step 3: Payment Details -->
                <div id="step-3" class="step hidden flex flex-col h-full">
                    <div class="text-center mb-6 mt-2">
                        <h2 class="text-xl font-bold text-gray-900">Payment Details</h2>
                        <p class="text-sm text-gray-500 mt-2">Enter your card information to complete the registration.</p>
                    </div>

                    <div class="mt-2 text-center bg-[#E0F2FE] text-[#0369A1] rounded-lg p-3 mb-6 font-medium text-sm border border-[#BAE6FD] hidden" id="plan-summary">
                        You have selected the <strong><span id="summary-plan-name"></span></strong> plan.
                    </div>

                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 shadow-inner">
                        <h3 class="font-medium text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                            Card Information
                        </h3>
                        <div id="card-element" class="min-h-[150px] relative z-10"></div>
                        <div id="card-errors" class="text-sm text-red-500 mt-3 font-medium"></div>
                    </div>

                    <div class="flex justify-between mt-8 pt-6 border-t border-gray-100">
                        <button type="button" id="backToStep2" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-200 transition">
                            Back
                        </button>

                        <button type="submit" id="submit-button"
                            class="px-6 py-2.5 text-sm font-medium text-white bg-[#0284C7] rounded-lg hover:bg-[#0369A1] transition focus:ring-4 focus:ring-blue-300 flex items-center justify-center gap-2 shadow-sm">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.5 8V4.5a3.5 3.5 0 1 0-7 0V8M8 12v3M2 8h12a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1Z"/></svg>
                            Register & Pay
                        </button>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <a class="text-sm text-[#0284C7] hover:underline" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>
                </div>

                <!-- <div class="text-center">
                    <img src="images/rating-design.png" alt=""
                        draggable="false" class="mx-auto mt-4 w-[290px]">
                </div> -->
            </form>

            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
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

        document.addEventListener('DOMContentLoaded', () => {
            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');
            const step3 = document.getElementById('step-3');
            const toStep2Btn = document.getElementById('toStep2');
            const toStep3Btn = document.getElementById('toStep3');
            const backToStep1Btn = document.getElementById('backToStep1');
            const backToStep2Btn = document.getElementById('backToStep2');
            const form = document.getElementById('multiStepForm');
            const cardErrors = document.getElementById('card-errors');
            const submitButton = document.getElementById('submit-button');
    
            let stripe = Stripe("{{ config('services.stripe.key') }}");
            let elements = null;
            let paymentElement = null;
            let clientSecret = null;
    
            // Function to clear all error messages and reset input styles
            function clearErrors() {
                document.querySelectorAll('.text-red-500, .text-red-600').forEach(el => {
                    if (el.id !== 'card-errors') el.textContent = '';
                });
            
                document.querySelectorAll('input, select, textarea').forEach(el => {
                    el.classList.remove('border-red-500', 'focus:border-red-500');
                });
            
                cardErrors.textContent = '';
            }
            
            // Function to display validation errors
            function displayErrors(errors) {
                clearErrors();
            
                Object.keys(errors).forEach(field => {
                    const inputElement = document.querySelector(`input[name="${field}"], select[name="${field}"], textarea[name="${field}"]`);
                    const errorElement = document.querySelector(`[data-field="${field}"]`) ||
                                         inputElement?.parentNode?.querySelector('.text-red-500');
            
                    if (errorElement) {
                        errorElement.textContent = errors[field][0];
                    }
            
                    if (inputElement) {
                        inputElement.classList.add('border-red-500', 'focus:border-red-500');
                    }
                });
            }
    
            window.togglePartnerRegistration = function(val) {
                const noticeBox = document.getElementById('partner-notice-box');
                const btn = document.getElementById('toStep2');
                if (btn) {
                    if (val && val !== 'standard') {
                        if (noticeBox) noticeBox.classList.remove('hidden');
                        btn.textContent = 'Submit Partner Application';
                    } else {
                        if (noticeBox) noticeBox.classList.add('hidden');
                        btn.textContent = 'Next Step';
                    }
                }
            };

            const partnerTypeInput = document.getElementById('partner_type');
            if (partnerTypeInput) {
                togglePartnerRegistration(partnerTypeInput.value);
            }

            // Validate Step 1 with backend before proceeding to Step 2
            toStep2Btn.addEventListener('click', async () => {
                clearErrors();
                
                const formData = new FormData();
                formData.append('name', document.getElementById('name').value.trim());
                formData.append('email', document.getElementById('email').value.trim());
                formData.append('password', document.getElementById('password').value);
                formData.append('password_confirmation', document.getElementById('password_confirmation').value);
                formData.append('company_name', document.getElementById('company_name').value.trim());
                formData.append('partner_type', document.getElementById('partner_type').value);
                formData.append('_token', '{{ csrf_token() }}');
    
                try {
                    // Validate with backend first
                    const response = await fetch('{{ route("validate.step1") }}', {
                        method: 'POST',
                        body: formData
                    });
    
                    const data = await response.json();
    
                    if (!response.ok) {
                        if (data.errors) {
                            displayErrors(data.errors);
                        } else {
                            alert(data.message || 'Validation failed. Please check your inputs.');
                        }
                        return;
                    }
    
                    const partnerType = document.getElementById('partner_type').value;
                    if (partnerType && partnerType !== 'standard') {
                        toStep2Btn.disabled = true;
                        toStep2Btn.textContent = 'Submitting...';
                        form.submit();
                        return;
                    }

                    // If standard customer validation passes, move to step 2
                    step1.classList.add('hidden');
                    step2.classList.remove('hidden');
                    
                } catch (error) {
                    console.error('Validation error:', error);
                    alert('Something went wrong. Please try again.');
                }
            });
    
            backToStep1Btn.addEventListener('click', () => {
                step2.classList.add('hidden');
                step1.classList.remove('hidden');
                clearErrors();
                clientSecret = null; // Reset payment intent
            });

            toStep3Btn.addEventListener('click', () => {
                const selectedPlan = document.querySelector('input[name="payment_plan"]:checked');
                if (!selectedPlan) {
                    alert('Please select a payment plan to continue.');
                    return;
                }
                
                // If Custom Plan is selected, redirect to contact page
                if (selectedPlan.value === 'custom') {
                    const name = document.getElementById('name').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const company = document.getElementById('company_name').value.trim();
                    
                    // Redirect to contact page in a new tab with pre-filled details
                    const contactUrl = `{{ route('contact') }}?inquiry=enterprise&first_name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&business_name=${encodeURIComponent(company)}`;
                    window.open(contactUrl, '_blank');
                    return;
                }

                // Show summary text
                const planName = selectedPlan.closest('label').querySelector('.capitalize').textContent;
                document.getElementById('summary-plan-name').textContent = planName;
                document.getElementById('plan-summary').classList.remove('hidden');

                step2.classList.add('hidden');
                step3.classList.remove('hidden');
            });

            backToStep2Btn.addEventListener('click', () => {
                step3.classList.add('hidden');
                step2.classList.remove('hidden');
            });
    
            // When user selects payment plan, create payment intent
            const paymentPlanRadios = document.querySelectorAll('input[name="payment_plan"]');
            paymentPlanRadios.forEach(radio => {
                radio.addEventListener('change', async () => {
                    const plan = radio.value;

                    // Toggle button text for custom plan
                    if (plan === 'custom') {
                        toStep3Btn.textContent = 'Contact Us for Enterprise Plan';
                    } else {
                        toStep3Btn.textContent = 'Continue to Payment';
                    }

                    if (plan === 'custom') return; // Don't create Stripe intent for custom plan

                    const name = document.getElementById('name').value.trim();
                    const email = document.getElementById('email').value.trim();
    
                    try {
                        const response = await fetch('{{ route('create.intent') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                plan,
                                name,
                                email
                            })
                        });
    
                        const data = await response.json();
    
                        if (!response.ok) {
                            throw new Error(data.message || 'Failed to create payment intent.');
                        }
    
                        clientSecret = data.client_secret;
                        cardErrors.textContent = '';
                        
                        // Initialize Payment Element
                        const appearance = {
                            theme: 'stripe',
                            variables: {
                                colorPrimary: '#0284C7',
                                colorBackground: '#ffffff',
                                colorText: '#1f2937',
                                colorDanger: '#ef4444',
                                fontFamily: 'ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
                                spacingUnit: '4px',
                                borderRadius: '8px',
                            }
                        };
                        
                        if (paymentElement) {
                            paymentElement.destroy();
                        }
                        
                        elements = stripe.elements({ clientSecret, appearance });
                        paymentElement = elements.create('payment', {
                            layout: 'tabs',
                            defaultValues: {
                                billingDetails: {
                                    name: name,
                                    email: email
                                }
                            }
                        });
                        paymentElement.mount('#card-element');
                    } catch (error) {
                        clientSecret = null;
                        cardErrors.textContent = error.message;
                    }
                });
            });
            
            
    
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                clearErrors();
                submitButton.disabled = true;
                submitButton.textContent = 'Processing...';
    
                // Validate payment plan selection
                const selectedPlan = document.querySelector('input[name="payment_plan"]:checked');
                if (!selectedPlan) {
                    alert('Please select a payment plan.');
                    submitButton.disabled = false;
                    submitButton.textContent = 'Register & Pay';
                    return;
                }
    
                if (!clientSecret) {
                    cardErrors.textContent = 'Payment initialization failed. Please select a payment plan again.';
                    submitButton.disabled = false;
                    submitButton.textContent = 'Register & Pay';
                    return;
                }
    
                try {
                    const {error, paymentIntent} = await stripe.confirmPayment({
                        elements,
                        confirmParams: {
                            payment_method_data: {
                                billing_details: {
                                    name: document.getElementById('name').value,
                                    email: document.getElementById('email').value,
                                }
                            }
                        },
                        redirect: "if_required"
                    });
    
                    if (error) {
                        cardErrors.textContent = error.message;
                        submitButton.disabled = false;
                        submitButton.textContent = 'Register & Pay';
                        return;
                    }
    
                    // Payment successful, now submit form
                    let intentInput = document.querySelector('input[name="payment_intent_id"]');
                    if (!intentInput) {
                        intentInput = document.createElement('input');
                        intentInput.type = 'hidden';
                        intentInput.name = 'payment_intent_id';
                        form.appendChild(intentInput);
                    }
                    intentInput.value = paymentIntent.id;
    
                    // Submit the form to backend
                    form.submit();
                    
                } catch (error) {
                    console.error('Payment error:', error);
                    cardErrors.textContent = 'Payment processing failed. Please try again.';
                    submitButton.disabled = false;
                    submitButton.textContent = 'Register & Pay';
                }
            });
        });
    </script>
@endsection