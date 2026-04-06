<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Order Notification - Tap Review Cards</title>
    <style>
        body { font-family: 'Outfit', sans-serif; background: #ffffff; color: #1e293b; margin: 0; padding: 0; line-height: 1.6; }
        .container { max-width: 600px; margin: 40px auto; padding: 40px; border: 1px solid #f1f5f9; border-radius: 24px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        h1 { font-size: 24px; font-weight: 800; color: #142D63; margin-bottom: 20px; }
        .card { background: #f8fafc; padding: 25px; border-radius: 20px; margin: 30px 0; border: 1px solid #e2e8f0; }
        .label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 4px; display: block; }
        .value { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 15px; }
        .cta-button { display: inline-block; background: #142D63; color: #ffffff; padding: 14px 28px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 14px; }
        .footer { font-size: 12px; color: #94a3b8; margin-top: 40px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 New Order Received!</h1>
        <p>A new order has been placed on Tap Review Cards. Here are the details:</p>
        
        <div class="card">
            <span class="label">Order Number</span>
            <div class="value">#{{ $order->order_number }}</div>

            <span class="label">Customer</span>
            <div class="value">{{ $order->customer_name }} ({{ $order->customer_email }})</div>

            <span class="label">Order Total</span>
            <div class="value">£{{ number_format($order->total, 2) }}</div>

            <span class="label">Product</span>
            @foreach($order->items as $item)
                <div class="value">{{ $item->product_name }} - {{ $item->variant_name }}</div>
            @endforeach

            @if($order->google_place_name)
                <span class="label">Target Business</span>
                <div class="value">{{ $order->google_place_name }}</div>
                <div class="label" style="margin-top: -10px; margin-bottom: 15px;">Place ID: {{ $order->google_place_id }}</div>
            @endif
        </div>

        <div style="text-align: center;">
            <a href="{{ route('admin.orders.index') }}" class="cta-button" style="color: #ffffff;">View Order in Admin Panel</a>
        </div>
        
        <div class="footer">
            <p>This is an automated notification. Please log in to the admin panel to manage this order.</p>
        </div>
    </div>
</body>
</html>
