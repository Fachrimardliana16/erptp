<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label Aset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 10cm;
            height: 5cm;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }
        td {
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
            vertical-align: middle;
        }
        .logo, .qr-code {
            width: 25%;
            height: auto;
        }
        .logo img, .qr-code img {
            max-width: 100%;
            height: auto;
        }
        .name {
            font-size: 14px;
            font-weight: bold;
        }
        .details {
            font-size: 10px;
        }
        .footer {
            font-size: 14px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
        <body>
            <div class="container">
                <table>
                    <tr>
                        <td rowspan="3" class="logo">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/logo_pdam.png'))) }}" alt="Logo Perusahaan">
                        </td>
                        <td colspan="3" class="name">{{ $asset->name }}</td>
                        <td rowspan="3" class="qr-code">
                            <img src="data:image/png;base64,{{ $qrCodeImage }}" alt="QR Code">
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
            </div>
        </body>
</html>