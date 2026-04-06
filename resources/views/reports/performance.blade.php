<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ReviewBooster Performance Report</title>
    <style>
        /* PDF specific resets */
        @page { margin: 0; }
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            color: #1e293b; 
            margin: 0; 
            padding: 0;
            background-color: #ffffff;
        }

        /* Layout containers */
        .page-container { padding: 40px; }
        
        /* Brand Header */
        .brand-header {
            background-color: #0f172a;
            color: white;
            padding: 40px;
            text-align: left;
            position: relative;
        }
        .brand-header h1 { 
            margin: 0; 
            font-size: 32px; 
            font-weight: 800; 
            letter-spacing: -1px;
            color: #3b82f6;
        }
        .brand-header p { 
            margin: 5px 0 0 0; 
            font-size: 14px; 
            color: #94a3b8; 
            font-weight: 500;
        }
        .report-meta {
            position: absolute;
            top: 40px;
            right: 40px;
            text-align: right;
        }
        .report-meta div { font-size: 12px; color: #94a3b8; margin-bottom: 2px; }
        .report-meta .date { color: white; font-weight: bold; font-size: 14px; }

        /* Typography */
        .section-header {
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f5f9;
            display: block;
        }
        .section-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* KPI Cards Grid */
        .kpi-row { margin-bottom: 30px; width: 100%; border-collapse: separate; border-spacing: 15px 0; margin-left: -15px; }
        .kpi-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            text-align: left;
            border: 1px solid #e2e8f0;
        }
        .kpi-card .label { font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px; }
        .kpi-card .value { font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1; }
        .kpi-card .sub-label { font-size: 10px; color: #94a3b8; margin-top: 5px; }
        
        /* Table Styles */
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; background: white; }
        .data-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            text-align: left;
            padding: 12px 15px;
            border-bottom: 2px solid #e2e8f0;
        }
        .data-table td {
            padding: 14px 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
            vertical-align: middle;
        }
        .data-table tr:nth-child(even) { background-color: #fcfcfd; }

        /* Review Sentiment Badges */
        .sentiment-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .sentiment-positive { background-color: #dcfce7; color: #166534; }
        .sentiment-negative { background-color: #fee2e2; color: #991b1b; }

        /* Star Rating */
        .stars { color: #f59e0b; font-size: 12px; }

        /* Card Name Cell */
        .card-name { font-weight: 600; color: #3b82f6; }

        /* Footer */
        .footer {
            margin-top: 50px;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #f1f5f9;
            font-size: 10px;
            color: #94a3b8;
        }
        .page-number:after { content: counter(page); }

    </style>
</head>
<body>
    <div class="brand-header">
        <h1>ReviewBooster</h1>
        <p>Premium Analytics & Feedback Insights</p>
        
        <div class="report-meta">
            <div>Report Generated On</div>
            <div class="date">{{ now()->format('F d, Y') }}</div>
        </div>
    </div>

    <div class="page-container">
        <div class="section-header">
            <h2>Performance Snapshot</h2>
        </div>

        <table class="kpi-row">
            <tr>
                <td width="25%">
                    <div class="kpi-card" style="border-top: 4px solid #3b82f6;">
                        <div class="label">Total Reviews</div>
                        <div class="value">{{ number_format($totalReviews) }}</div>
                        <div class="sub-label">All-time feedback</div>
                    </div>
                </td>
                <td width="25%">
                    <div class="kpi-card" style="border-top: 4px solid #10b981;">
                        <div class="label">Positive</div>
                        <div class="value">{{ number_format($positiveCount) }}</div>
                        <div class="sub-label">4-5 star ratings</div>
                    </div>
                </td>
                <td width="25%">
                    <div class="kpi-card" style="border-top: 4px solid #ef4444;">
                        <div class="label">Negative</div>
                        <div class="value">{{ number_format($negativeCount) }}</div>
                        <div class="sub-label">1-2 star ratings</div>
                    </div>
                </td>
                <td width="25%">
                    <div class="kpi-card" style="border-top: 4px solid #8b5cf6;">
                        <div class="label">Active Cards</div>
                        <div class="value">{{ number_format($totalCards) }}</div>
                        <div class="sub-label">Deployed NFC/QR</div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-header">
            <h2>Recent Customer Feedback</h2>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th width="15%">Date</th>
                    <th width="20%">Card ID</th>
                    <th width="15%">Rating</th>
                    <th width="50%">Customer Comment</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentReviews as $review)
                <tr>
                    <td>{{ $review->created_at->format('M d, Y') }}</td>
                    <td class="card-name">{{ $review->card->name ?? 'Deleted Card' }}</td>
                    <td>
                        <span class="stars">
                            @for($i=1; $i<=5; $i++)
                                {{ $i <= $review->rating ? '★' : '☆' }}
                            @endfor
                        </span>
                    </td>
                    <td>
                        @if($review->rating >= 4)
                            <span class="sentiment-badge sentiment-positive">Positive</span>
                        @elseif($review->rating <= 2)
                            <span class="sentiment-badge sentiment-negative">Negative</span>
                        @endif
                        <div style="color: #64748b; margin-top: 4px; font-style: italic;">
                            "{{ Str::limit($review->review ?? 'No comment provided by the user.', 120) }}"
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #94a3b8;">No recent reviews found for this period.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-header">
            <h2>Asset Performance</h2>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th width="60%">Asset Name / Location</th>
                    <th width="40%" style="text-align: right;">Total Engagement (Reviews)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cardStats->take(10) as $stat)
                <tr>
                    <td class="card-name">{{ $stat['name'] }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($stat['review_count'] ?? 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>This report is confidential and intended for intended for {{ auth()->user()->name }}.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
