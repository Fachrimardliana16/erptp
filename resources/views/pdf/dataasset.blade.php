<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Aset</title>
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
            width: 30%;
            text-align: left;
        }
        td {
            text-align: left;
        }
        h4 {
            text-align: left;
            margin-top: 20px;
        }
        img {
            max-width: 100px;
            height: auto;
        }
        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
        }
        .kop-surat img {
            max-width: 125%;
            height: auto;
        }
    </style>
</head>
<body>
    <!-- Kop surat dengan gambar Base64 -->
    <div class="kop-surat">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/kop_surat.png'))) }}" alt="Kop Surat Perusahaan">
    </div>

    <h4>Data Asset : {{ $asset->name }}</h4>

    <table>
        <tr>
            <th>Gambar</th>
            <td><img src="{{ asset($asset->img) }}" alt="Gambar Aset"></td>
        </tr>
        <tr>
            <th>Nomor Aset</th>
            <td>{{ $asset->assets_number }}</td>
        </tr>
        <tr>
            <th>Nama</th>
            <td>{{ $asset->name }}</td>
        </tr>
        <tr>
            <th>Kategori</th>
            <td>{{ $asset->categoryAsset->name }}</td>
        </tr>
        <tr>
            <th>Tanggal Pembelian</th>
            <td>{{ \Carbon\Carbon::parse($asset->purchase_date)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <th>Kondisi</th>
            <td>{{ $asset->conditionAsset->name }}</td>
        </tr>
        <tr>
            <th>Harga</th>
            <td>Rp. {{ number_format($asset->price, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Sumber Dana</th>
            <td>{{ $asset->funding_source }}</td>
        </tr>
        <tr>
            <th>Merk</th>
            <td>{{ $asset->brand }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $asset->assetsStatus->name }}</td>
        </tr>
        <tr>
            <th>Status Transaksi</th>
            <td>{{ $asset->AssetTransactionStatus->name }}</td>
        </tr>
        <tr>
            <th>Nilai Buku</th>
            <td>Rp. {{ number_format($asset->book_value, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Tanggal Habis Nilai Buku</th>
            <td>{{ \Carbon\Carbon::parse($asset->book_value_expiry)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <th>Dibuat Pada</th>
            <td>{{ \Carbon\Carbon::parse($asset->created_at)->format('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <th>Diperbarui Pada</th>
            <td>{{ \Carbon\Carbon::parse($asset->updated_at)->format('d-m-Y H:i') }}</td>
        </tr>
    </table>
</body>
</html>