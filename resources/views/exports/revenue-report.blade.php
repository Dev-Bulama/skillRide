<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Revenue Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; margin: 30px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #10B981; padding-bottom: 15px; }
        .header h1 { color: #10B981; margin: 0; font-size: 24px; }
        .period { color: #666; font-size: 14px; margin-top: 5px; }
        .summary { display: flex; gap: 20px; margin-bottom: 25px; }
        .stat-box { flex: 1; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; text-align: center; }
        .stat-box .value { font-size: 22px; font-weight: bold; color: #10B981; }
        .stat-box .label { color: #666; font-size: 11px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #10B981; color: white; padding: 8px 10px; text-align: left; font-size: 12px; }
        td { padding: 8px 10px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) { background: #f9fafb; }
        .footer { text-align: center; margin-top: 30px; color: #999; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SkillRide Revenue Report</h1>
        <p class="period">{{ $from->format('M d, Y') }} - {{ $to->format('M d, Y') }}</p>
    </div>

    <div class="summary">
        <div class="stat-box">
            <div class="value">&#8358;{{ number_format($report['total_revenue'], 2) }}</div>
            <div class="label">Total Revenue</div>
        </div>
        <div class="stat-box">
            <div class="value">{{ $report['total_transactions'] }}</div>
            <div class="label">Transactions</div>
        </div>
        <div class="stat-box">
            <div class="value">&#8358;{{ number_format($report['average_payment'], 2) }}</div>
            <div class="label">Avg Payment</div>
        </div>
    </div>

    @if(!empty($report['by_method']))
    <h3>Revenue by Payment Method</h3>
    <table>
        <thead><tr><th>Method</th><th>Amount</th></tr></thead>
        <tbody>
            @foreach($report['by_method'] as $method => $amount)
            <tr><td>{{ ucfirst(str_replace('_', ' ', $method)) }}</td><td>&#8358;{{ number_format($amount, 2) }}</td></tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(!empty($report['daily']))
    <h3>Daily Breakdown</h3>
    <table>
        <thead><tr><th>Date</th><th>Amount</th></tr></thead>
        <tbody>
            @foreach($report['daily'] as $date => $amount)
            <tr><td>{{ $date }}</td><td>&#8358;{{ number_format($amount, 2) }}</td></tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y h:i A') }} | SkillRide Fleet Management</p>
    </div>
</body>
</html>
