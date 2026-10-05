@php
    $greeting = \App\Models\Setting::get('chatbot_greeting')
        ?: "Hi! I'm Tappy, your Tap Review Cards assistant. Ask me about our review cards, delivery, custom logos or how it all works.";
@endphp

<div x-data="chatWidget()" x-init="init()" class="font-sans">
    {{-- Launcher bubble --}}
    <button type="button" @click="toggle()" x-show="!open"
            class="fixed bottom-6 right-6 z-[60] w-14 h-14 rounded-full bg-[#00A0FF] text-white shadow-xl shadow-blue-500/30 flex items-center justify-center hover:bg-[#0089db] hover:scale-105 transition-all"
            aria-label="Open chat">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.9 9.9 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <span class="absolute -top-0.5 -right-0.5 w-3.5 h-3.5 bg-green-400 border-2 border-white rounded-full"></span>
    </button>

    {{-- Chat panel --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         class="fixed z-[60] bottom-0 right-0 sm:bottom-6 sm:right-6 w-full sm:w-[380px] h-[85vh] sm:h-[560px] max-h-[90vh] bg-white sm:rounded-2xl shadow-2xl border border-gray-200 flex flex-col overflow-hidden"
         style="display:none;">

        {{-- Header --}}
        <div class="flex items-center gap-3 px-4 py-3 bg-[#142D63] text-white shrink-0">
            <div class="w-9 h-9 rounded-full bg-white/15 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.9 9.9 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold leading-tight">Tap Review Cards</p>
                <p class="text-[11px] text-white/70 flex items-center gap-1"><span class="w-2 h-2 bg-green-400 rounded-full inline-block"></span> Online — typically replies instantly</p>
            </div>
            <button type="button" @click="reset()" title="Clear chat" class="p-1.5 rounded-lg hover:bg-white/10 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M10 11v6M14 11v6M5 7l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/></svg>
            </button>
            <button type="button" @click="toggle()" title="Close" class="p-1.5 rounded-lg hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Messages --}}
        <div x-ref="scroll" class="flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-gray-50">
            <template x-for="(m, i) in messages" :key="i">
                <div :class="m.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="m.role === 'user'
                            ? 'bg-[#00A0FF] text-white rounded-2xl rounded-br-md'
                            : 'bg-white text-gray-800 border border-gray-100 rounded-2xl rounded-bl-md'"
                         class="max-w-[82%] px-3.5 py-2.5 text-[13.5px] leading-relaxed shadow-sm"
                         style="white-space:pre-wrap; word-break:break-word;" x-text="m.text"></div>
                </div>
            </template>

            {{-- Typing indicator --}}
            <div x-show="loading" class="flex justify-start">
                <div class="bg-white border border-gray-100 rounded-2xl rounded-bl-md px-4 py-3 shadow-sm">
                    <span class="flex gap-1">
                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                    </span>
                </div>
            </div>

            {{-- Suggested questions (only before any user message) --}}
            <div x-show="!messages.some(m => m.role === 'user') && !loading" class="pt-1 space-y-2">
                <template x-for="s in suggestions" :key="s">
                    <button type="button" @click="ask(s)"
                            class="block w-full text-left text-[13px] text-[#142D63] bg-white border border-gray-200 hover:border-[#00A0FF] hover:bg-blue-50/50 rounded-xl px-3.5 py-2 transition-colors" x-text="s"></button>
                </template>
            </div>
        </div>

        {{-- Input --}}
        <div class="border-t border-gray-100 p-3 bg-white shrink-0">
            <form @submit.prevent="send()" class="flex items-end gap-2">
                <textarea x-ref="box" x-model="input" rows="1"
                          @keydown.enter.prevent="send()"
                          @input="$el.style.height='auto'; $el.style.height=Math.min($el.scrollHeight,96)+'px'"
                          placeholder="Type your message…" maxlength="1000"
                          class="flex-1 resize-none max-h-24 text-sm text-gray-800 placeholder-gray-400 border border-gray-200 rounded-xl px-3 py-2.5 focus:border-[#00A0FF] focus:ring-2 focus:ring-[#00A0FF]/10 outline-none"></textarea>
                <button type="submit" :disabled="loading || !input.trim()"
                        class="w-10 h-10 shrink-0 rounded-xl bg-[#00A0FF] text-white flex items-center justify-center hover:bg-[#0089db] disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5M5 12l7-7 7 7"/></svg>
                </button>
            </form>
            <p class="text-[10px] text-gray-400 text-center mt-1.5">AI assistant — may occasionally be inaccurate.</p>
        </div>
    </div>
</div>

<script>
    function chatWidget() {
        return {
            open: false,
            input: '',
            loading: false,
            messages: [],
            greeting: @json($greeting),
            fallback: "Sorry, something went wrong. Please email info@tapreviewcards.co.uk and we'll help.",
            endpoint: @json(route('chatbot.send')),
            suggestions: [
                'How do the review cards work?',
                'What are your delivery options?',
                'Do you offer custom logos?',
                'Can I get wholesale pricing?',
            ],
            init() {
                try {
                    const s = localStorage.getItem('rb_chat');
                    if (s) this.messages = JSON.parse(s) || [];
                } catch (e) { this.messages = []; }
                if (!Array.isArray(this.messages) || this.messages.length === 0) {
                    this.messages = [{ role: 'bot', text: this.greeting }];
                }
            },
            toggle() {
                this.open = !this.open;
                if (this.open) this.$nextTick(() => { this.scrollDown(); if (this.$refs.box) this.$refs.box.focus(); });
            },
            persist() {
                try { localStorage.setItem('rb_chat', JSON.stringify(this.messages.slice(-30))); } catch (e) {}
            },
            ask(q) { this.input = q; this.send(); },
            async send() {
                const text = (this.input || '').trim();
                if (!text || this.loading) return;
                this.messages.push({ role: 'user', text });
                this.input = '';
                if (this.$refs.box) this.$refs.box.style.height = 'auto';
                this.loading = true;
                this.persist();
                this.$nextTick(() => this.scrollDown());

                const history = this.messages.slice(0, -1)
                    .filter(m => m.text)
                    .slice(-10)
                    .map(m => ({ role: m.role === 'user' ? 'user' : 'model', text: m.text }));

                try {
                    const res = await fetch(this.endpoint, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ message: text, history }),
                    });
                    const data = await res.json().catch(() => ({}));
                    this.messages.push({ role: 'bot', text: (data && data.reply) ? data.reply : this.fallback });
                } catch (e) {
                    this.messages.push({ role: 'bot', text: this.fallback });
                } finally {
                    this.loading = false;
                    this.persist();
                    this.$nextTick(() => this.scrollDown());
                }
            },
            scrollDown() { const el = this.$refs.scroll; if (el) el.scrollTop = el.scrollHeight; },
            reset() { this.messages = [{ role: 'bot', text: this.greeting }]; this.persist(); this.$nextTick(() => this.scrollDown()); },
        };
    }
</script>
