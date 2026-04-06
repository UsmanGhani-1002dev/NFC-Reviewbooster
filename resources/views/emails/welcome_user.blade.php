<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to Review Booster</title>
    <style>
        body { font-family: 'Outfit', sans-serif; background: #ffffff; color: #1e293b; margin: 0; padding: 0; line-height: 1.6; }
        .container { max-width: 500px; margin: 40px auto; padding: 40px; border: 1px solid #f1f5f9; border-radius: 24px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .logo { margin-bottom: 30px; }
        .success-icon { font-size: 48px; margin-bottom: 20px; }
        h1 { font-size: 28px; font-weight: 800; color: #01A0FF; margin-bottom: 15px; }
        p { font-size: 16px; color: #475569; margin-bottom: 20px; }
        .card { background: #f8fafc; padding: 25px; border-radius: 20px; margin: 30px 0; border: 1px solid #e2e8f0; }
        .cta-button { display: inline-block; background: #01A0FF; color: #ffffff; padding: 16px 32px; border-radius: 16px; text-decoration: none; font-weight: 800; font-size: 16px; box-shadow: 0 10px 15px -3px rgba(1, 160, 255, 0.3); }
        .footer { font-size: 12px; color: #94a3b8; margin-top: 40px; text-align: center; }
        .label { font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; display: block; }
        .plan-name { font-size: 20px; font-weight: 800; color: #1e293b; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, {{ $user->name }}!</h1>
        <p>Thanks for choosing Review Booster! We're excited to help you grow your brand reputation through smart NFC reviews.</p>
        
        <div class="card">
            <span class="label">Your Subscription Plan</span>
            <div class="plan-name">{{ $plan->name }}</div>
            <div style="font-size: 14px; font-weight: 600; color: #64748b; margin-top: 4px;">Active for {{ $plan->duration_days }} Days</div>
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="{{ url('/dashboard') }}" class="cta-button" style="color: #ffffff;">Go to Dashboard</a>
        </div>

        <p style="margin-top: 40px;">If you have any questions or need help setting up your tags, just reply to this email!</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
