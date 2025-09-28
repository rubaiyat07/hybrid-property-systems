<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lease Agreement Created</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #2563eb;">Your Lease Agreement</h1>

        <p>Dear {{ $lease->tenant->user->name }},</p>

        <p>Congratulations! Your lease agreement has been successfully created. Please find attached a PDF copy of your lease agreement for your records.</p>

        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h3 style="margin-top: 0; color: #2563eb;">Lease Details:</h3>
            <p><strong>Property:</strong> {{ $lease->unit->property->address }}</p>
            <p><strong>Unit:</strong> {{ $lease->unit->unit_number }}</p>
            <p><strong>Lease Period:</strong> {{ $lease->start_date->format('M d, Y') }} to {{ $lease->end_date->format('M d, Y') }}</p>
            <p><strong>Monthly Rent:</strong> ৳{{ number_format($lease->rent_amount, 2) }}</p>
            <p><strong>Security Deposit:</strong> ৳{{ number_format($lease->deposit ?? 0, 2) }}</p>
        </div>

        <p>You can also view and download your lease agreement anytime from your tenant dashboard under "My Leases".</p>

        <p>If you have any questions about your lease agreement, please contact your landlord directly.</p>

        <p>Best regards,<br>
        {{ config('app.name') }} Team</p>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666;">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
