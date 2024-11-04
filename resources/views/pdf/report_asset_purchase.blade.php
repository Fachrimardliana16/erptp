<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Aset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
        }
        .kop-surat img {
            max-width: 125%;
            height: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px; /* Memberikan ruang untuk tanda tangan */
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px; /* Ukuran padding dikurangi untuk teks lebih kecil */
            font-size: 12px; /* Ukuran teks dikurangi */
        }
        th {
            text-align: center; /* Menyelaraskan teks header tabel ke tengah */
            background-color: #f2f2f2; /* Memberikan warna latar belakang yang ringan untuk header tabel */
            font-weight: bold;
        }
        td {
            text-align: left; /* Menyelaraskan teks sel tabel ke kiri */
        }
        .no-col {
            text-align: center; /* Menyelaraskan teks kolom 'No.' ke tengah */
        }
        .total-col {
            text-align: center; /* Menyelaraskan teks kolom 'No.' ke tengah */
        }
        .condition-col {
            text-align: center; /* Menyelaraskan teks kolom 'No.' ke tengah */
        }
        .signature {
            text-align: center;
            margin-top: 20px; /* Jarak dari tabel */
            padding-right: 20px;
            padding-left: 60%;
        }
        .signature p {
            text-align: center;
            margin: 5px 0;
            font-size: 12px; /* Menyamakan ukuran font dengan tabel */
        }
        .signature .signature-line {
            margin-top: 85px; /* Jarak untuk tanda tangan */
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Kop surat dengan gambar Base64 -->
    <div class="kop-surat">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/kop_surat.png'))) }}" alt="Kop Surat Perusahaan">
    </div>

    <h4 align="center">Laporan Daftar Pembelian Barang</h4>
    <table cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor Permintaan</th>
                <th>Nama Aset</th>
                <th>Kategori</th>
                <th>Nomor Aset</th>
                <th>Merk</th>
                <th>Tanggal Pembelian</th>
                <th>Kondisi</th>
                <th>Sumber Dana</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalPrice = 0;
            @endphp
            @foreach($records as $record)
                <tr>
                    <td class="no-col">{{ $loop->iteration }}</td>
                    <td>{{ $record->assetRequest->document_number }}</td>
                    <td>{{ $record->asset_name }}</td>
                    <td>{{ $record->category->name }}</td>
                    <td>{{ $record->assets_number }}</td>
                    <td>{{ $record->brand }}</td>
                    <td>{{ $record->purchase_date }}</td>
                    <td>{{ $record->condition->name }}</td>                 
                    <td>{{ $record->funding_source }}</td>
                    <td>Rp {{ number_format($record->price, 0, ',','.') }}</td>
                </tr>
                @php
                $totalPrice += $record->price;
                @endphp
            @endforeach
            <!-- Baris total -->
            <tr>
                <td colspan="8"></td> <!-- Kosongkan kolom sebelum kolom total -->
                <td class='total-col'><strong>Total</strong></td> <!-- Merge sampai kolom sumber dana -->
                <td><strong>Rp {{ number_format($totalPrice, 0, ',','.') }}</strong></td> <!-- Total Pembelian -->
            </tr>
        </tbody>
    </table>

    <!-- Tanda tangan di bawah tabel -->
    <div class="signature">
        <p>Purbalingga, {{ \Carbon\Carbon::now()->format('d F Y') }}</p><br>
        <p>Mengetahui,<br>{{ $employee->employeePosition->name }}</p>
        <div class="signature-line">
            <p><strong>{{ $employee->name }}</strong></p>
        </div>
    </div>
</body>
</html>
