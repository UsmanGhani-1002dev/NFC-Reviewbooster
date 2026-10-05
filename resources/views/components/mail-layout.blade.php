@props(['heading' => null, 'preview' => null])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ $heading ?? config('app.name', 'Tap Review Cards') }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:'Segoe UI', Arial, Helvetica, sans-serif; color:#1f2937; -webkit-font-smoothing:antialiased;">
    @if($preview)
        <div style="display:none; max-height:0; overflow:hidden; opacity:0; color:transparent;">{{ $preview }}</div>
    @endif

    <div style="padding:28px 14px;">
        <div style="max-width:560px; margin:0 auto; background:#ffffff; border:1px solid #e5e7eb; border-radius:14px; overflow:hidden;">

            <!-- Header -->
            <div style="padding:22px 30px; border-bottom:1px solid #f1f5f9;">
                <span style="font-size:18px; font-weight:700; color:#0f172a; letter-spacing:-0.2px;">Tap Review Cards</span>
            </div>

            <!-- Body -->
            <div style="padding:30px;">
                @if($heading)
                    <h1 style="margin:0 0 18px; font-size:21px; line-height:1.3; font-weight:700; color:#0f172a;">{{ $heading }}</h1>
                @endif
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div style="padding:18px 30px; border-top:1px solid #f1f5f9; background:#fafafa;">
                <p style="margin:0; font-size:12px; line-height:1.6; color:#9ca3af;">
                    &copy; {{ date('Y') }} Tap Review Cards. All rights reserved.<br>
                    This is an automated message, please do not share it.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
