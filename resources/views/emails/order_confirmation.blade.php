<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation - Tap Review Cards</title>
    <style>
        body { font-family: 'Outfit', sans-serif; background: #ffffff; color: #1e293b; margin: 0; padding: 0; line-height: 1.6; }
        .container { max-width: 600px; margin: 40px auto; padding: 40px; border: 1px solid #f1f5f9; border-radius: 24px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        h1 { font-size: 28px; font-weight: 800; color: #01A0FF; margin-bottom: 5px; }
        .order-number { color: #94a3b8; font-size: 14px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 30px; }
        p { font-size: 16px; color: #475569; margin-bottom: 20px; }
        .card { background: #f8fafc; padding: 25px; border-radius: 20px; margin: 30px 0; border: 1px solid #e2e8f0; }
        .item-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #e2e8f0; }
        .item-name { font-weight: 700; color: #1e293b; }
        .item-variant { font-size: 13px; color: #64748b; }
        .item-price { font-weight: 800; color: #01A0FF; }
        .total-row { display: flex; justify-content: space-between; font-size: 20px; font-weight: 800; color: #1e293b; margin-top: 20px; }
        .address-box { font-size: 14px; color: #475569; margin-top: 10px; line-height: 1.4; }
        .footer { font-size: 12px; color: #94a3b8; margin-top: 40px; text-align: center; }
        .label { font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; display: block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thanks for your order!</h1>
        <div class="order-number">Order #{{ $order->order_number }}</div>
        
        <p>Hi {{ $order->customer_name }},</p>
        <p>Your payment was successful and your order is now being processed. We'll get your NFC cards programmed and shipped within 48 hours!</p>
        
        <div class="card">
            <span class="label">Order Summary</span>
            @foreach($order->items as $item)
            <div class="item-row">
                <div>
                    <div class="item-name">{{ $item->product_name }}</div>
                    <div class="item-variant">{{ $item->variant_name }} &times; {{ $item->quantity }}</div>
                    @if(!empty($item->locations))
                    <div style="margin-top: 6px;">
                        @foreach($item->locations as $loc)
                        <div style="font-size: 11px; color: #555555; margin-top: 3px;">
                            &#128205; {{ $loc['name'] }}
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($loc['name']) }}&query_place_id={{ $loc['id'] }}" style="color: #01A0FF; text-decoration: none; font-weight: 700;">(Map)</a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="item-price">£{{ number_format($item->total, 2) }}</div>
            </div>
            @endforeach
            <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; font-size: 14px; color: #64748b; margin-bottom: 8px;">
                    <span>Items Subtotal</span>
                    <span>£{{ number_format($order->total - ($order->shipping_fee ?? 0), 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 14px; color: #64748b; margin-bottom: 12px;">
                    <span>UK Shipping & Delivery</span>
                    <span>
                        @if(($order->shipping_fee ?? 0) > 0)
                            £{{ number_format($order->shipping_fee, 2) }}
                        @else
                            <strong style="color: #16a34a;">FREE</strong>
                        @endif
                    </span>
                </div>
                <div class="total-row">
                    <span>Total Paid</span>
                    <span>£{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="card">
            <span class="label">Shipping Address</span>
            <div class="address-box">
                {{ $order->customer_name }}<br>
                {{ $order->shipping_address['line1'] }}<br>
                @if(!empty($order->shipping_address['line2']))
                    {{ $order->shipping_address['line2'] }}<br>
                @endif
                {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['postcode'] }}<br>
                {{ $order->shipping_address['country'] }}
            </div>
        </div>

        @php $hasItemLocations = $order->items->contains(fn($i) => !empty($i->locations)); @endphp
        @if(!$hasItemLocations && $order->google_place_name)
        <div class="card">
            <span class="label">Business Details</span>
            <div class="item-name">{{ $order->google_place_name }}</div>
            <div class="item-variant">Place ID: {{ $order->google_place_id }}</div>
            @if($order->google_maps_link)
                <a href="{{ $order->google_maps_link }}" style="font-size: 11px; color: #01A0FF; text-decoration: none; font-weight: 700; margin-top: 10px; display: inline-block;">VERIFY ON GOOGLE MAPS &rarr;</a>
            @endif
        </div>
        @endif

        <p style="margin-top: 40px;">If you have any questions about your order, please don't hesitate to reach out to us.</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Tap Review Cards') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
