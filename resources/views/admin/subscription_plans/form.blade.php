<div class="max-w-2xl mx-auto w-full bg-white p-10 rounded-2xl shadow-md border border-gray-200">
    <h2 class="text-2xl font-bold text-gray-900 text-center mb-10">
        {{ isset($plan) ? 'Edit Subscription Plan' : 'Create a New Subscription Plan' }}
    </h2>

    {{-- Plan Name --}}
    <div class="mb-6">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Plan Name</label>
        <input 
            type="text" 
            id="name" 
            name="name" 
            value="{{ old('name', $plan->name ?? '') }}" 
            required 
            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600"
        />
    </div>

    {{-- Description --}}
    <div class="mb-6">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
        <textarea 
            id="description" 
            name="description" 
            rows="4" 
            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 resize-none"
        >{{ old('description', $plan->description ?? '') }}</textarea>
    </div>

    {{-- Price --}}
    <div class="mb-6">
        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (GBP)</label>
        <input 
            type="number" 
            id="price" 
            name="price"
            step="any"
            min="0" 
            value="{{ old('price', $plan->price ?? '') }}" 
            required 
            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600"
        />
    </div>

    {{-- Duration --}}
    <div class="mb-6">
        <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">Duration (days)</label>
        <input 
            type="number" 
            id="duration_days" 
            name="duration_days" 
            value="{{ old('duration_days', $plan->duration_days ?? '30') }}" 
            required 
            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600"
        />
    </div>

    {{-- Card Limit --}}
    <div class="mb-6">
        <label for="card_limit" class="block text-sm font-medium text-gray-700 mb-2">NFC Card Limit</label>
        <input 
            type="number" 
            id="card_limit" 
            name="card_limit" 
            value="{{ old('card_limit', $plan->card_limit ?? '1') }}" 
            required 
            min="1"
            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600"
        />
    </div>

    {{-- Review Limit --}}
    <div class="mb-8">
        <label for="review_limit" class="block text-sm font-medium text-gray-700 mb-2">Monthly Review Limit (-1 for unlimited)</label>
        <input 
            type="number" 
            id="review_limit" 
            name="review_limit" 
            value="{{ old('review_limit', $plan->review_limit ?? '50') }}" 
            required 
            min="-1"
            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600"
        />
    </div>

    {{-- AI Review Replies --}}
    <div class="mb-8">
        <label class="flex items-start gap-3 cursor-pointer select-none rounded-lg border border-gray-200 bg-gray-50/60 p-4 hover:border-blue-300 transition">
            <input type="checkbox" name="has_ai_replies" value="1"
                   {{ old('has_ai_replies', $plan->has_ai_replies ?? false) ? 'checked' : '' }}
                   class="mt-0.5 w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
            <span>
                <span class="block text-sm font-semibold text-gray-800">Enable AI Review Replies</span>
                <span class="block text-xs text-gray-500">Subscribers on this plan can generate AI-written responses to their reviews (Google Gemini).</span>
            </span>
        </label>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.subscription-plans.index') }}"
           class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
            Cancel
        </a>

        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            {{ isset($plan) ? 'Update Plan' : 'Save Plan' }}
        </button>
    </div>
</div>


<script src="https://cdn.ckeditor.com/4.16.2/standard-all/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        try {
            console.log('Initializing CKEditor 4.16.2...');

            const textareaEl = document.getElementById('description');

            const config = {
                height: 400,
                extraPlugins: 'justify,tab,font,colorbutton,colordialog,link',
                toolbar: [
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
                    { name: 'alignment', items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                    { name: 'lineheight', items: ['LineHeight'] }, 
                    { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
                    { name: 'colors', items: ['TextColor', 'BGColor'] },
                    { name: 'links', items: ['Link', 'Unlink'] },
                    { name: 'insert', items: ['Image', 'Table', 'HorizontalRule'] },
                    { name: 'editing', items: ['Scayt'] },
                    { name: 'tools', items: ['Maximize', 'ShowBlocks'] },
                    { name: 'document', items: ['Source'] }
                ],
                enterMode: CKEDITOR.ENTER_P, 
                shiftEnterMode: CKEDITOR.ENTER_BR, 
                fontSize_sizes: '10/10px;12/12px;14/14px;16/16px;18/18px;20/20px;24/24px;28/28px;32/32px;36/36px;40/40px;46/46px;',
                line_height: "1;1.2;1.5;2;2.5;3", 
                tabSpaces: 4, 
                removeButtons: '', 
                removePlugins: 'elementspath', 
                resize_enabled: true,
                notification: {
                    duration: 0
                }
            };

            if (textareaEl) CKEDITOR.replace('description', config);
            // if (textareaEn) CKEDITOR.replace('text_en', config);

            console.log('CKEditor initialization complete');
        } catch (err) {
            console.error('Error initializing CKEditor:', err);
        }
    });
</script>
<style>
    /* Hide the CKEditor security warning box */
    .cke_notification_warning {
        display: none !important;
    }
</style>
