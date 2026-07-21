<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Expenses Report</title>
    <style>
        @page {
            margin: 1cm;
            size: A4;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', 'Lucida Console', Monaco, monospace;
            font-size: 10px;
            line-height: 1.2;
            color: #000000;
            background: #ffffff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
        
        .report-container {
            border: 2px solid #000000;
            background: #ffffff !important;
            margin: 8px;
            padding: 12px;
        }
        
        .header {
            background: #cccccc !important;
            background-color: #cccccc !important;
            border: 2px solid #000000;
            border-bottom: 2px solid #000000;
            padding: 12px;
            text-align: center;
            margin: -12px -12px 12px -12px;
        }
        
        .company-name {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        
        .report-title {
            font-size: 12px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 8px;
        }
        
        .report-info {
            font-size: 9px;
            margin-bottom: 2px;
        }
        
        .separator-line {
            border-top: 1px solid #000000;
            margin: 8px 0;
        }
        
        .summary-section {
            background: #dddddd !important;
            background-color: #dddddd !important;
            border: 1px solid #000000;
            border-bottom: 1px solid #000000;
            padding: 8px 12px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .summary-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Courier New', monospace;
        }
        
        th {
            background: #bbbbbb !important;
            background-color: #bbbbbb !important;
            color: #000000 !important;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            padding: 6px 4px;
            text-align: left;
            border: 1px solid #000000;
        }
        
        th.center {
            text-align: center;
        }
        
        th.right {
            text-align: right;
        }
        
        td {
            padding: 4px 4px;
            border: 1px solid #000000;
            font-size: 9px;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background: #f8f8f8 !important;
        }
        
        tr:nth-child(odd) {
            background: #ffffff !important;
        }
        
        .amount-cell {
            text-align: right;
            font-weight: bold;
        }
        
        .date-cell {
            text-align: center;
            font-weight: normal;
        }
        
        .status-cell {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-paid {
            background: #ffffff !important;
            color: #000000 !important;
        }
        
        .status-pending {
            background: #f0f0f0 !important;
            color: #000000 !important;
        }
        
        .status-cancelled {
            background: #e0e0e0 !important;
            color: #000000 !important;
            text-decoration: line-through;
        }
        
        .user-cell {
            text-transform: uppercase;
        }
        
        .total-row {
            background: #d0d0d0 !important;
            font-weight: bold;
            border-top: 2px solid #000000;
        }
        
        .total-row td {
            padding: 6px 4px;
            font-weight: bold;
        }
        
        .empty-state {
            text-align: center;
            padding: 20px;
            background: #f8f8f8 !important;
            font-style: italic;
        }
        
        .footer {
            background: #cccccc !important;
            background-color: #cccccc !important;
            border: 2px solid #000000;
            border-top: 2px solid #000000;
            padding: 8px 12px;
            font-size: 8px;
            text-align: center;
            margin: 12px -12px -12px -12px;
        }
        
        .footer-line {
            margin-bottom: 2px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .field-label {
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <!-- Header Section -->
        <div class="header">
            <div class="company-name">{{ $company_name }}</div>
            <div class="report-title">EXPENSE REPORT</div>
            <div class="separator-line"></div>
            @if($fromDate && $toDate)
                <div class="report-info"><span class="field-label">Period:</span> {{ \Carbon\Carbon::parse($fromDate)->format('m/d/Y') }} - {{ \Carbon\Carbon::parse($toDate)->format('m/d/Y') }}</div>
            @elseif($fromDate)
                <div class="report-info"><span class="field-label">From:</span> {{ \Carbon\Carbon::parse($fromDate)->format('m/d/Y') }}</div>
            @elseif($toDate)
                <div class="report-info"><span class="field-label">Until:</span> {{ \Carbon\Carbon::parse($toDate)->format('m/d/Y') }}</div>
            @endif
            <div class="report-info"><span class="field-label">Generated:</span> {{ $generated_at }}</div>
            <div class="report-info"><span class="field-label">Page:</span> 1</div>
        </div>

        @php 
            $totalAmount = 0;
            $totalCount = 0;
            $cancelledCount = 0;
            foreach($expenses as $expense) {
                if ($expense->status != 'cancelled') {
                    $totalAmount += $expense->amount;
                    $totalCount++;
                } else {
                    $cancelledCount++;
                }
            }
        @endphp

        <!-- Summary Section -->
        @if(count($expenses) > 0)
        <div class="summary-section">
            <div class="summary-line">
                <span>TOTAL RECORDS:</span>
                <span>{{ count($expenses) }}</span>
            </div>
            <div class="summary-line">
                <span>APPROVED ITEMS:</span>
                <span>{{ $totalCount }}</span>
            </div>
            <div class="summary-line">
                <span>CANCELLED ITEMS:</span>
                <span>{{ $cancelledCount }}</span>
            </div>
            <div class="summary-line">
                <span>TOTAL AMOUNT:</span>
                <span>INR {{ number_format($totalAmount, 2) }}</span>
            </div>
        </div>
        @endif

        <!-- Data Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">DATE</th>
                    <th class="right" style="width: 15%;">AMOUNT</th>
                    <th style="width: 35%;">DESCRIPTION</th>
                    <th class="center" style="width: 12%;">STATUS</th>
                    <th style="width: 26%;">USER</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td class="date-cell">{{ $expense->expense_date->format('m/d/Y') }}</td>
                        <td class="amount-cell">{{ number_format($expense->amount, 2) }}</td>
                        <td>{{ strtoupper($expense->note) }}</td>
                        <td class="status-cell status-{{ $expense->status }}">
                            {{ strtoupper($expense->status) }}
                        </td>
                        <td class="user-cell">{{ strtoupper($expense->user->name) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">
                            *** NO RECORDS FOUND ***
                        </td>
                    </tr>
                @endforelse
                
                @if(count($expenses) > 0)
                    <tr class="total-row">
                        <td colspan="2">TOTAL (EXCL. CANCELLED):</td>
                        <td class="amount-cell">INR {{ number_format($totalAmount, 2) }}</td>
                        <td class="center">{{ $totalCount }}</td>
                        <td class="center">ITEMS</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-line">*** AUTO-GENERATED REPORT ***</div>
            <div class="footer-line">PRINTED: {{ $generated_at }}</div>
            <div class="footer-line">VERIFY ALL DATA FOR ACCURACY - CONFIDENTIAL DOCUMENT</div>
            <div class="footer-line">================================</div>
        </div>
    </div>
</body>
</html>