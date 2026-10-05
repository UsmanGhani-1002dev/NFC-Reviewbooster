<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RE: {{ $submission->subject }}</title>
</head>
<body style="margin:0;padding:0;background-color:#eef2f7;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#eef2f7" style="background-color:#eef2f7;">
        <tr>
            <td align="center" style="padding:30px 12px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="width:600px;max-width:600px;background-color:#ffffff;border:1px solid #e5e9f0;border-radius:14px;overflow:hidden;">
                    <!-- Header with logo -->
                    <tr>
                        <td align="center" bgcolor="#ffffff" style="background-color:#daf1ffa6;padding:15px 28px 15px;border-bottom:2px solid #142D63;">
                            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'Tap Review Cards') }}" height="46" style="height:85px;width:auto;display:block;border:0;outline:none;text-decoration:none;">
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:28px;font-family:Arial,'Segoe UI',sans-serif;color:#334155;font-size:15px;line-height:1.7;">
                            <div style="white-space:pre-wrap;color:#334155;">{{ $replyMessage }}</div>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:26px;border-top:1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding-top:18px;">
                                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#94a3b8;margin-bottom:6px;">Your original message</div>
                                        <div style="background-color:#f8fafc;border-left:3px solid #cbd5e1;padding:12px 14px;border-radius:6px;font-size:13px;color:#64748b;white-space:pre-wrap;">{{ $submission->message }}</div>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:24px 0 0;">If you have any further questions, simply reply to this email.</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" bgcolor="#ffffff" style="background-color:#ffffff;padding:18px 28px 28px;font-family:Arial,'Segoe UI',sans-serif;font-size:12px;color:#94a3b8;border-top:1px solid #eef2f7;">
                            &copy; {{ date('Y') }} {{ config('app.name', 'Tap Review Cards') }}. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
