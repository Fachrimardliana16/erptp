<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Surat Perjalanan Dinas</title>
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
        .no-col{
            text-align: center;
        }
        .tgl-col{
            text-align: center;
        }
        .signature {
            text-align: center;
            margin-top: 20px;
            padding-right: 20px;
            padding-left: 60%;
        }
        .signature p {
            margin: 5px 0;
            font-size: 12px;
        }
        .signature .signature-line {
            margin-top: 85px;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/kop_surat.png'))) }}" alt="Kop Surat Perusahaan">
    </div>

    <h4 align="center">Laporan Daftar Surat Perjalanan Dinas</h4>
    <table cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor Surat</th>
                <th>Nama Pegawai</th>
                <th>Pegawai Pengikut</th>
                <th>Tanggal</th>
                <th>Tujuan</th>
                <th>Detail Tujuan</th>
                <th>Maksud Perjalanan</th>
                <th>Pembiayaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    <td class="no-col">{{ $loop->iteration }}</td>
                    <td>{{ $record->registration_number }}</td>
                    <td>{{ $record->businessTravelEmployee->name }}</td>
                    <td>
                        @if($record->followers->isNotEmpty())
                            @foreach($record->followers as $index => $follower)
                                {{ $index + 1 }}. {{ $follower->name }}
                                @if(!$loop->last)<br>@endif
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td class="tgl-col">{{ \Carbon\Carbon::parse($record->start_date)->format('d/m/Y') }}
                    <br>-<br>
                        {{ \Carbon\Carbon::parse($record->end_date)->format('d/m/Y') }}
                    </td>
                    <td>{{ $record->destination }}</td>
                    <td>{{ $record->destination_detail }}</td>
                    <td>{{ $record->purpose_of_trip }}</td>
                    <td>{{ $record->business_trip_expenses }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Tanda tangan di bawah tabel -->
    <div class="signature">
        <p>Purbalingga, {{ \Carbon\Carbon::now()->format('d F Y') }}</p><br>
        <p>Mengetahui,<br>{{ $employees->employeePosition->name }}</p>
        <div class="signature-line">
            <p><strong>{{ $employees->name }}</strong></p>
        </div>
    </div>
</body>
</html>