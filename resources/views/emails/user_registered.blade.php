<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Registration - Review Booster</title>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background: #f1f5f9; color: #0f172a; margin: 0; padding: 0; }
        .wrapper { background: #f1f5f9; padding: 40px 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); }
        .header { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 60px 40px; text-align: center; }
        .content { padding: 40px; }
        .footer { background: #f8fafc; padding: 24px; text-align: center; font-size: 13px; color: #64748b; border-top: 1px solid #f1f5f9; }
        .chip { display: inline-flex; align-items: center; padding: 6px 16px; border-radius: 99px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; background: #e0f2fe; color: #0369a1; }
        h1 { font-size: 32px; font-weight: 900; color: #ffffff; margin: 0; line-height: 1.1; }
        .lead { color: #94a3b8; font-size: 14px; margin-top: 12px; font-weight: 500; }
        .section-title { font-size: 14px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 24px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table td { padding: 12px 0; border-bottom: 1px solid #f8fafc; }
        .label { font-size: 14px; font-weight: 600; color: #64748b; width: 140px; }
        .value { font-size: 15px; font-weight: 700; color: #0f172a; text-align: right; }
        .plan-card { background: #f8fafc; border-radius: 20px; padding: 24px; margin-top: 30px; border: 1px solid #f1f5f9; }
        .price-tag { font-size: 24px; font-weight: 900; color: #2563eb; margin-top: 8px; }
        .btn { display: inline-block; background: #2563eb; color: #ffffff !important; padding: 18px 36px; border-radius: 18px; text-decoration: none; font-weight: 800; font-size: 15px; margin-top: 30px; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2); }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>🎉 New Business Joined!</h1>
            </div>
            <div class="content">
                    <h2>Hi Admin,</h2>
                    <p style="border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">A new user has just registered and purchased a subscription. Here are the details:</p>
                <table class="data-table">
                    <tr>
                        <td class="label">Full Name</td>
                        <td class="value">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Organization</td>
                        <td class="value">{{ $user->company_name ?? 'Not Specified' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Direct Email</td>
                        <td class="value"><a href="mailto:{{ $user->email }}" style="color: #2563eb; text-decoration: none;">{{ $user->email }}</a></td>
                    </tr>
                </table>
                <div class="section-title" style="margin-top: 40px;">Subscription Package</div>
                <table class="data-table">
                    <tr>
                        <td class="label">Plan Name</td>
                        <td class="value">{{ $plan->name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Amount Paid</td>
                        <td class="value" style="color: #2563eb;">${{ number_format($plan->price, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Duration</td>
                        <td class="value">{{ $plan->duration_days }} Days</td>
                    </tr>
                </table>

                <div style="text-align: center;">
                    <a href="{{ url('/admin/users') }}" class="btn">Manage User In Dashboard</a>
                </div>
            </div>
            <div class="footer">
                This is an automated notification from the Review Booster Admin System.
                <br>&copy; {{ date('Y') }} Enovtec Technologies.
            </div>
        </div>
    </div>
</body>
</html>
