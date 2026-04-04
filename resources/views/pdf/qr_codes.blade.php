	<!DOCTYPE html>
<html>
<head>
    <title>QR Code Tables</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 0;
            margin: 0;
        }
        .container {
            width: 100%;
            text-align: center;
        }
        /* Kotak per QR Code */
        .qr-box {
            display: inline-block;
            width: 45%; /* Muat 2 kotak per baris */
            margin: 10px;
            padding: 20px 0;
            border: 2px dashed #ccc; /* Garis putus-putus untuk panduan gunting */
            text-align: center;
            page-break-inside: avoid; /* Mencegah kotak terpotong beda halaman */
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .sub-title {
            font-size: 14px;
            margin-bottom: 15px;
            color: #666;
        }
        img {
            width: 150px; /* Ukuran tampilan QR di PDF */
            height: 150px;
        }
        .footer {
            margin-top: 10px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        @foreach($tables as $table)
            <div class="qr-box">
                <div class="title">MEJA {{ $table['table_number'] }}</div>
                <div class="sub-title">Scan untuk memesan</div>
                
                <img src="{{ $table['qr_image'] }}" alt="QR Table {{ $table['table_number'] }}">

                <div class="footer">Grand Santhi Coffee Shop</div>
            </div>
        @endforeach
    </div>
</body>
</html>