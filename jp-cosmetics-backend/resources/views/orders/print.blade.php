<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_number }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1f2937;
            background: #ffffff;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #77527d;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo img {
            max-width: 150px;
            max-height: 80px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #77527d;
        }

        .header p {
            margin: 2px 0;
            font-size: 11px;
            color: #6b7280;
        }

        .section {
            margin-bottom: 18px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 6px;
            /* padding-bottom: 6px; */
                        /* padding-left: 12px; */
        }

        .info-table {
            width: 100%;
            /* padding-top: 10px;
            padding-bottom: 10px; */
            border-collapse: collapse;
        }

        .info-table td {
            padding: 3px 6px 3px 0px;
            
            vertical-align: top;
        }

        .label {
            width: 120px;
            color: #6b7280;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table.items th {
            background: #f3f4f6;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #e5e7eb;
            text-align: left;
        }

        table.items td {
            padding: 8px;
            border: 1px solid #e5e7eb;
        }

        table.items th.text-right,
        table.items td.text-right {
            text-align: right;
        }

        table.items th.text-center,
        table.items td.text-center {
            text-align: center;
        }

        .product-attr {
            font-size: 10px;
            color: #6b7280;
        }

        .totals {
            border-top: 2px solid #77527d;
            padding-top: 6px;
        }

        .totals table {
            width: 100%;
            border-collapse: collapse;
            margin-left: auto;
        }

        .totals td {
            padding: 5px 0;
        }

        .totals .label {
            text-align: right;
            color: #6b7280;
            padding-right: 15px;
            min-width: 100px;
        }

        .totals .value {
            text-align: right;
            font-weight: bold;
            min-width: 80px;
        }

        .grand-total {
            font-size: 14px;
            color: #111827;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 10px;
            border-radius: 10px;
            background: #e5e7eb;
        }

        .badge.success { background: #dcfce7; color: #166534; }
        .badge.pending { background: #fef3c7; color: #92400e; }
        .badge.failed  { background: #fee2e2; color: #991b1b; }
    </style>
</head>

<body>

<div class="logo">
    {{-- <img src="path/to/your/logo.png" alt="Logo"> --}}
</div>

<div class="header">
    <h1>{{ config('app.name') }}</h1>
    <p>Invoice #{{ $order->order_number }}</p>
    {{-- <p>Transaction Id:{{  $order->transaction_id ?? '—' }}</p> --}}
    <p>{{ $order->created_at->format('d M Y, h:i A') }}</p>
</div>

<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <!-- Customer Info -->
        <td width="50%" valign="top" style="padding-right:10px;">
            <div class="section">
                <div class="section-title">Customer Information</div>
                <table class="info-table">
                    <tr>
                        <td class="">Name</td>
                        <td>{{ $order->customer->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="">Phone</td>
                        <td>{{ $order->customer->phone ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="">Email</td>
                        <td>{{ $order->customer->email ?? '—' }}</td>
                    </tr>
                </table>
            </div>
        </td>

        <!-- Shipping Info -->
        <td width="50%" valign="top" style="padding-left:10px;">
            <div class="section">
                <div class="section-title">Shipping Details</div>
                <table class="info-table">
                    <tr>
                        <td>
                            {{ $order->address->title ?? '' }}<br>
                            {{ $order->address->address ?? '' }}<br>
                            {{ $order->address->area ?? '' }} {{ $order->address->city ?? '' }}
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

<div class="section">
    <div class="section-title" style="padding-top: 1rem;">Order Items</div>
    <table class="items">
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->details as $item)
            <tr>
                <td>
                    {{ $item->product->name ?? '—' }}<br>
                    @if($item->productAttribute)
                        <span class="product-attr">
                            {{ $item->productAttribute->attribute_name }} :
                            {{ $item->productAttribute->attribute_value }}
                        </span>
                    @endif
                </td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">{{ number_format($item->payable, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 15px;">
    <tr>
        <!-- Payment Details (Left) -->
        <td width="50%" valign="top" style="padding-right:10px;">
            <div class="section">
                {{-- <div class="section-title">Payment Details</div> --}}
                <table class="info-table">
                    <tr>
                        <td class="label">Payment Method</td>
                        <td>{{ $order->payment_method }}</td>
                    </tr>
                    <tr>
                        <td class="label">Status</td>
                        <td>
                            <span class="badge {{ $order->payment_status }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                    </tr>
                    
                </table>
            </div>
        </td>

        <!-- Totals (Right) -->
        <td width="50%" valign="top" style="padding-left:10px;">
            <div class="totals" style="text-align: right;">
                <table style="margin-left: auto;">
                    <tr>
                        <td class="label">Subtotal</td>
                        <td class="value">{{ number_format($order->sub_total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Delivery</td>
                        <td class="value">{{ number_format($order->delivery_charge, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Discount</td>
                        <td class="value">- {{ number_format($order->discount_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label grand-total">Total Payable</td>
                        <td class="value grand-total">{{ number_format($order->payable_total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

<div class="footer">
    This is a system-generated invoice • Thank you for your business
</div>

</body>
</html>