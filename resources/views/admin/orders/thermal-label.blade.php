<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label Pengiriman - {{ $order->order_number }}</title>
    <style>
        @page {
            size: 100mm 150mm; /* Standar Ukuran Kertas Thermal Label Ekspedisi */
            margin: 0;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 8mm;
            color: #000;
            background: #fff;
            box-sizing: border-box;
            width: 100mm;
            height: 150mm;
            font-size: 11px;
            line-height: 1.25;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .store-title {
            font-size: 14px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .courier-badge {
            border: 2px solid #000;
            padding: 4px 8px;
            font-size: 13px;
            font-weight: 900;
            text-transform: uppercase;
        }
        .barcode-box {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .barcode-mock {
            font-family: 'Courier New', monospace;
            font-size: 26px;
            letter-spacing: 4px;
            font-weight: 900;
            margin: 4px 0;
        }
        .tracking-num {
            font-size: 12px;
            font-weight: bold;
        }
        .section {
            border-bottom: 1px solid #000;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }
        .label-title {
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            color: #333;
            margin-bottom: 2px;
        }
        .recipient-name {
            font-size: 13px;
            font-weight: 900;
            margin-bottom: 2px;
        }
        .recipient-phone {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .address-box {
            font-size: 11px;
            line-height: 1.3;
        }
        .cod-banner {
            background: #000;
            color: #fff;
            text-align: center;
            padding: 6px;
            font-size: 14px;
            font-weight: 900;
            margin: 6px 0;
            text-transform: uppercase;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-top: 4px;
        }
        .items-table th, .items-table td {
            border-bottom: 1px dotted #888;
            padding: 3px 0;
            text-align: left;
        }
        .items-table th:last-child, .items-table td:last-child {
            text-align: right;
        }
        .footer {
            margin-top: 8px;
            font-size: 9px;
            text-align: center;
            color: #555;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 6mm;
            }
        }
    </style>
</head>
<body>
    <!-- Tombol Print Navigasi -->
    <div class="no-print" style="margin-bottom: 12px; background: #FFF0F3; border: 1px solid #E87A90; padding: 10px; border-radius: 8px; text-align: center;">
        <button onclick="window.print()" style="background: #E87A90; color: #fff; border: none; padding: 8px 16px; font-weight: bold; border-radius: 6px; cursor: pointer; font-size: 12px;">
            🖨️ Cetak Label Thermal Ini (Ctrl + P)
        </button>
        <span style="font-size: 11px; margin-left: 10px; color: #555;">Ukuran standar: 10 x 15 cm</span>
    </div>

    <!-- Header Toko & Ekspedisi -->
    <div class="header">
        <div>
            <div class="store-title">Mikael on Shop</div>
            <div style="font-size: 9px; color: #444;">Luxury Leather Goods D2C</div>
        </div>
        <div class="courier-badge">
            {{ $order->courier_code ?: 'JNE' }}
        </div>
    </div>

    <!-- Barcode / Resi Box -->
    <div class="barcode-box">
        <div class="label-title">Nomor Resi / AWB Tracking</div>
        <div class="barcode-mock">||| | |||| | |||||| |</div>
        <div class="tracking-num">{{ $order->tracking_number ?: 'RES-' . $order->order_number }}</div>
        <div style="font-size: 9px; color: #555;">Order ID: {{ $order->order_number }}</div>
    </div>

    <!-- Banner COD jika metode COD -->
    @if(strtoupper($order->payment_method) === 'COD')
    <div class="cod-banner">
        💵 COD: TAGIH Rp {{ number_format($order->grand_total, 0, ',', '.') }}
    </div>
    @else
    <div style="border: 2px solid #000; text-align: center; padding: 4px; font-weight: 900; margin: 6px 0; font-size: 12px;">
        ✓ SUDAH LUNAS (NON-COD: {{ $order->payment_method }})
    </div>
    @endif

    <!-- Data Penerima -->
    <div class="section">
        <div class="label-title">PENERIMA:</div>
        <div class="recipient-name">{{ $order->customer_name }}</div>
        <div class="recipient-phone">📞 {{ $order->customer_phone }}</div>
        <div class="address-box">
            {{ $order->address_line }}<br>
            <strong>{{ $order->district }}, {{ $order->city }}, {{ $order->province }}</strong>
        </div>
    </div>

    <!-- Data Pengirim -->
    <div class="section" style="display: flex; justify-content: space-between;">
        <div>
            <div class="label-title">PENGIRIM:</div>
            <div style="font-weight: bold;">Mikael on Shop Gudang Pusat</div>
            <div style="font-size: 10px;">Jakarta Barat, DKI Jakarta</div>
            <div style="font-size: 10px;">WA: 0812-8899-2211</div>
        </div>
        <div style="text-align: right;">
            <div class="label-title">LAYANAN:</div>
            <div style="font-weight: 900;">{{ $order->courier_service ?: 'REG' }}</div>
            <div style="font-size: 10px;">Berat: ~1.0 Kg</div>
        </div>
    </div>

    <!-- Ringkasan Produk dalam Paket -->
    <div class="section" style="border-bottom: none;">
        <div class="label-title">ISI PAKET (PACKING LIST GUDANG):</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item & Varian</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }} ({{ $item->variant_name }})</td>
                    <td>{{ $item->quantity }} pcs</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        Harap videokan saat membuka paket. Terima kasih telah berbelanja di Mikael on Shop!
    </div>
</body>
</html>
