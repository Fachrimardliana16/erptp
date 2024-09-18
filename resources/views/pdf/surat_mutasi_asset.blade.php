<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Berita Acara Serah Terima Aset</title>
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

    <h4><u></u>BERITA ACARA SERAH TERIMA ASET</u></h4>
    <br>
    <p>Pada hari ini, {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}, telah dierahkan Barang Inventaris sebagai berikut:</p>

    <table class="detail-aset">
        <thead>
            <tr>
                <th>Nomor Aset</th>
                <th>Nama Aset</th>
                <th>Kondisi</th>
                <th>Lokasi</th>
                <th>Sub Lokasi</th>
                <th>Pemegang</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $assetmutation->AssetsMutation->assets_number }}</td>
                <td>{{ $assetmutation->AssetsMutation->name }}</td>
                <td>{{ $assetmutation->MutationCondition->name }}</td>
                <td>{{ $assetmutation->AssetsMutationlocation->name }}</td>
                <td>{{ $assetmutation->AssetsMutationsubLocation->name }}</td>
                <td>{{ $assetmutation->AssetsMutationemployee->name }}</td>
            </tr>
        </tbody>
    </table>

    <p>Aset tersebut telah diserahkan oleh pihak yang menyerahkan dan diterima oleh pihak yang menerima dengan rincian sebagaimana di atas. Pihak yang menerima bertanggung jawab penuh atas penggunaan, pemeliharaan, dan keamanan aset tersebut sesuai dengan peraturan yang berlaku.</p>

    <p>Demikian berita acara serah terima ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.</p>

    <div class="signatures">
        <table class="no-border-table">
            <tr>
                <td width="50%">
                    Yang Menyerahkan,<br>
                    <br><br><br><br><br><br>
                    __________________________
                </td>
                <td width="50%">
                    Yang Menerima,<br>
                    <br><br><br><br><br><br>
                    {{ $assetmutation->AssetsMutationemployee->name }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
