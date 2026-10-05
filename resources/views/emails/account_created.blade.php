<x-mail-layout heading="Your account is ready" preview="Your Tap Review Cards account has been created.">
    <p style="margin:0 0 16px; font-size:15px; color:#374151;">Hi <strong>{{ $user->name }}</strong>,</p>

    <p style="margin:0 0 16px; font-size:15px; line-height:1.6; color:#4b5563;">
        Your Tap Review Cards account has been created by our team. You can now sign in and start using your dashboard.
    </p>

    <div style="background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px; padding:16px 18px; margin:22px 0;">
        <span style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#94a3b8; margin-bottom:4px;">Your login email</span>
        <span style="font-size:15px; font-weight:600; color:#0f172a;">{{ $user->email }}</span>
    </div>

    <p style="margin:0 0 4px; font-size:15px; line-height:1.6; color:#4b5563;">
        Use the password that was shared with you to log in. We recommend changing it after your first sign-in.
    </p>

    <x-mail-button :url="route('login')">Log in to your account</x-mail-button>

    <p style="margin:18px 0 0; font-size:13px; line-height:1.6; color:#9ca3af;">
        If you did not expect this email, please contact our support team.
    </p>
</x-mail-layout>
