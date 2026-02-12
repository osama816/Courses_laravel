<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            background: #fff;
        }
        /* Refactored Header to Table */
        .header-table {
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header-table td {
            vertical-align: top;
            border: none; /* Reset default table border */
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-number {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        /* Refactored Columns to Table */
        .columns-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .columns-table td {
            vertical-align: top;
            width: 50%;
            border: none;
        }
        .info-row {
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        /* Standard Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .data-table th, .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .data-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        .total-row {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .total-amount {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header Table -->
        <table class="header-table">
            <tr>
                <td>
                    <div class="logo">CourseBook</div>
                    <div style="margin-top: 10px; color: #666;">
                        Online Course Platform<br>
                        support@coursebook.test
                    </div>
                </td>
                <td class="invoice-info">
                    <div class="invoice-number">Invoice #{{ $invoice->invoice_number }}</div>
                    <div style="margin-top: 10px;">
                        <div>Date: {{ $invoice->issued_at->format('F d, Y') }}</div>
                        <div>Status: 
                            <span class="status-badge status-{{ $invoice->status }}">
                                {{ strtoupper($invoice->status) }}
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="section">
            <div class="section-title">Bill To</div>
            <!-- Two Columns Table -->
            <table class="columns-table">
                <tr>
                    <td>
                        <div class="info-row">
                            <strong>{{ $user->name }}</strong>
                        </div>
                        <div class="info-row">{{ $user->email }}</div>
                    </td>
                    <td>
                        <div class="section-title" style="border: none; margin-bottom: 10px;">Payment Details</div>
                        <div class="info-row">
                            <span class="info-label">Method:</span>
                            {{ ucfirst($payment->payment_method) }}
                        </div>
                        <div class="info-row">
                            <span class="info-label">Transaction ID:</span>
                            {{ $payment->transaction_id }}
                        </div>
                        <div class="info-row">
                            <span class="info-label">Paid At:</span>
                            {{ $payment->paid_at ? $payment->paid_at->format('F d, Y H:i') : 'N/A' }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Course Details</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ $course->title }}</strong><br>
                            <small style="color: #666;">{{ $course->description }}</small>
                        </td>
                        <td class="text-right">${{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="total-section">
            <div class="total-row">
                <strong>Subtotal:</strong> ${{ number_format($invoice->amount, 2) }}
            </div>
            <div class="total-row">
                <strong>Tax:</strong> $0.00
            </div>
            <div class="total-amount">
                Total: ${{ number_format($invoice->amount, 2) }}
            </div>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>This is an automated invoice generated by CourseBook Platform.</p>
            <p>For support, please contact: support@coursebook.test</p>
        </div>
    </div>
</body>
</html>

