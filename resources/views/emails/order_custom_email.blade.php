<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectText }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            color: #333333;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
        }
        .header {
            background: linear-gradient(135deg, #1800ad 0%, #142D63 100%);
            padding: 32px 24px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 14px;
            color: #0cc0df;
            font-weight: 600;
        }
        .body {
            padding: 32px 28px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
        }
        .message-box {
            font-size: 15px;
            line-height: 1.5;
            color: #374151;
            background-color: #f9fafb;
            border-left: 4px solid #1800ad;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        .order-box {
            margin-top: 24px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            background-color: #ffffff;
        }
        .order-box-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f3f4f6;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        .item-table th {
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            color: #6b7280;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .item-table td {
            padding: 10px 0;
            font-size: 14px;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
        }
        .item-name {
            font-weight: 600;
            color: #111827;
        }
        .item-subtext {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 24px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .footer a {
            color: #1800ad;
            text-decoration: none;
            font-weight: 600;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 9999px;
            background-color: #e0e7ff;
            color: #3730a3;
            text-transform: capitalize;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tap Review Cards</h1>
            <p>Order Update #{{ $order->order_number }}</p>
        </div>
        <div class="body">
            <div class="greeting">Hello {{ $order->customer_name }},</div>
            
            <div class="message-box">
                {!! nl2br(e($messageText)) !!}
            </div>

            @if($includeSummary)
            <div class="order-box">
                <div class="order-box-title" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Order Details (#{{ $order->order_number }})</span>
                    <span class="badge">{{ ucfirst($order->status) }}</span>
                </div>
                
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th style="text-align: center;">Qty</th>
                            <th style="text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="item-name">{{ $item->product_name }}</div>
                                @if($item->variant_name)
                                    <div class="item-subtext">Option: {{ $item->variant_name }}</div>
                                @endif
                                @if(!empty($item->locations) && is_array($item->locations))
                                    @foreach($item->locations as $loc)
                                        @if(!empty($loc['name']))
                                            <div class="item-subtext">📍 {{ $loc['name'] }}</div>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td style="text-align: center; font-weight: 600;">{{ $item->quantity }}</td>
                            <td style="text-align: right; font-weight: 600;">£{{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; font-size: 14px;">
                    <div><strong>Total Amount Paid:</strong></div>
                    <div style="font-weight: 800; color: #1800ad; font-size: 16px;">£{{ number_format($order->total, 2) }}</div>
                </div>

                @if(!empty($order->shipping_address) && is_array($order->shipping_address))
                <div style="margin-top: 16px; font-size: 13px; color: #4b5563; background: #f9fafb; padding: 12px; border-radius: 8px;">
                    <strong>Shipping Address:</strong><br>
                    {{ $order->customer_name }}<br>
                    {{ $order->shipping_address['line1'] ?? '' }}<br>
                    @if(!empty($order->shipping_address['line2']))
                        {{ $order->shipping_address['line2'] }}<br>
                    @endif
                    {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['postcode'] ?? '' }}<br>
                    {{ $order->shipping_address['country'] ?? 'UK' }}
                </div>
                @elseif($order->formatted_address && $order->formatted_address !== 'N/A')
                <div style="margin-top: 16px; font-size: 13px; color: #4b5563; background: #f9fafb; padding: 12px; border-radius: 8px;">
                    <strong>Shipping Address:</strong><br>
                    {{ $order->formatted_address }}
                </div>
                @endif
            </div>
            @endif
        </div>

        <div class="footer">
            <p style="margin: 0 0 8px 0;">If you have any questions, feel free to reply to this email or contact our support team.</p>
            <p style="margin: 0;">&copy; {{ date('Y') }} {{ config('app.name', 'Tap Review Cards') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
