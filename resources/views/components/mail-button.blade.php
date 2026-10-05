@props(['url' => '#'])
<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:26px 0;">
    <tr>
        <td style="border-radius:10px; background:#01A0FF;">
            <a href="{{ $url }}" target="_blank"
               style="display:inline-block; padding:13px 28px; font-size:14px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:10px;">{{ $slot }}</a>
        </td>
    </tr>
</table>
