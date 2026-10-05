<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class KeyringPlanSeeder extends Seeder
{
    public function run(): void
    {
        // Find existing rating tag / keyring product or create one
        $product = Product::where('slug', 'rating-tag')
            ->orWhere('name', 'LIKE', '%key%')
            ->orWhere('name', 'LIKE', '%tag%')
            ->first();

        if (!$product) {
            $product = Product::create([
                'name' => 'Rating Tag',
                'slug' => 'rating-tag',
                'subtitle' => 'NFC Google Review Keychain Tag',
                'description' => 'Durable keychain-style NFC tag that works for portable review collection. Available in one-off purchases or monthly/annual team plans.',
                'badge_text' => 'Portable & Durable',
                'features' => [
                    'Works on iPhone & Android',
                    'No app required',
                    'Keychain design',
                    'Optional team dashboard',
                ],
                'is_active' => true,
                'sort_order' => 2,
            ]);
        }

        $variantsData = [
            [
                'name' => 'Generic Keyring (No Subscription)',
                'quantity' => 1,
                'price' => 10.00,
                'original_price' => 10.00,
                'discount_percent' => 0,
                'is_best_value' => false,
                'is_most_popular' => false,
                'stock' => 100,
                'sort_order' => 1,
            ],
            [
                'name' => '5 Keyrings Plan (Monthly Billing)',
                'quantity' => 5,
                'price' => 15.00,
                'original_price' => 15.00,
                'discount_percent' => 0,
                'is_best_value' => false,
                'is_most_popular' => true,
                'stock' => 100,
                'sort_order' => 2,
            ],
            [
                'name' => '10 Keyrings Plan (Monthly Billing)',
                'quantity' => 10,
                'price' => 20.00,
                'original_price' => 20.00,
                'discount_percent' => 0,
                'is_best_value' => true,
                'is_most_popular' => false,
                'stock' => 100,
                'sort_order' => 3,
            ],
            [
                'name' => '5 Keyrings Plan (Annual Billing - 1 Mo Free)',
                'quantity' => 5,
                'price' => 165.00,
                'original_price' => 180.00,
                'discount_percent' => 8,
                'is_best_value' => false,
                'is_most_popular' => true,
                'stock' => 100,
                'sort_order' => 4,
            ],
            [
                'name' => '10 Keyrings Plan (Annual Billing - 1 Mo Free)',
                'quantity' => 10,
                'price' => 220.00,
                'original_price' => 240.00,
                'discount_percent' => 8,
                'is_best_value' => true,
                'is_most_popular' => false,
                'stock' => 100,
                'sort_order' => 5,
            ],
        ];

        foreach ($variantsData as $vData) {
            ProductVariant::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'name' => $vData['name'],
                ],
                $vData
            );
        }
    }
}
