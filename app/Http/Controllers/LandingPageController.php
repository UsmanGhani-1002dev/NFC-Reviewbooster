<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function cards()
    {
        $products = \App\Models\Product::where('name', 'LIKE', '%card%')
            ->where('is_active', 1)
            ->with('variants')
            ->get();
            
        return view('landing.google-review-cards', compact('products'));
    }

    public function stand()
    {
        $products = \App\Models\Product::where('name', 'LIKE', '%stand%')
            ->where('is_active', 1)
            ->with('variants')
            ->get();
            
        return view('landing.google-review-stand', compact('products'));
    }

    public function keychain()
    {
        $products = \App\Models\Product::where('name', 'LIKE', '%key%')
            ->where('is_active', 1)
            ->with('variants')
            ->get();
            
        return view('landing.google-review-keychain', compact('products'));
    }
}
