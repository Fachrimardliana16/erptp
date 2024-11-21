<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pelatihan/Diklat Pegawai</title>
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
            max-width: 100%;
            height: auto;
        }
        h4 {
            text-align: center;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            font-size: 12px;
        }
        th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
        }
        td {
            text-align: left;
        }
        .no-col {
            text-align: center;
        }
        .signature {
            text-align: center;
            margin-top: 20px;
            padding-left: 60%;
        }
        .signature p {
            text-align: center;
            margin: 5px 0;
            font-size: 12px;
        }
        .signature .signature-line {
            margin-top: 85px;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/kop_surat.png'))) }}" alt="Kop Surat Perusahaan">
    </div>

    <h4>Laporan Pelatihan/Diklat Pegawai</h4>
    <table cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal Pelatihan</th>
                <th>Nama Pegawai</th>
                <th>Judul Pelatihan</th>
                <th>Lokasi</th>
                <th>Penyelenggara</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    <td class="no-col">{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->training_date)->format('d F Y') }}</td>
                    <td>{{ $record->employeeTraining->name }}</td>
                    <td>{{ $record->training_title }}</td>
                    <td>{{ $record->training_location }}</td>
                    <td>{{ $record->organizer }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <p>Purbalingga, {{ \Carbon\Carbon::now()->format('d F Y') }}</p><br>
        <p>Mengetahui,<br>{{ $employees->employeePosition->name }}</p>
        <div class="signature-line">
            <p><strong>{{ $employees->name }}</strong></p>
        </div>
    </div>
</body>
</html>