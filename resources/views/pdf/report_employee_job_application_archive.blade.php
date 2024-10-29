<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Lamaran Kerja</title>
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
        .no-col, .status-col {
            text-align: center;
        }
        .signature {
            text-align: center;
            margin-top: 20px;
            padding-right: 20px;
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

    <h4 align="center">Arsip Lamaran Kerja</h4>
    <table cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th>No. Registrasi</th>
                <th>Tanggal Registrasi</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Tempat Tanggal Lahir</th>
                <th>Agama</th>
                <th>Alamat</th>
                <th>Pendidikan</th>
                <th>E-Mail</th>
                <th>Kontak</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    <td class="no-col">{{ $loop->iteration }}</td>
                    <td>{{ $record->registration_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->registration_date)->format('d F Y') }}</td>
                    <td>{{ $record->name }}</td>
                    <td>{{ $record->gender }}</td>
                    <td>{{ $record->place_of_birth }}, {{ \Carbon\Carbon::parse($record->date_of_birth)->format('d F Y') }}</td>
                    <td>{{ $record->religion }}</td>
                    <td>{{ $record->address }}</td>
                    <td>{{ $record->employeedu->name }} {{ $record->major }}</td>
                    <td>{{ $record->email }}</td>
                    <td>{{ $record->contact }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <p>Purbalingga, {{ \Carbon\Carbon::now()->format('d F Y') }}</p><br>
        <p>Mengetahui,<br>{{ $employee->employeePosition->name }}</p>
        <div class="signature-line">
            <p><strong>{{ $employee->name }}</strong></p>
        </div>
    </div>
</body>
</html>