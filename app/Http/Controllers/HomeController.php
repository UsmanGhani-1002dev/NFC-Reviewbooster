<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $cards = \App\Models\SubscriptionPlan::all();
        $products = \App\Models\Product::where('is_active', true)
            ->with(['variants' => function($q) {
                $q->where('stock', '>', 0)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('home', compact('cards', 'products'));
    }

     public function aboutUs()
    {
        return view('aboutus');
    }

    public function howitswork()
    {
        return view('howitswork');
    }

    public function shippingReturns()
    {
        return view('shipping-returns');
    }

    public function privacypolicy(){
        return view('privacy-policy');
    }

    public function termsOfService()
    {
        return view('terms-of-service');
    }
}
