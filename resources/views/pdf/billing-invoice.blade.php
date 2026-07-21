<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ substr($billing->uuid, 0, 8) }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.2cm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 100%;
            padding: 10px;
        }

        .header, .invoice-details, .amount-section, .footer {
            page-break-inside: avoid;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #008080;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .logo img {
            max-height: 40px;
            width: auto;
        }

        .company-info {
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        .invoice-header {
            background-color: #008080;
            color: white;
            text-align: center;
            padding: 6px;
            border-radius: 3px;
            font-size: 16px;
            margin-bottom: 12px;
        }

        .invoice-details {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 3px;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            font-size: 10px;
            padding: 6px;
        }

        th {
            background-color: #008080;
            color: white;
            text-align: left;
        }

        tr td:last-child {
            text-align: right;
        }

        .amount-section {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 3px;
            width: 100%;
            max-width: 360px;
            float: right;
            border: 1px solid #dee2e6;
            margin-top: 12px;
        }

        .total-amount td {
            font-weight: bold;
            font-size: 12px;
            color: #008080;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 9px;
            border-radius: 8px;
            background-color: white;
            color: #008080;
            font-weight: bold;
        }

        .footer {
            font-size: 9px;
            color: #666;
            text-align: center;
            border-top: 1px solid #dee2e6;
            padding-top: 5px;
            margin-top: 15px;
            position: relative;
            bottom: 0;
        }

        h3 {
            font-size: 12px;
            margin-bottom: 4px;
            color: #008080;
        }

        .bank-details h3 {
            border-bottom: 1px solid #008080;
            padding-bottom: 3px;
        }

        .bank-details td {
            font-size: 10px;
            vertical-align: top;
        }
        .bank-box {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 3px;
            border: 1px solid #dee2e6;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            margin-right: 10px;
            font-size: 10px;
        }

        .bottom-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            clear: both;
        }

        .bank-details {
            width: 60%;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 3px;
            border: 1px solid #dee2e6;
        }

        .bank-details-label {
            font-size: 12px;
            color: #008080;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #008080;
            padding-bottom: 3px;
        }

    </style>

</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="{{ public_path('images/DOCMEY_LOGO.png') }}" alt="Docmey Logo">
            </div>
            <div class="company-info">
                <div>{{ env('COMPANY_NAME') }}</div>
                <div>{{ env('ADDRESS') }}</div>
                <div>{{ env('ADDRESSONE') }}</div>
                <div>{{ env('ADDRESSTWO') }}</div>
                <div>Email: {{ env('ADMIN_EMAIL') }}</div>
                <div>Phone: {{ env('MOBILE') }}</div>
            </div>
        </div>

        <div class="invoice-header">
            <h1 style="margin: 0; font-size: 18px;">INVOICE</h1>
            <div class="status-badge" style="margin-top: 4px;">
                Invoice #: {{ substr($billing->uuid, 0, 8) }}
            </div>
        </div>

        <div class="invoice-details">
            <table>
                <tr>
                    <td style="width: 50%; vertical-align: top;">
                        <h3>Bill To:</h3>
                        <div>{{ $billing->selectedTeam->name }}</div>
                        <div>GST: {{ $billing->billingDetail->gst_number }}</div>
                        <div>{{ $billing->billingDetail->billing_address }}</div>
                    </td>
                    <td style="width: 50%; text-align: right; vertical-align: top;">
                        <h3>Bill From:</h3>
                        <div>{{ env('COMPANY_NAME') }}</div>
                        <div>GSTIN: {{ env('GSTIN_NUMBER') }}</div>
                        <div>{{ env('ADDRESS') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Period</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background-color: #f8f9fa;">
                    <td>{{ \Illuminate\Support\Str::limit($billing->invoice_matter, 100) }}</td>
                    <td>
                        {{ $billing->billing_start_date->format('M d, Y') }} - 
                        {{ $billing->billing_end_date->format('M d, Y') }}
                    </td>
                    <td>₹{{ number_format($billing->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="amount-section">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td>₹{{ number_format($billing->amount, 2) }}</td>
                </tr>
                <tr>
                    <td>GST (18%):</td>
                    <td>₹{{ number_format($billing->amount * 0.18, 2) }}</td>
                </tr>
                <tr class="total-amount">
                    <td><strong>Total:</strong></td>
                    <td><strong>₹{{ number_format($billing->amount * 1.18, 2) }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="bottom-section">
            <div class="bank-details">
                <div class="bank-details-label">Bank Details</div>
                <table>
                    <tr>
                        <td style="width: 100%;">
                            <div style="margin-bottom: 5px;">
                                <strong style="color: #008080;">Bank Name:</strong> 
                                <span>{{ env('BANK_NAME') }}</span>
                            </div>
                            <div style="margin-bottom: 5px;">
                                <strong style="color: #008080;">Account Name:</strong> 
                                <span>{{ env('BANK_ACCOUNT_NAME') }}</span>
                            </div>
                            <div style="margin-bottom: 5px;">
                                <strong style="color: #008080;">Account Type:</strong> 
                                <span>{{ env('BANK_ACCOUNT_TYPE') }}</span>
                            </div>
                            <div>
                                <strong style="color: #008080;">Account Number:</strong> 
                                <span>{{ env('BANK_ACCOUNT_NUMBER') }}</span>
                            </div>
                            <div>
                                <strong style="color: #008080;">SWIFT Code:</strong> 
                                <span>{{ env('BANK_SWIFT_CODE') }}</span>
                            </div>
                            <div>
                                <strong style="color: #008080;">IFSC Code:</strong> 
                                <span>{{ env('BANK_IFSC') }}</span>
                            </div>
                            <div>
                                <strong style="color: #008080;">PAN:</strong> 
                                <span>{{ env('PAN_NUMBER') }}</span>
                            </div>
                        </td>
                     
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>This is a computer-generated invoice. No signature required.</p>
        <p>Generated on: {{ $generated_at }}</p>
        <p>{{ config('app.name') }} | Deltan Technologies © {{ date('Y') }}</p>
    </div>
</body>

</html>