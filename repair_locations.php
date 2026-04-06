<?php

use App\Models\Order;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Http\Kernel::class)->handle(Illuminate\Http\Request::capture());

echo "Cleaning up order locations...\n";

$orders = Order::whereNotNull('google_places')->get();

foreach ($orders as $order) {
    if (empty($order->google_places)) continue;
    
    $originalCount = count($order->google_places);
    
    $cleaned = collect($order->google_places)
        ->filter(fn($loc) => !empty($loc['id']) && !empty($loc['name']))
        ->unique('id')
        ->values()
        ->all();
        
    $newCount = count($cleaned);
    
    if ($originalCount !== $newCount) {
        $order->update(['google_places' => $cleaned]);
        echo "Order #{$order->order_number}: Cleaned {$originalCount} down to {$newCount} locations.\n";
    }
}

echo "Cleanup complete.\n";
