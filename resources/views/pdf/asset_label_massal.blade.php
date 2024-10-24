<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Label Aset</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0.2cm;
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
            page-break-after: always;
        }

        .label {
            width: 10cm;
            height: 5cm;
            border: 1px solid #000;
            box-sizing: border-box;
            padding: 5px;
            margin-bottom: 0.1cm;
        }

        table {
            width: 100%;
            height: auto;
            border-collapse: collapse;
        }

        td {
            padding: 2px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #000;
        }

        .logo,
        .qr-code {
            width: 25%;
            height: auto;
        }

        .logo img,
        .qr-code img {
            max-width: 100%;
            height: auto;
        }

        .name,
        .details,
        .footer {
            font-size: 10px;
            border: 1px solid #000;
            padding: 2px;
            box-sizing: border-box;
        }

        .name {
            font-weight: bold;
            font-size: 14px;
        }

        .footer {
            font-size: 14px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @foreach ($assets as $index => $asset)
        @if ($index % 10 == 0)
            <!-- Memulai halaman baru setelah setiap 10 label -->
            <table class="page">
        @endif
        @if ($index % 2 == 0)
            <!-- Memeriksa apakah indeks genap -->
            <tr>
        @endif
        <td class="label">
            <table>
                <tr>
                    <td rowspan="3" class="logo">
                        @if (file_exists(public_path('storage/logo_pdam.png')))
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/logo_pdam.png'))) }}"
                                alt="Logo Perusahaan">
                        @else
                            <span>Logo Not Found</span>
                        @endif
                    </td>
                    <td colspan="3" class="name">{{ $asset->name }}</td>
                    <td rowspan="3" class="qr-code">
                        @if (isset($qrCodes[$asset->id]))
                            <img src="data:image/png;base64,{{ $qrCodes[$asset->id] }}" alt="QR Code">
                        @else
                            <span>QR Code Missing</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="details">Sumber Dana ({{ $asset->funding_source ?? 'N/A' }})</td>
                    <td class="details">Tahun Pembelian
                        ({{ $asset->purchase_date ? $asset->purchase_date->format('Y') : 'N/A' }})</td>
                    <td class="details">Lokasi ({{ $asset->AssetMutationLocation->name ?? 'N/A' }})</td>
                </tr>
                <tr>
                    <td colspan="3" class="footer">{{ $asset->assets_number ?? 'N/A' }}</td>
                </tr>
            </table>
        </td>
        @if ($index % 2 == 1 || $index == count($assets) - 1)
            <!-- Menutup baris pada indeks ganjil atau terakhir -->
            </tr>
        @endif
        @if ($index % 10 == 9 || $index == count($assets) - 1)
            <!-- Menutup tabel pada indeks ke-9 atau terakhir -->
            </table>
        @endif
    @endforeach
</body>

</html>
