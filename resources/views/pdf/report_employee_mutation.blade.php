<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mutasi Pegawai</title>
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

    <h4 align="center">Laporan Daftar Mutasi Pegawai</h4>
    <table cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor SK</th>
                <th>Tanggal Mutasi</th>
                <th>Nama Pegawai</th>
                <th>Bagian Lama</th>
                <th>Sub Bagian Lama</th>
                <th>Bagian Baru</th>
                <th>Sub Bagian Baru</th>
                <th>Jabatan Lama</th>
                <th>Jabatan Baru</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    <td class="no-col">{{ $loop->iteration }}</td>
                    <td>{{ $record->decision_letter_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($record->mutation_date)->format('d F Y') }}</td>
                    <td>{{ $record->employeeMutation->name }}</td>
                    <td>{{ $record->oldDepartment->name }}</td>
                    <td>{{ $record->oldSubDepartment->name }}</td>
                    <td>{{ $record->newDepartment->name }}</td>
                    <td>{{ $record->newSubDepartment->name }}</td>
                    <td>{{ $record->oldPosition->name }}</td>
                    <td>{{ $record->newPosition->name }}</td>
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