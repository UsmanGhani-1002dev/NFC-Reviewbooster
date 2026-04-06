<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Rating Card
        $card = Product::create([
            'name' => 'Rating Card',
            'slug' => 'rating-card',
            'subtitle' => 'NFC Google Review Card',
            'description' => 'Our premium NFC-enabled review card makes it effortless for your customers to leave Google reviews. Simply tap the card on any modern smartphone and your customer will be taken directly to your Google review page. Each card is pre-configured and ready to use straight out of the box. Made from premium PVC plastic with a professional finish.',
            'badge_text' => '#1 Google Review Card in the UK',
            'features' => [
                'Works on iPhone & Android',
                'No app required',
                '£0 monthly fees',
                'Free stand included',
            ],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        ProductVariant::create([
            'product_id' => $card->id,
            'name' => '1 Rating Card + free stand',
            'quantity' => 1,
            'price' => 14.99,
            'original_price' => 25.00,
            'discount_percent' => 25,
            'is_best_value' => false,
            'is_most_popular' => false,
            'stock' => 50,
            'sort_order' => 1,
        ]);

        ProductVariant::create([
            'product_id' => $card->id,
            'name' => '3 Rating Cards + free stand',
            'quantity' => 3,
            'price' => 39.99,
            'original_price' => 75.00,
            'discount_percent' => 35,
            'is_best_value' => false,
            'is_most_popular' => true,
            'stock' => 30,
            'sort_order' => 2,
        ]);

        ProductVariant::create([
            'product_id' => $card->id,
            'name' => '5 Rating Cards + free stand',
            'quantity' => 5,
            'price' => 69.99,
            'original_price' => 125.00,
            'discount_percent' => 45,
            'is_best_value' => true,
            'is_most_popular' => false,
            'stock' => 17,
            'sort_order' => 3,
        ]);

        // Rating Tag
        $tag = Product::create([
            'name' => 'Rating Tag',
            'slug' => 'rating-tag',
            'subtitle' => 'NFC Google Review Keychain Tag',
            'description' => 'The Rating Tag is a durable keychain-style NFC tag that works exactly like our Rating Card but in a portable keychain format. Perfect for businesses on the go. Attach it to keys, lanyards, or display stands. Made from premium stainless steel with a scratch-resistant finish.',
            'badge_text' => 'Portable & Durable',
            'features' => [
                'Works on iPhone & Android',
                'No app required',
                '£0 monthly fees',
                'Keychain design',
            ],
            'is_active' => true,
            'sort_order' => 2,
        ]);

        ProductVariant::create([
            'product_id' => $tag->id,
            'name' => '1 Rating Tag',
            'quantity' => 1,
            'price' => 14.99,
            'original_price' => 25.00,
            'discount_percent' => 20,
            'is_best_value' => false,
            'is_most_popular' => false,
            'stock' => 45,
            'sort_order' => 1,
        ]);

        ProductVariant::create([
            'product_id' => $tag->id,
            'name' => '3 Rating Tags',
            'quantity' => 3,
            'price' => 39.99,
            'original_price' => 75.00,
            'discount_percent' => 35,
            'is_best_value' => false,
            'is_most_popular' => true,
            'stock' => 25,
            'sort_order' => 2,
        ]);

        ProductVariant::create([
            'product_id' => $tag->id,
            'name' => '5 Rating Tags',
            'quantity' => 5,
            'price' => 69.99,
            'original_price' => 125.00,
            'discount_percent' => 45,
            'is_best_value' => true,
            'is_most_popular' => false,
            'stock' => 15,
            'sort_order' => 3,
        ]);
    }
}
