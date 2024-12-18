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

    <h4 align="center">Laporan Daftar Permintaan Barang</h4>
    <table cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor Dokumen</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Jenis Barang</th>
                <th>Jumlah</th>
                <th>Keperluan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    <td class="no-col">{{ $loop->iteration }}</td>
                    <td>{{ $record->document_number }}</td>
                    <td>{{ $record->date }}</td>
                    <td>{{ $record->asset_name }}</td>
                    <td>{{ $record->category->name }}</td>
                    <td>{{ $record->quantity }}</td>
                    <td>{{ $record->purpose }}</td>
                    <td>{{ $record->status_request == 1 ? 'Selesai' : 'Belum Selesai' }}</td>
                </tr>
            @endforeach
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
