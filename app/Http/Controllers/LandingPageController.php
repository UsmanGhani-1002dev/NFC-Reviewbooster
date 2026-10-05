<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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
        // $products = \App\Models\Product::where(function($q) {
        //         $q->where('name', 'LIKE', '%key%')
        //           ->orWhere('name', 'LIKE', '%tag%');
        //     })
        //     ->where('is_active', 1)
        //     ->with('variants')
        //     ->get();

            $products = Product::where('is_active', true)->where(function($q) {
                $q->where('name', 'LIKE', '%key%')
                  ->orWhere('name', 'LIKE', '%tag%');
            })
            ->with(['variants' => function($q) {
                $q->where('name', 'NOT LIKE', '%1 Mo Free%');
            }])
            ->get();
            
        $primaryProduct = $products->first();

        // Map database variants to plan slots. We fetch ALL variants of the
        // primary product here (bypassing the "1 Mo Free" filter used for the
        // product grid above) so the annual plans are included in the pricing.
        $planVariants = [];
        if ($primaryProduct) {
            $allVariants = $primaryProduct->variants()->get();
            foreach ($allVariants as $v) {
                if (str_contains($v->name, '5 Keyrings') && str_contains($v->name, 'Monthly')) {
                    $planVariants['5_monthly'] = $v;
                } elseif (str_contains($v->name, '10 Keyrings') && str_contains($v->name, 'Monthly')) {
                    $planVariants['10_monthly'] = $v;
                } elseif (str_contains($v->name, '5 Keyrings') && str_contains($v->name, 'Annual')) {
                    $planVariants['5_annual'] = $v;
                } elseif (str_contains($v->name, '10 Keyrings') && str_contains($v->name, 'Annual')) {
                    $planVariants['10_annual'] = $v;
                } elseif (str_contains($v->name, 'No Subscription')) {
                    $planVariants['no_subscription'] = $v;
                } elseif (!str_contains($v->name, 'Plan') && !isset($planVariants['no_subscription'])) {
                    // First standalone (non-plan) keyring = the pay-as-you-go price.
                    $planVariants['no_subscription'] = $v;
                }
            }
        }

        $defaultVariant = $primaryProduct ? $primaryProduct->variants->first() : null;

        return view('landing.google-review-keychain', compact('products', 'primaryProduct', 'defaultVariant', 'planVariants'));
    }
}
