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
                $q->where('stock', '>', 0)->orderBy('sort_order')
                ->where('name', 'NOT LIKE', '%Plan%')
                ->where('name', 'NOT LIKE', '%Monthly%')
                ->where('name', 'NOT LIKE', '%Annual%');
            }])
            ->get();

        return view('home', compact('cards', 'products'));
    }

     public function aboutUs()
    {
        return view('aboutus');
    }

    public function howitswork()
    {
        $cards = \App\Models\SubscriptionPlan::all();
        return view('howitswork', compact('cards'));
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
