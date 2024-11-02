<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kenaikan Gaji Berkala Pegawai</title>
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

    <h4 align="center">Laporan Kenaikan Gaji Berkala Pegawai</h4>
    <table cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor Surat</th>
                <th>Tanggal Berkala</th>
                <th>Nama Pegawai</th>
                <th>Gaji Pokok</th>
                <th>Kenaikan</th>
                <th>Total Gaji Pokok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    <td class="no-col">{{ $loop->iteration }}</td>
                    <td>{{ $record->number_psi }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->date_periodic_salary_increase)->format('d F Y') }}</td>
                    <td>{{ $record->employeePeriodic->name }}</td>
                    <td>{{ 'Rp ' . number_format($record->basic_salary, 0, ',', '.') }}</td>
                    <td>{{ 'Rp ' . number_format($record->salary_increase, 0, ',', '.') }}</td>
                    <td>{{ 'Rp ' . number_format($record->total_basic_salary, 0, ',', '.') }}</td>
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