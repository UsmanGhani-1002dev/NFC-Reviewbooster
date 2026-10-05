<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Has Been Shipped!</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
    <!-- Outer Wrapper Table for 100% Background Coverage -->
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f4f6f9" style="background-color: #f4f6f9; width: 100%; padding: 30px 10px;">
        <tr>
            <td align="center">
                <!-- Main Container Table -->
                <table role="presentation" width="600" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" bgcolor="#1800ad" style="background-color: #1800ad; padding: 35px 20px; color: #ffffff; text-align: center;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff; font-family: 'Segoe UI', Arial, sans-serif;">
                                🚚 Your Order is On Its Way!
                            </h1>
                            <p style="margin: 8px 0 0 0; font-size: 14px; color: #ffffff; opacity: 0.9;">
                                Order #{{ $order->order_number }}
                            </p>
                            <div style="display: inline-block; background-color: #0cc0df; color: #ffffff; font-size: 11px; font-weight: 800; text-transform: uppercase; padding: 5px 14px; border-radius: 20px; margin-top: 12px; letter-spacing: 0.5px;">
                                Dispatched via {{ $order->carrier ?? 'Royal Mail' }}
                            </div>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 30px 30px 20px 30px; font-family: 'Segoe UI', Arial, sans-serif;">
                            <!-- Prominent Greeting -->
                            <h2 style="font-size: 22px; font-weight: 800; color: #1e293b; margin: 0 0 12px 0; font-family: 'Segoe UI', Arial, sans-serif;">
                                Hi {{ $order->customer_name }},
                            </h2>

                            <p style="font-size: 15px; color: #475569; line-height: 1.6; margin: 0 0 24px 0;">
                                Great news! Your order has been processed and dispatched. You can track your parcel's delivery progress using the details below:
                            </p>

                            <!-- Tracking Card -->
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; margin-bottom: 25px;">
                                <tr>
                                    <td align="center" style="padding: 24px 20px; text-align: center;">
                                        <div style="font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px;">
                                            COURIER: {{ strtoupper($order->carrier ?? 'Royal Mail') }}
                                        </div>

                                        @if($order->clean_tracking_number)
                                            <div style="font-family: 'Courier New', Courier, monospace; font-size: 22px; font-weight: 800; color: #1800ad; letter-spacing: 1.5px; background-color: #ffffff; padding: 10px 20px; border-radius: 8px; border: 1px dashed #cbd5e1; display: inline-block; margin-bottom: 18px;">
                                                {{ $order->clean_tracking_number }}
                                            </div>
                                            <div>
                                                <a href="{{ $order->tracking_url }}" target="_blank" style="display: inline-block; background-color: #d9251d; color: #ffffff !important; font-weight: 800; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-size: 15px; font-family: 'Segoe UI', Arial, sans-serif;">
                                                    🔴 Track Your Item on {{ $order->carrier ?? 'Royal Mail' }} &rarr;
                                                </a>
                                            </div>
                                        @else
                                            <p style="font-size: 14px; color: #475569; margin: 0;">
                                                Your parcel has been dispatched. Tracking info is updating with the courier.
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <!-- Shipping Address -->
                            <div style="font-size: 13px; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #f1f5f9; padding-bottom: 6px; margin-top: 25px; margin-bottom: 10px;">
                                Shipping Address
                            </div>
                            <div style="background-color: #f8fafc; border-radius: 8px; padding: 16px; font-size: 14px; color: #475569; line-height: 1.6; border: 1px solid #e2e8f0; margin-bottom: 25px;">
                                <strong style="color: #1e293b;">{{ $order->customer_name }}</strong><br>
                                {{ $order->formatted_address }}
                            </div>

                            <!-- Items Summary -->
                            <div style="font-size: 13px; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #f1f5f9; padding-bottom: 6px; margin-bottom: 10px;">
                                Order Items
                            </div>
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 25px;">
                                @foreach($order->items as $item)
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #1e293b;">
                                        <strong>{{ $item->product_name }}</strong>
                                        <div style="font-size: 12px; color: #64748b;">{{ $item->variant_name }} &times; {{ $item->quantity }}</div>
                                    </td>
                                    <td align="right" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; font-weight: 800; color: #0f172a;">
                                        &pound;{{ number_format($item->total, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </table>

                            <!-- Need Help Box -->
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #eff6ff; border-radius: 10px; border: 1px solid #bfdbfe;">
                                <tr>
                                    <td style="padding: 14px 16px; text-align: center; font-size: 13px; color: #1e40af; line-height: 1.5;">
                                        💡 <strong>Need Help?</strong> You can also track your order anytime on our website or reply directly to this email.
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" bgcolor="#f8fafc" style="background-color: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0 0 4px 0;">&copy; {{ date('Y') }} {{ config('app.name', 'Tap Review Cards') }}. All rights reserved.</p>
                            <p style="margin: 0;">Managed & operated under Enovtec Ltd.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
