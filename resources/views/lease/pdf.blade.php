<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lease Agreement</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            text-transform: uppercase;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 16px;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        .party-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .party-info .party {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .party-info .party h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .terms {
            margin-bottom: 20px;
        }
        .terms table {
            width: 100%;
            border-collapse: collapse;
        }
        .terms table td {
            padding: 8px;
            border: 1px solid #000;
        }
        .terms table td:first-child {
            font-weight: bold;
            width: 30%;
        }
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .signature {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
            height: 30px;
        }
        .date {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Residential Lease Agreement</h1>
        <p>Lease Agreement Number: {{ $lease->id }}</p>
    </div>

    <div class="party-info">
        <div class="party">
            <h3>Landlord:</h3>
            <p><strong>{{ $lease->unit->property->owner->name }}</strong></p>
            <p>{{ $lease->unit->property->owner->email }}</p>
            <p>{{ $lease->unit->property->owner->phone ?? 'N/A' }}</p>
        </div>
        <div class="party">
            <h3>Tenant:</h3>
            <p><strong>{{ $lease->tenant->user->name }}</strong></p>
            <p>{{ $lease->tenant->user->email }}</p>
            <p>{{ $lease->tenant->user->phone ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="section">
        <h2>Property Information</h2>
        <p><strong>Property Address:</strong> {{ $lease->unit->property->address }}</p>
        <p><strong>Unit:</strong> {{ $lease->unit->unit_number }}</p>
        <p><strong>Property Type:</strong> {{ $lease->unit->property->type }}</p>
        <p><strong>Size:</strong> {{ $lease->unit->size }} sq ft</p>
    </div>

    <div class="section">
        <h2>Lease Terms</h2>
        <div class="terms">
            <table>
                <tr>
                    <td>Lease Start Date:</td>
                    <td>{{ $lease->start_date->format('F j, Y') }}</td>
                </tr>
                <tr>
                    <td>Lease End Date:</td>
                    <td>{{ $lease->end_date->format('F j, Y') }}</td>
                </tr>
                <tr>
                    <td>Monthly Rent Amount:</td>
                    <td>৳{{ number_format($lease->rent_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Security Deposit:</td>
                    <td>৳{{ number_format($lease->deposit ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td>Lease Status:</td>
                    <td>{{ ucfirst($lease->status) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <h2>Terms and Conditions</h2>
        <ol>
            <li>The Tenant agrees to pay rent on time and maintain the property in good condition.</li>
            <li>The Landlord agrees to maintain the property and make necessary repairs.</li>
            <li>This lease agreement is binding for the duration specified above.</li>
            <li>Any modifications to this agreement must be in writing and signed by both parties.</li>
            <li>The security deposit will be returned within 30 days of lease termination, subject to deductions for damages.</li>
        </ol>
    </div>

    <div class="section">
        <h2>Signatures</h2>
        <p>This agreement is entered into on {{ now()->format('F j, Y') }}.</p>

        <div class="signature-section">
            <div class="signature">
                <div class="signature-line"></div>
                <p>Landlord Signature</p>
                <p>{{ $lease->unit->property->owner->name }}</p>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <p>Tenant Signature</p>
                <p>{{ $lease->tenant->user->name }}</p>
            </div>
        </div>
    </div>

    <div class="date">
        <p>Date: {{ now()->format('F j, Y') }}</p>
    </div>
</body>
</html>
