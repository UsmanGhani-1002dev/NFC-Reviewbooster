@php $discount = (int) $partner->getPartnerDiscountPercent(); @endphp
<x-mail-layout :heading="$status === 'approved' ? 'Your partner account is approved' : 'Partner application update'"
               :preview="$status === 'approved' ? 'Your partner account has been approved.' : 'An update on your partner application.'">

    <p style="margin:0 0 16px; font-size:15px; color:#374151;">Hi <strong>{{ $partner->name }}</strong>,</p>

    @if($status === 'approved')
        <p style="margin:0 0 16px; font-size:15px; line-height:1.6; color:#4b5563;">
            Your application for a <strong>{{ $partner->partner_type_label }}</strong> account has been approved by our team.
        </p>

        <div style="background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px; padding:16px 18px; margin:22px 0;">
            <span style="display:block; font-size:13px; font-weight:700; color:#0f172a; margin-bottom:8px;">Your partner benefits are now active</span>
            <ul style="margin:0; padding-left:18px; color:#4b5563; font-size:14px; line-height:1.7;">
                @if($discount > 0)
                    <li>Automatic <strong>{{ $discount }}% wholesale discount</strong> applied at checkout.</li>
                @endif
                <li>Unbranded, blank dropshipping enabled for your customer orders.</li>
            </ul>
        </div>

        <x-mail-button :url="route('shop.index')">Browse the wholesale shop</x-mail-button>
    @else
        <p style="margin:0 0 16px; font-size:15px; line-height:1.6; color:#4b5563;">
            Thank you for your interest in our partner programme. After reviewing your application for a
            <strong>{{ $partner->partner_type_label }}</strong> account, we are unable to approve it at this time.
        </p>
        <p style="margin:0 0 16px; font-size:15px; line-height:1.6; color:#4b5563;">
            You can still browse and purchase products with a standard account. If you have any questions,
            please contact our support team.
        </p>
    @endif

    <p style="margin:22px 0 0; font-size:14px; color:#6b7280;">Tap Review Cards Team</p>
</x-mail-layout>
