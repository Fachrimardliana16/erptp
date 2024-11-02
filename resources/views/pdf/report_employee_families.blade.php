<!-- resources/views/pdf/report_employee_families.blade.php -->

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Keluarga Pegawai</title>
    <style>
        /* CSS untuk tampilan cetak yang rapi */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
        }

        .kop-surat img {
            width: 100%;
            max-width: 100%;
        }

        h2 {
            text-align: center;
            margin-top: 0;
            font-size: 16px;
        }

        .content {
            margin-top: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th, .table td {
            border: 1px solid #333;
            padding: 5px;
            font-size: 12px;
        }

        .table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .table td {
            text-align: center;
        }

        /* Spesifik untuk kolom "Nama" agar tetap rata kiri */
        .table td.name-column {
            text-align: left;
        }

    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="kop-surat">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/kop_surat.png'))) }}" alt="Kop Surat Perusahaan">
    </div>

    <!-- Judul Laporan -->
    <h2>Data Keluarga Pegawai</h2><br><br>

    <!-- Data Pegawai -->
    <strong>Nama Pegawai :</strong> {{ $records->first()->employeeFamilies->name ?? '-' }}
    
    <!-- Tabel Data Keluarga -->
    <div class="content">
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Status Keluarga</th>
                    <th>NIK</th>
                    <th>Jenis Kelamin</th>
                    <th>Tempat Tanggal Lahir</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $record)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="name-column">{{ $record->name }}</td> <!-- Kolom Nama -->
                        <td>{{ $record->families->name ?? '-' }}</td>
                        <td>{{ $record->id_number }}</td>
                        <td>{{ $record->gender }}</td>
                        <td>{{ $record->place_birth }}, {{ \Carbon\Carbon::parse($record->date_birth)->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>