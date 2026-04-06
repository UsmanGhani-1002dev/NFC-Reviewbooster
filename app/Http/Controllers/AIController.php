<?php

namespace App\Http\Controllers;

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

        // Check if user has Premium subscription
        $hasPremium = $user->subscription 
            && $user->subscription->plan 
            && stripos($user->subscription->plan->name, 'premium') !== false
            && $user->subscription->ends_at > now();

        if (!$hasPremium) {
            return response()->json([
                'success' => false, 
                'message' => 'This feature is only available for Premium Plan subscribers.'
            ], 403);
        }

        $apiKey = config('services.google.gemini_api_key');
        $model = config('services.google.gemini_model');
        
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
            ])->post("https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$apiKey}", [
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
