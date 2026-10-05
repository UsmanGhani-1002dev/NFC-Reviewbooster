<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'stripe_key' => 'nullable|string',
            'stripe_secret' => 'nullable|string',
            'google_places_api_key' => 'nullable|string',
            'wholesaler_discount_percent' => 'nullable|numeric|min:0|max:100',
            'retailer_discount_percent' => 'nullable|numeric|min:0|max:100',
            'corporate_discount_percent' => 'nullable|numeric|min:0|max:100',
            'delivery_fee_standard' => 'nullable|numeric|min:0',
            'delivery_fee_express' => 'nullable|numeric|min:0',
            'free_delivery_threshold' => 'nullable|numeric|min:0',
            // AI chatbot (Google Gemini)
            'chatbot_enabled' => 'nullable|boolean',
            'gemini_api_key' => 'nullable|string|max:255',
            'chatbot_model' => 'nullable|string|max:100',
            'chatbot_greeting' => 'nullable|string|max:300',
            'chatbot_extra_context' => 'nullable|string|max:4000',
        ]);

        // Checkbox sends nothing when off — normalise to 0 so it can be disabled.
        $data['chatbot_enabled'] = $request->boolean('chatbot_enabled') ? 1 : 0;

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}
