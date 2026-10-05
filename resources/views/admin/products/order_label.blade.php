@php
    $addr = $order->shipping_address ?? [];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shipping Label — {{ $order->order_number }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/JsBarcode/3.11.5/JsBarcode.all.min.js"></script>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 24px;
            background: #f1f5f9;
        }
        .print-bar { max-width: 350px; margin: 0 auto 16px; text-align: right; }
        .print-bar button { background: #01A0FF; color: #fff; border: none; padding: 10px 22px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; }
        .print-bar a { margin-right: 12px; color: #475569; font-size: 14px; text-decoration: none; }
        .label {
            max-width: 350px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            padding: 28px;
        }
        
        .to .name { font-size: 18px; font-weight: 800; margin-bottom: 6px; color: #0f172a; }
        .to .lines { font-size: 14px; line-height: 1.6; font-weight: 600; color: #475569; }
        .to .postcode { font-size: 16px; font-weight: 800; letter-spacing: .05em; margin-top: 6px; color: #0f172a; }

        @media print {
            body { background: #fff; padding: 0; }
            .print-bar { display: none; }
            .label { border-width: 2px; margin: 0; }
            @page { margin: 10mm; }
        }
    </style>
</head>
<body>
    <div class="print-bar">
        <a href="{{ route('admin.orders.edit', $order) }}">&larr; Back to order</a>
        <button onclick="window.print()">🖨 Print Label</button>
    </div>

    <div class="label">

        <div class="addr-row">
            <div class="to">
                <div class="name">{{ $order->customer_name }}</div>
                <div class="lines">
                    {{ $addr['line1'] ?? '' }}<br>
                    @if(!empty($addr['line2'])){{ $addr['line2'] }}<br>@endif
                    {{ $addr['city'] ?? '' }}@if(!empty($addr['county'])), {{ $addr['county'] }}@endif<br>
                    {{ $addr['country'] ?? '' }}
                </div>
                @if(!empty($addr['postcode']))
                    <div class="postcode">{{ $addr['postcode'] }}</div>
                @endif
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 400); });
    </script>
</body>
</html>