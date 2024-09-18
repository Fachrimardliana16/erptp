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

    <h4 align="center">Laporan Daftar Penghapusan Aset</h4>
    <table cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Nama Aset</th>
                <th>Nilai Buku</th>
                <th>Alasan Penghapusan</th>
                <th>Nilai Penghapusan</th>
                <th>Proses Penghapusan</th>
                <th>Pejabat Mengetahui</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalServiceCost = 0;
            @endphp
            @foreach($records as $record)
                <tr>
                    <td class="no-col">{{ $loop->iteration }}</td>
                    <td>{{ $record->disposal_date }}</td>
                    <td>{{ $record->assetDisposals->name }}</td>
                    <td>Rp {{ number_format($record->book_value, 0, ',','.') }}</td>
                    <td>{{ $record->disposal_reason }}</td>
                    <td>Rp {{ number_format($record->disposal_value, 0, ',','.') }}</td>
                    <td>{{ $record->disposal_process }}</td>
                    <td>{{ $record->employeeDisposals->name }}</td>
                    <td>{{ $record->disposal_notes }}</td>
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