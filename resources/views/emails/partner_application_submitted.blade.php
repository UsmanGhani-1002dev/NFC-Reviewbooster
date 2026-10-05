<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New B2B Partner Application</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #e5e7eb;">
        <h2 style="color: #111827; margin-top: 0;">👑 New B2B Partner Application Received</h2>
        <p style="color: #4b5563; font-size: 15px;">A new user has submitted a B2B partner application requiring your review:</p>
        
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 10px 0; font-weight: bold; color: #374151;">Applicant Name:</td>
                <td style="padding: 10px 0; color: #111827;">{{ $applicant->name }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 10px 0; font-weight: bold; color: #374151;">Email:</td>
                <td style="padding: 10px 0; color: #111827;">{{ $applicant->email }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 10px 0; font-weight: bold; color: #374151;">Company Name:</td>
                <td style="padding: 10px 0; color: #111827;">{{ $applicant->company_name ?? 'N/A' }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 10px 0; font-weight: bold; color: #374151;">Requested Role:</td>
                <td style="padding: 10px 0; color: #7c3aed; font-weight: bold;">{{ $applicant->partner_type_label }}</td>
            </tr>
            @if($applicant->vat_number)
            <tr style="border-bottom: 1px solid #e5e7eb;">
                <td style="padding: 10px 0; font-weight: bold; color: #374151;">VAT/Tax Number:</td>
                <td style="padding: 10px 0; color: #111827;">{{ $applicant->vat_number }}</td>
            </tr>
            @endif
        </table>

        <div style="margin-top: 25px; text-align: center;">
            <a href="{{ route('admin.users.index') }}" style="background-color: #000000; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Review & Approve Application</a>
        </div>

        <p style="color: #9ca3af; font-size: 12px; margin-top: 30px; text-align: center;">Tap Review Cards Admin Portal</p>
    </div>
</body>
</html>
