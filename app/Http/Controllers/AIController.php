<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    public function generateResponse(Request $request)
    {
        $request->validate([
            'review_content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $user = auth()->user();

        // AI replies are enabled per-plan via the has_ai_replies flag.
        $hasAiReplies = $user->subscription
            && $user->subscription->plan
            && $user->subscription->plan->has_ai_replies
            && $user->subscription->ends_at > now();

        if (!$hasAiReplies) {
            return response()->json([
                'success' => false,
                'message' => 'AI review replies are not included in your current plan. Please upgrade to use this feature.'
            ], 403);
        }

        // One key for the whole app: the admin Settings value is primary, with
        // the .env value kept only as a fallback. Same for the model.
        $apiKey = trim((string) Setting::get('gemini_api_key', '')) ?: (string) config('services.google.gemini_api_key');
        $model = trim((string) Setting::get('chatbot_model', '')) ?: ((string) config('services.google.gemini_model') ?: 'gemini-2.5-flash');

        if (!$apiKey || $apiKey === 'your_gemini_api_key_here') {
            return response()->json([
                'success' => false, 
                'message' => 'AI API key not configured. Please contact support.'
            ], 500);
        }

        $reviewContent = $request->review_content;
        $rating = $request->rating;

        $prompt = "You are a professional business owner. Write a polite, concise response to this customer review.
        Customer Feedback: \"{$reviewContent}\"
        Rating: {$rating} Stars

        Guidelines:
        - If the rating is 4 or 5 stars, thank them warmly and invite them back.
        - If the rating is 1, 2, or 3 stars, apologize sincerely, show empathy, and encourage them to reach out directly to resolve the issue.
        - Keep it professional but friendly.
        - Maximum 2-3 sentences.
        - Do not include any placeholders like [Your Name] or [Business Name].";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $generatedText = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Could not generate response.';
                
                return response()->json([
                    'success' => true,
                    'generated_text' => trim($generatedText)
                ]);
            } else {
                Log::error('Gemini API Error: ' . $response->body());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate response from AI.'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('AI Response Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while generating the response.'
            ], 500);
        }
    }
}
