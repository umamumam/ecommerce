<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Label Pengiriman #{{ $order->code }}</title>
    <style>
        @page {
            size: 100mm 150mm;
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 5mm;
            font-size: 10pt;
            color: #000;
        }
        .container {
            border: 2px solid #000;
            height: 140mm;
            position: relative;
        }
        .header {
            border-bottom: 2px solid #000;
            padding: 5px;
            text-align: center;
        }
        .courier-logo {
            font-size: 18pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -1px;
        }
        .barcode-section {
            text-align: center;
            padding: 10px 0;
            border-bottom: 2px dashed #000;
        }
        .barcode-img {
            width: 80%;
            height: 60px;
        }
        .waybill-text {
            font-size: 14pt;
            font-weight: bold;
            margin-top: 5px;
        }
        .address-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #000;
            table-layout: fixed;
        }
        .address-table td {
            padding: 8px;
            vertical-align: top;
            border-bottom: 2px solid #000;
        }
        .label-title {
            font-size: 8pt;
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        .address-name {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .address-detail {
            font-size: 9pt;
            line-height: 1.2;
        }
        .item-section {
            padding: 8px;
            border-bottom: 2px solid #000;
            min-height: 30mm;
        }
        .item-list {
            width: 100%;
            font-size: 9pt;
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 7pt;
            padding: 5px 0;
            background: #f0f0f0;
            font-weight: bold;
        }
        .badge {
            background: #000;
            color: #fff;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8pt;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <table width="100%">
                <tr>
                    <td align="left" width="50%">
                        <div class="courier-logo">{{ $order->shipping_courier }}</div>
                    </td>
                    <td align="right" width="50%">
                        <span class="badge">REGULER</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="barcode-section">
            @if($order->shipping_waybill)
                {!! DNS1D::getBarcodeHTML($order->shipping_waybill, 'C128', 2, 50) !!}
                <div class="waybill-text">{{ $order->shipping_waybill }}</div>
            @else
                <div style="padding: 20px; font-weight: bold; color: #666;">RESI BELUM TERSEDIA</div>
            @endif
        </div>

        <table class="address-table">
            <tr>
                <td width="50%" style="border-right: 2px solid #000;">
                    <span class="label-title">Penerima:</span>
                    <div class="address-name">{{ $order->shipping_name }}</div>
                    <div class="address-detail">
                        {{ $order->user->phone }}<br>
                        {{ $order->shipping_address }}
                    </div>
                </td>
                <td width="50%">
                    <span class="label-title">Pengirim:</span>
                    <div class="address-name">{{ config('app.name') }}</div>
                    <div class="address-detail">
                        Admin Toko<br>
                        Jakarta, Indonesia
                    </div>
                </td>
            </tr>
        </table>

        <div class="item-section">
            <span class="label-title">Daftar Barang:</span>
            <table class="item-list">
                @foreach($order->details as $item)
                <tr>
                    <td width="20" valign="top">{{ $loop->iteration }}.</td>
                    <td>
                        {{ $item->product->name }} 
                        @if($item->variant_1 || $item->variant_2)
                            <br><small>({{ $item->variant_1 }} {{ $item->variant_2 }})</small>
                        @endif
                    </td>
                    <td width="30" align="right" valign="top">x{{ $item->quantity }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="footer">
            DICETAK MELALUI SISTEM LOGISTIK {{ strtoupper(config('app.name')) }} - biteship.com
        </div>
    </div>
</body>
</html>
