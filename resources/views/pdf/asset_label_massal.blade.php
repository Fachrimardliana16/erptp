<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Aset</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0.2cm; /* Menggunakan margin 0.2 cm */
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .page {
            width: 100%;
            box-sizing: border-box;
            padding: 1px;
            page-break-after: always; /* Memastikan halaman baru setelah setiap 10 label */
        }
        .label {
            width: 10cm; /* Lebar label */
            height: 5cm; /* Tinggi label */
            border: 1px solid #000; /* Border untuk seluruh label */
            box-sizing: border-box;
            padding: 5px; /* Tambahkan padding untuk ruang di dalam label */
            margin-bottom: 0.1cm;
        }
        table {
            width: 100%;
            height: auto; /* Mengatur tinggi otomatis */
            border-collapse: collapse; /* Menghilangkan jarak antara sel tabel */
        }
        td {
            padding: 2px; /* Mengatur padding sel agar tidak terlalu rapat */
            text-align: center;
            vertical-align: middle;
            border: 1px solid #000; /* Menghilangkan border pada setiap sel */
        }
        .logo, .qr-code {
            width: 25%;
            height: auto;
        }
        .logo img, .qr-code img {
            max-width: 100%;
            height: auto;
            /*border: 1px solid #000;  Border untuk logo dan QR Code */
            /*padding: 2px; /* Memberikan sedikit ruang di sekitar gambar */
            /*box-sizing: border-box; /* Pastikan padding dihitung dalam lebar */
        }
        .name, .details, .footer {
            font-size: 10px; /* Mengatur ukuran font */
            border: 1px solid #000; /* Border untuk bagian nama, detail, dan footer */
            padding: 2px; /* Memberikan sedikit ruang di dalam sel */
            box-sizing: border-box; /* Pastikan padding dihitung dalam lebar */
        }
        .name {
            font-weight: bold; /* Menebalkan teks nama */
            font-size: 14px; /* Ukuran font khusus untuk nama */
        }
        .footer {
            font-size: 14px; /* Ukuran font untuk footer */
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @foreach ($assets as $index => $asset)
        @if ($index % 10 == 0) <!-- Memulai halaman baru setelah setiap 10 label -->
            <table class="page">
        @endif
        
        @if ($index % 2 == 0) <!-- Memeriksa apakah indeks genap -->
            <tr>
        @endif
        
        <td class="label">
            <table>
                <tr>
                    <td rowspan="3" class="logo">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/logo_pdam.png'))) }}" alt="Logo Perusahaan">
                    </td>
                    <td colspan="3" class="name">{{ $asset->name }}</td>
                    <td rowspan="3" class="qr-code">
                        <img src="data:image/png;base64,{{ $qrCodes[$asset->id] }}" alt="QR Code">
                    </td>
                </tr>
                <tr>
                    <td class="details">Sumber Dana ({{ $asset->funding_source }})</td>
                    <td class="details">Tahun Pembelian ({{ $asset->purchase_date->format('Y') }})</td>
                    <td class="details">Lokasi ({{ $asset->AssetMutationLocation->name }})</td>
                </tr>
                <tr>
                    <td colspan="3" class="footer">{{ $asset->assets_number }}</td>
                </tr>
            </table>
        </td>

        @if ($index % 2 == 1 || $index == count($assets) - 1) <!-- Menutup baris pada indeks ganjil atau terakhir -->
            </tr>
        @endif

        @if ($index % 10 == 9 || $index == count($assets) - 1) <!-- Menutup tabel pada indeks ke-9 atau terakhir -->
            </table>
        @endif
    @endforeach
</body>
</html>
