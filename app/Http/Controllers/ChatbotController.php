<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Public customer-support chatbot backed by Google Gemini (free tier).
     * The API key stays server-side; the browser only ever talks to this route.
     */
    public function send(Request $request)
    {
        if (!(bool) Setting::get('chatbot_enabled', false)) {
            return response()->json(['reply' => 'The assistant is currently unavailable. Please contact us at info@tapreviewcards.co.uk.'], 200);
        }

        // Settings value is primary; fall back to the .env key so a single
        // key powers both the chatbot and the AI review assistant.
        $apiKey = trim((string) Setting::get('gemini_api_key', '')) ?: trim((string) config('services.google.gemini_api_key', ''));
        if ($apiKey === '') {
            return response()->json(['reply' => 'The assistant is not configured yet. Please email info@tapreviewcards.co.uk and we will be glad to help.'], 200);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array|max:20',
            'history.*.role' => 'nullable|string',
            'history.*.text' => 'nullable|string',
        ]);

        $model = trim((string) Setting::get('chatbot_model', '')) ?: 'gemini-2.5-flash';

        // ---- Build the grounded system prompt -----------------------------
        $systemPrompt = $this->buildSystemPrompt();

        // ---- Conversation history (last 10 turns, user/model only) --------
        $contents = [];
        foreach (array_slice($validated['history'] ?? [], -10) as $turn) {
            $role = ($turn['role'] ?? '') === 'model' ? 'model' : 'user';
            $text = trim((string) ($turn['text'] ?? ''));
            if ($text === '') {
                continue;
            }
            $contents[] = ['role' => $role, 'parts' => [['text' => mb_substr($text, 0, 1000)]]];
        }
        // Gemini requires the conversation to start with a user turn.
        while (!empty($contents) && $contents[0]['role'] !== 'user') {
            array_shift($contents);
        }
        $contents[] = ['role' => 'user', 'parts' => [['text' => $validated['message']]]];

        try {
            $response = Http::timeout(25)
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.4,
                        'maxOutputTokens' => 600,
                        'topP' => 0.9,
                    ],
                    'safetySettings' => [
                        ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_ONLY_HIGH'],
                        ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_ONLY_HIGH'],
                    ],
                ]);

            if (!$response->successful()) {
                Log::warning('Chatbot Gemini error', ['status' => $response->status(), 'body' => $response->body()]);
                return response()->json(['reply' => $this->fallback()], 200);
            }

            $reply = data_get($response->json(), 'candidates.0.content.parts.0.text');
            $reply = is_string($reply) ? trim($reply) : '';

            if ($reply === '') {
                return response()->json(['reply' => $this->fallback()], 200);
            }

            return response()->json(['reply' => $reply], 200);
        } catch (\Throwable $e) {
            Log::error('Chatbot request failed: ' . $e->getMessage());
            return response()->json(['reply' => $this->fallback()], 200);
        }
    }

    /**
     * Compose the assistant's knowledge from live settings + products so the
     * answers stay accurate as prices and products change.
     */
    private function buildSystemPrompt(): string
    {
        // Build a priced product list from live data so the bot can quote
        // accurate prices (per variant) and never say "I don't have the price".
        $products = Product::with('variants')
            ->when(\Illuminate\Support\Facades\Schema::hasColumn('products', 'is_active'), fn ($q) => $q->where('is_active', true))
            ->orderBy('sort_order')
            ->get()
            ->map(function ($p) {
                $variants = $p->variants
                    ->map(fn ($v) => $v->name . ' £' . number_format((float) $v->price, 2))
                    ->implode(', ');
                return '- ' . $p->name . ($variants !== '' ? ': ' . $variants : '');
            })
            ->implode("\n");

        $std = number_format((float) Setting::get('delivery_fee_standard', 3.90), 2);
        $exp = number_format((float) Setting::get('delivery_fee_express', 6.90), 2);
        $threshold = number_format((float) Setting::get('free_delivery_threshold', 25), 2);
        $extra = trim((string) Setting::get('chatbot_extra_context', ''));

        $prompt = <<<PROMPT
You are "Tappy", the friendly customer-support assistant for Tap Review Cards (tapreviewcards.co.uk), a UK company.

What we do:
Tap Review Cards sells NFC "tap" products that help businesses collect more Google reviews instantly. A customer taps the product with their phone (or scans the QR code) and is taken straight to the business's Google review page. No app is needed and it works on both iPhone and Android.

Products and pricing (GBP, each variant priced):
{$products}

Delivery (UK): Standard delivery £{$std} (around 48 hours) and Express delivery £{$exp} (around 24 hours). Standard delivery is free on orders over £{$threshold}.

Also good to know:
- A custom company logo / branding option is available on the products.
- Customers get a smart dashboard with a review gate, staff tracking and AI review responses.
- Wholesale / B2B partner pricing is available for resellers.
- Contact: info@tapreviewcards.co.uk or +44 7300 401004. There are Track Order and Shipping & Returns pages on the website.
PROMPT;

        if ($extra !== '') {
            $prompt .= "\n\nExtra information:\n" . $extra;
        }

        $prompt .= <<<RULES


How to reply:
- Be concise, warm and professional. Use British English and plain text with short paragraphs.
- Only help with Tap Review Cards, its products, ordering, delivery, how it works and general support. If asked something unrelated, politely steer back or suggest emailing support.
- You cannot access individual orders, accounts or payment details. For order-specific or account issues, point the customer to the Track Order page or to email info@tapreviewcards.co.uk.
- Never invent prices, delivery times or policies beyond what is stated above. If you are unsure, say so and point to the website or support.
- Do not use emojis.
RULES;

        return $prompt;
    }

    private function fallback(): string
    {
        return "Sorry, I'm having trouble answering right now. Please try again in a moment, or email us at info@tapreviewcards.co.uk and we'll be happy to help.";
    }
}
