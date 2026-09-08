        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Delivery Challan - {{ $order->order_no }}</title>
            <style>
                body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #1f2937; margin: 0; padding: 0; line-height: 1.4; }
                .invoice-box { max-width: 800px; margin: auto; padding: 25px; border: 1px solid #e5e7eb; background: #fff; }
                .header-table, .details-table, .items-table { width: 100%; border-collapse: collapse; }
                .header-table td, .details-table td { padding: 5px; vertical-align: top; }
                .company-title { font-size: 20px; font-weight: bold; color: #4f46e5; margin: 0; letter-spacing: 0.5px; }
                .challan-badge { font-size: 12px; font-weight: bold; background: #e0e7ff; color: #3730a3; padding: 6px 14px; border-radius: 6px; text-align: right; display: inline-block; letter-spacing: 0.5px; }
                .heading { background: #f3f4f6; font-weight: bold; text-transform: uppercase; font-size: 9px; color: #4b5563; padding: 5px 8px; border-radius: 4px; }
                .items-table th, .items-table td { border: 1px solid #e5e7eb; padding: 8px 10px; text-align: left; }
                .items-table th { background: #f9fafb; font-size: 9px; text-transform: uppercase; color: #6b7280; font-weight: bold; }
                .text-right { text-align: right; }
                .text-center { text-align: center; }
                .total-section { margin-top: 20px; float: right; width: 320px; }
                .total-section table { width: 100%; border-collapse: collapse; }
                .total-section td { padding: 7px 10px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
                .footer { margin-top: 60px; width: 100%; font-size: 10px; }
                .signature-box { float: right; text-align: center; width: 180px; border-top: 1px solid #1f2937; padding-top: 6px; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class="invoice-box">
                <!-- Header Info -->
                <table class="header-table">
                    <tr>
                        <td>
                            <h2 class="company-title">DIORA HARDWARE</h2>
                            <div style="color: #4b5563; font-size: 10px; margin-top: 4px;">
                                Manufacturers & Wholesalers of SS Mortise Handles & Locks<br>
                                Rajkot, Gujarat, India.
                            </div>
                        </td>
                        <td style="text-align: right;">
                            <div class="challan-badge">DELIVERY CHALLAN</div>
                            <div style="margin-top: 8px; font-size: 10px; color: #4b5563;">
                                <strong>Challan No:</strong> CH-{{ $order->order_no }}<br>
                                <strong>Date:</strong> {{ $order->order_date }}<br>
                                <strong>Order Ref:</strong> {{ $order->order_no }}
                            </div>
                        </td>
                    </tr>
                </table>

                <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 15px 0;">

                <!-- Customer & Shipping Details -->
                <table class="details-table" style="margin-bottom: 20px;">
                    <tr>
                        <td style="width: 50%;">
                            <div class="heading">Shipped / Billed To:</div>
                            <div style="margin-top: 6px;">
                                <strong style="font-size: 12px;">{{ $order->customer->customer_name ?? 'N/A' }}</strong><br>
                                @if($order->customer->company_name ?? false)
                                    Company: {{ $order->customer->company_name }}<br>
                                @endif
                                Phone: {{ $order->customer->customer_phone ?? 'N/A' }}<br>
                                GSTIN: {{ $order->customer->gstin ?? 'N/A' }}
                            </div>
                        </td>
                        <td style="width: 50%;">
                            <div class="heading">Shipping Address:</div>
                            <div style="margin-top: 6px;">
                                {{ $order->customer->shipping_address ?? $order->customer->billing_address ?? 'Standard Address' }}
                                <div style="margin-top: 6px;"><strong>Fulfillment Status:</strong> {{ ucwords(str_replace('_', ' ', $order->status)) }}</div>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Order Items Matrix -->
                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width: 6%;" class="text-center">No.</th>
                            <th style="width: 54%;">Product Description</th>
                            <th style="width: 12%;" class="text-center">Qty</th>
                            <th style="width: 14%;" class="text-right">Price (INR)</th>
                            <th style="width: 14%;" class="text-right">Total (INR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong style="font-size: 11px;">{{ $item->product->product_name ?? 'Item' }}</strong>
                                    <div style="font-size: 9px; color: #6b7280; margin-top: 2px;">
                                        Code: {{ $item->product->product_code ?? 'N/A' }} | 
                                        Type: <span style="color: #4f46e5; font-weight: bold;">{{ $item->set_type ?? 'Round Dabi' }}</span> | 
                                        Finish: {{ $item->product->finish ?? 'Standard' }}
                                    </div>
                                </td>
                                <td class="text-center"><strong>{{ $item->quantity }}</strong></td>
                                <td class="text-right">{{ number_format($item->price, 2) }}</td>
                                <td class="text-right" style="font-weight: bold;">{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Totals & Discount Section -->
                <div class="total-section">
                    <table>
                        <tr>
                            <td>Subtotal:</td>
                            <td class="text-right"><strong>{{ number_format($order->subtotal ?? $order->total_amount, 2) }}</strong></td>
                        </tr>
                        @if(($order->discount_amount ?? 0) > 0)
                            <tr>
                                <td>Discount ({{ $order->discount_type === 'percentage' ? $order->discount_value . '%' : 'Flat' }}):</td>
                                <td class="text-right" style="color: #dc2626; font-weight: bold;">
                                    -{{ number_format($order->discount_amount, 2) }}
                                </td>
                            </tr>
                        @endif
                        <tr style="background: #eef2ff;">
                            <td><strong style="color: #3730a3; font-size: 12px;">Grand Total:</strong></td>
                            <td class="text-right" style="font-size: 13px; font-weight: bold; color: #4f46e5;">
                                {{ number_format($order->total_amount, 2) }}
                            </td>
                        </tr>
                    </table>
                </div>

                <div style="clear: both;"></div>

                @if($order->notes)
                    <div style="margin-top: 25px; background: #f9fafb; padding: 10px; border-radius: 6px; border: 1px solid #f3f4f6;">
                        <strong>Remarks / Notes:</strong> {{ $order->notes }}
                    </div>
                @endif

                <!-- Footer Signatures -->
                <table class="footer">
                    <tr>
                        <td style="width: 60%; color: #4b5563; font-size: 9px;">
                            1. Goods once sold will not be taken back.<br>
                            2. Subject to Rajkot jurisdiction.<br>
                            3. Received goods in good condition.
                        </td>
                        <td style="width: 40%; text-align: right;">
                            <div style="margin-bottom: 35px; font-size: 10px;">For <strong>DIORA HARDWARE</strong></div>
                            <div class="signature-box">Authorized Signatory</div>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>