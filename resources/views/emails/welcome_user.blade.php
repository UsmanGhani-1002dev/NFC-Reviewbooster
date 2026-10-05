<x-mail-layout heading="Welcome to Tap Review Cards" preview="Your account and subscription are active.">
    <p style="margin:0 0 16px; font-size:18px; color:#374151;">Hi <strong>{{ $user->name }}</strong>,</p>

    <p style="margin:0 0 16px; font-size:15px; line-height:1.6; color:#4b5563;">
        Thanks for choosing Tap Review Cards. We are excited to help you grow your reputation with smart NFC review products.
    </p>

    <div style="background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px; padding:16px 18px; margin:22px 0;">
        <span style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#94a3b8; margin-bottom:4px;">Your subscription plan</span>
        <span style="font-size:16px; font-weight:700; color:#0f172a;">{{ $plan->name }}</span>
        <div style="font-size:13px; font-weight:600; color:#64748b; margin-top:2px;">Active for {{ $plan->duration_days }} days</div>
    </div>

    <x-mail-button :url="url('/dashboard')">Go to your dashboard</x-mail-button>

    <p style="margin:18px 0 0; font-size:14px; line-height:1.6; color:#6b7280;">
        If you have any questions or need help setting up your tags, just reply to this email.
    </p>
</x-mail-layout>
