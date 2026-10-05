@php
    $isDropship = $order->is_dropship;
    $appName = config('app.name', 'Tap Review Cards');
    $siteDomain = 'tapreviewcards.co.uk';
    $addr = $order->shipping_address ?? [];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order {{ $order->order_number }} — Details</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 24px;
            background: #f1f5f9;
            line-height: 1.5;
        }
        .sheet {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 48px 56px;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
        }

        /* Header */
        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #142D63;
            padding-bottom: 18px;
            margin-bottom: 40px;
        }
        .brand img { height: 80px; width: auto; display: block; }
        .brand .name { font-size: 26px; font-weight: 800; letter-spacing: .02em; color: #142D63; text-transform: uppercase; }
        .brand .tag { font-size: 12px; color: #94a3b8; margin-top: 2px; }
        .domain { font-size: 14px; color: #64748b; }

        /* Title */
        h1.title {
            text-align: center;
            font-size: 34px;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #142D63;
            margin: 0 0 20px;
        }

        /* Boxes */
        .panel { padding: 22px 26px; margin-bottom: 30px; }
        .panel h3 { margin: 0 0 12px; font-size: 15px; font-weight: 800; letter-spacing: .03em; text-transform: uppercase; color: #142D63; }
        .panel.deliver { background: #eef4fb; border: 1px solid #dbe7f5; }
        .panel.deliver .addr { font-size: 15px; color: #334155; line-height: 1.7; }
        .panel.howto { background: #eefaf1; border: 1px solid #c9ecd6; }
        .panel.howto p { margin: 0; font-size: 14px; color: #3f5c4a; }
        .panel.warranty { background: #fdf7e9; border: 1px solid #f2e2ba; }
        .panel.warranty ul { margin: 0; padding-left: 18px; font-size: 13px; color: #6b5b31; }
        .panel.warranty li { margin-bottom: 5px; }

        /* Items table */
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; overflow: hidden; }
        thead th { background: #142D63; color: #fff; text-align: left; font-size: 13px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; padding: 14px 20px; }
        thead th.qty { text-align: center; width: 70px; }
        thead th.r { text-align: right; width: 110px; }
        tbody td { padding: 16px 20px; border: 1px solid #e5e9f0; font-size: 15px; vertical-align: top; }
        tbody td.qty { text-align: center; font-weight: 700; }
        tbody td.r { text-align: right; }
        tbody td .name { font-weight: 700; color: #1e293b; }
        tbody td .sub { font-size: 13px; color: #94a3b8; margin-top: 2px; }
        tfoot td { padding: 12px 20px; border: 1px solid #e5e9f0; font-size: 14px; }
        tfoot td.lbl { text-align: right; color: #64748b; }
        tfoot td.val { text-align: right; font-weight: 700; color: #1e293b; }
        tfoot tr.grand td { background: #eef4fb; font-size: 17px; font-weight: 800; color: #142D63; }

        /* Thank you */
        .thankyou { text-align: center; margin: 40px 0 8px; }
        .thankyou .big { font-size: 24px; font-weight: 800; color: #2f9e52; margin-bottom: 6px; }
        .thankyou .sub { font-size: 14px; color: #94a3b8; }

        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 18px; }

        /* Screen-only toolbar */
        .print-bar { max-width: 800px; margin: 0 auto 16px; text-align: right; }
        .print-bar button { background: #142D63; color: #fff; border: none; padding: 10px 22px; font-size: 14px; font-weight: 700; cursor: pointer; border-radius: 10px; }
        .print-bar a { margin-right: 12px; color: #475569; font-size: 14px; text-decoration: none; }

        @media print {
            @page { size: A4; margin: 13mm; }
            html, body { background: #fff; padding: 0; font-size: 12.5px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .sheet { box-shadow: none; border-radius: 0; max-width: 100%; padding: 0; }
            .print-bar { display: none; }

            /* Balanced spacing — roomy but still fits a single A4 page */
            .top { margin-bottom: 26px; padding-bottom: 14px; }
            .brand img { height: 60px; }
            h1.title { font-size: 27px; margin: 0 0 22px; }
            .panel { margin-bottom: 20px; padding: 15px 20px; }
            .panel h3 { margin-bottom: 9px; font-size: 13px; }
            .panel.deliver .addr { font-size: 13px; line-height: 1.55; }
            .panel.howto p { font-size: 12.5px; }
            .panel.warranty ul { font-size: 12px; }
            .panel.warranty li { margin-bottom: 4px; }
            table { margin-bottom: 20px; }
            thead th { padding: 10px 18px; font-size: 12px; }
            tbody td { padding: 11px 18px; font-size: 13px; }
            tfoot td { padding: 9px 18px; font-size: 13px; }
            tfoot tr.grand td { font-size: 15px; }
            .thankyou { margin: 22px 0 6px; }
            .thankyou .big { font-size: 21px; }
            .footer { margin-top: 20px; padding-top: 14px; }

            /* Keep related blocks intact and render brand colours in the PDF */
            table, tfoot tr, .panel, .thankyou { page-break-inside: avoid; }
            .top, thead th, tbody td, tfoot td, .panel, .thankyou .big, tfoot tr.grand td {
                -webkit-print-color-adjust: exact; print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="print-bar">
        <a href="{{ route('admin.orders.edit', $order) }}">&larr; Back to order</a>
        <button onclick="window.print()">🖨 Print</button>
    </div>

    <div class="sheet">
        <!-- Header -->
        <div class="top">
            <div class="brand">
                @if(!$isDropship)
                    <img src="{{ asset('images/logo.png') }}" alt="{{ $appName }}" onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                    <div class="name" style="display:none">{{ $appName }}</div>
                @else
                    <div class="name">Dispatch Note</div>
                    <div class="tag">Unbranded packing slip</div>
                @endif
            </div>
            @if(!$isDropship)
                <div class="domain">{{ $siteDomain }}</div>
            @endif
        </div>

        <h1 class="title">Order Details</h1>

        <!-- Deliver To -->
        <div class="panel deliver">
            <h3>Deliver To</h3>
            <div class="addr">
                <strong>{{ $order->customer_name }}</strong><br>
                {{ $addr['line1'] ?? '' }}<br>
                @if(!empty($addr['line2'])){{ $addr['line2'] }}<br>@endif
                {{ $addr['city'] ?? '' }}@if(!empty($addr['county'])), {{ $addr['county'] }}@endif<br>
                {{ $addr['postcode'] ?? '' }}<br>
                {{ $addr['country'] ?? '' }}
            </div>
        </div>

        <!-- Items -->
        @php $itemsSubtotal = $order->items->sum(fn($i) => (float) $i->total); @endphp
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="qty">Qty</th>
                    <th class="r">Price</th>
                    <th class="r">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="name">{{ $item->product_name }}</div>
                        @if($item->variant_name)<div class="sub">{{ $item->variant_name }}</div>@endif
                    </td>
                    <td class="qty">{{ $item->quantity }}</td>
                    <td class="r">£{{ number_format($item->unit_price, 2) }}</td>
                    <td class="r">£{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                @if($order->shipping_fee > 0)
                <tr>
                    <td class="lbl" colspan="3">Delivery{{ $order->shipping_method === 'express' ? ' (Express)' : ' (Standard)' }}</td>
                    <td class="val">£{{ number_format($order->shipping_fee, 2) }}</td>
                </tr>
                @else
                <tr>
                    <td class="lbl" colspan="3">Delivery</td>
                    <td class="val" style="color:#2f9e52">FREE</td>
                </tr>
                @endif
                <tr class="grand">
                    <td class="lbl" colspan="3" style="color:#142D63;font-weight:800;">Total</td>
                    <td class="val" style="color:#142D63;">£{{ number_format($order->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- How to use -->
        <div class="panel howto">
            <h3>How to use your NFC card</h3>
            <p>Ask your customer to unlock their NFC-enabled smartphone and hold it near the tap symbol on the card. The Google review page should open automatically — no app is required.</p>
        </div>

        <!-- Warranty -->
        <div class="panel warranty">
            <h3>Warranty &amp; Returns</h3>
            <ul>
                <li>If it stops working under normal use, contact us with your order number and we'll send a free replacement.</li>
                <li>Unused items can be returned within <strong>14 days</strong> of delivery. Custom-programmed items are non-refundable unless faulty.</li>
                <li>Please quote order <strong>{{ $order->order_number }}</strong> when you get in touch.</li>
            </ul>
        </div>

        @if(!$isDropship)
        <!-- Thank you -->
        <div class="thankyou">
            <div class="big">Thank you for your order!</div>
            <div class="sub">Need assistance? Visit {{ $siteDomain }}</div>
        </div>

        <div class="footer">{{ $appName }} | {{ $siteDomain }}</div>
        @else
        <div class="footer">Please dispatch to the address above in plain, unbranded packaging.</div>
        @endif
    </div>

    <script>
        window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 400); });
    </script>
</body>
</html>
