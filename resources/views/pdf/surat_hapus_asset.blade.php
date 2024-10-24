<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Berita Acara Penghapusan Aset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
            font-size: 12px;
        }
        td {
            text-align: center;
        }
        h4, h5 {
            text-align: center;
            margin-top: 20px;
        }
        p {
            text-align: justify;
        }
        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
        }
        .kop-surat img {
            max-width: 125%;
            height: auto;
        }
        /* Ukuran teks lebih kecil pada tabel detail aset */
        .detail-aset td {
            font-size: 12px;
        }
        /* Aturan untuk tabel tanda tangan tanpa border */
        .no-border-table {
            width: 100%;
            margin-top: 50px;
            border: none;
        }
        .no-border-table td {
            border: none;
            text-align: center;
            padding-top: 60px;
        }
    </style>
</head>
<body>
    <!-- Kop surat dengan gambar Base64 -->
    <div class="kop-surat">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/kop_surat.png'))) }}" alt="Kop Surat Perusahaan">
    </div>

    <h4><u>BERITA ACARA PENGHAPUSAN ASET</u></h4>
    <br>
    <p>Pada hari ini, {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}, telah dilakukan penghapusan Barang Inventaris sebagai berikut:</p>

    <table class="detail-aset">
        <thead>
            <tr>
                <th>Nama Aset</th>
                <th>Tanggal Penghapusan</th>
                <th>Nilai Buku</th>
                <th>Nilai Penghapusan</th>
                <th>Proses Penghapusan</th>
                <th>Alasan Penghapusan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $assetdisposal->assetDisposals->name }}</td>
                <td>{{ \Carbon\Carbon::parse($assetdisposal->disposal_date)->translatedFormat('d F Y') }}</td>
                <td>Rp {{ number_format($assetdisposal->book_value, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($assetdisposal->disposal_value, 0, ',', '.') }}</td>
                <td>{{ $assetdisposal->disposal_process }}</td>
                <td>{{ $assetdisposal->disposal_reason }}</td>
            </tr>
        </tbody>
    </table>

    <p>Aset tersebut telah dihapuskan dengan rincian sebagaimana di atas. Pihak yang mengetahui bertanggung jawab atas proses penghapusan sesuai dengan peraturan yang berlaku.</p>

    <p>Demikian berita acara penghapusan aset ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.</p>

    <div class="signatures">
        <table class="no-border-table">
            <tr>
                <td width="50%">
                    Pejabat Mengetahui,<br>
                    <br><br><br><br><br><br>
                    {{ $assetdisposal->employeeDisposals->name }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>