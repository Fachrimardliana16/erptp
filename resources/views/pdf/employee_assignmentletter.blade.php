<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0 1cm;
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
        h4, p.center {
            text-align: center;
            margin: 0;
        }
        p, td {
            text-align: left;
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
        }
        th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .signature {
            text-align: center;
            margin-top: 20px;
            padding-left: 40%;
        }
        .signature p {
            text-align: center;
            margin: 5px 0;
        }
        .signature .signature-line {
            margin-top: 85px;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Kop surat dengan gambar Base64 -->
    <div class="kop-surat">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/kop_surat.png'))) }}" alt="Kop Surat Perusahaan">
    </div>
    <!-- Nomor Surat -->
    <h4 class="center"><u>SURAT TUGAS</u></h4>
    <p class="center">Nomor: {{ $surat_tugas->registration_number }}</p><br><br>

    <!-- Pembuka -->
    <p>Yang bertanda tangan dibawah ini:
        <br>Nama : {{ $surat_tugas->aassigningEmployee->name }}
        <br>Jabatan : {{ $surat_tugas->positionAssign->name }}<br>
    <br>Dengan ini menugaskan kepada:
    <br>Nama    : {{ $surat_tugas->assignedEmployee->name }}
    <!-- Isi Surat Tugas -->
    <br>
    <br>Untuk melaksanakan tugas {{ $surat_tugas->task }} mulai tanggal {{ \Carbon\Carbon::parse($surat_tugas->start_date)->format('d F Y') }} sampai dengan {{ \Carbon\Carbon::parse($surat_tugas->end_date)->format('d F Y') }}.
    
    <!-- Penutup -->
    <br>
    <br>Demikian untuk menjadikan periksa dan dilaksanakan dengan sebaik-baiknya.<br>

    <!-- Tanda tangan di bawah tabel -->
    <div class="signature">
        <p>Purbalingga, {{ \Carbon\Carbon::now()->format('d F Y') }}</p><br>
        <p>Mengetahui,
        <br>PERUMDA Air Minum Tirta Perwira
        <br>Kabupaten Purbalingga
        <br>{{ $surat_tugas->positionAssign->name }}
        </p>
        <div class="signature-line">
            <p><strong>{{ $surat_tugas->aassigningEmployee->name }}</strong></p>
        </div>
    </div>
</body>
</html>