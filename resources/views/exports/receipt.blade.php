<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; margin: 40px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #10B981; padding-bottom: 20px; }
        .header h1 { color: #10B981; margin: 0; font-size: 28px; }
        .header p { color: #666; margin: 5px 0; }
        .receipt-info { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .info-block { margin-bottom: 20px; }
        .info-block h3 { color: #10B981; margin-bottom: 8px; font-size: 14px; text-transform: uppercase; }
        .info-block p { margin: 3px 0; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background: #10B981; color: white; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .total { font-size: 24px; font-weight: bold; color: #10B981; text-align: right; margin-top: 20px; }
        .status { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-completed { background: #D1FAE5; color: #065F46; }
        .status-pending { background: #FEF3C7; color: #92400E; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SkillRide</h1>
        <p>Fleet Management System</p>
        <p>Payment Receipt</p>
    </div>

    <div class="info-block">
        <h3>Receipt Details</h3>
        <p><strong>Reference:</strong> {{ $payment->reference }}</p>
        <p><strong>Date:</strong> {{ $payment->created_at->format('F d, Y h:i A') }}</p>
        <p><strong>Status:</strong> <span class="status status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span></p>
    </div>

    <div class="info-block">
        <h3>Rider Information</h3>
        <p><strong>Name:</strong> {{ $payment->rider?->name ?? 'N/A' }}</p>
        <p><strong>Email:</strong> {{ $payment->rider?->email ?? 'N/A' }}</p>
        <p><strong>Phone:</strong> {{ $payment->rider?->phone ?? 'N/A' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Method</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Fleet Payment</td>
                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                <td>&#8358;{{ number_format($payment->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        Total: &#8358;{{ number_format($payment->amount, 2) }}
    </div>

    <div class="footer">
        <p>This is an automatically generated receipt.</p>
        <p>SkillRide Fleet Management &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
