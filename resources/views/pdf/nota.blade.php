<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Pesanan - {{ $order->order_code }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .header {
            border-bottom: 2px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 18px;
            color: #014421;
        }
        .header p { margin: 2px 0; }
        
        /* Modifikasi tabel info untuk 2 kolom */
        .info-wrapper {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-table {
            width: 100%;
        }
        .info-table td { 
            padding: 2px 0; 
            vertical-align: top;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .items-table th {
            border-bottom: 1px solid #ccc;
            border-top: 1px solid #ccc;
            padding: 5px 0;
            text-align: left;
        }
        .items-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .total-section {
            border-top: 2px dashed #ccc;
            padding-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="header text-center">
        <h1>GRAND SANTHI COFFEE</h1>
        <p>Jl. Alamat No. 123, Denpasar, Bali</p>
        <p>Telp: 0812-3456-7890</p>
    </div>

    <table class="info-wrapper">
        <tr>
            <td width="50%" valign="top">
                <table class="info-table">
                    <tr>
                        <td width="35%"><strong>Order ID</strong></td>
                        <td width="5%">:</td>
                        <td>{{ $order->order_code }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal</strong></td>
                        <td>:</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pelanggan</strong></td>
                        <td>:</td>
                        <td>{{ $order->customer_id ? $order->customer->name : ($order->guest_name ?? 'Guest') }}</td>
                    </tr>
                    <tr>
                        <td><strong>No. Meja</strong></td>
                        <td>:</td>
                        <td>{{ $order->table->table_number ?? '-' }}</td>
                    </tr>
                </table>
            </td>
            
            <td width="50%" valign="top">
                <table class="info-table">
                    <tr>
                        <td width="40%"><strong>Status Pesanan</strong></td>
                        <td width="5%">:</td>
                        <td>{{ strtoupper($order->status) }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status Pembayaran</strong></td>
                        <td>:</td>
                        <td>
                            {{ strtoupper(match($order->payment->payment_status ?? 'pending') {
                                'pending' => 'BELUM BAYAR',
                                'success' => 'LUNAS',
                                'failed'  => 'GAGAL',
                                'expired' => 'KADALUWARSA',
                                default   => 'TIDAK DIKENAL',
                            }) }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Metode Bayar</strong></td>
                        <td>:</td>
                        <td>
                            {{ strtoupper($order->payment->method == 'midtrans' ? 'Online Payment' : $order->payment->method) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="45%">Item</th>
                <th width="15%" class="text-center">Qty</th>
                <th width="20%" class="text-right">Harga</th>
                <th width="20%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    {{ $item->menu->name ?? 'Item Dihapus' }}
                    @if($item->note)
                    <br><small style="color: #666; font-style: italic;">* {{ $item->note }}</small>
                    @endif
                </td>
                <td class="text-left">{{ $item->qty }}</td>
                <td class="text-center">{{ number_format($item->menu->price, 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($item->menu->price * $item->qty, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <table style="width: 100%;">
            <tr>
                <td class="text-right" style="font-size: 14px; font-weight: bold;">TOTAL TAGIHAN:</td>
                <td class="text-right" width="30%" style="font-size: 16px; font-weight: bold; color: #014421;">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Simpan nota ini sebagai bukti pembayaran yang sah.</p>
    </div>

</body>
</html>