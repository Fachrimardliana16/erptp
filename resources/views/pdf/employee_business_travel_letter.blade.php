<!DOCTYPE html>
<html>
<head>
    <title>Surat Perintah Perjalanan Dinas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .header { text-align: center; margin-bottom: 20px; }
        .content { margin-bottom: 20px; }
        .footer { margin-top: 30px; }
        table { width: 100%; }
        td { padding: 5px; vertical-align: top; }
        .label { width: 30%; }
        .colon { width: 5px; text-align: left; }
        .value { width: 65%; }
        .section-title { font-weight: bold; margin-top: 15px; }
        .signature { margin-top: 40px; text-align: right; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h4>PEMERINTAH KABUPATEN PURBALINGGA</h4>
        <h4>PERUSAHAAN UMUM DAERAH AIR MINUM</h4>
        <h4>TIRTA PERWIRA</h4>
        <p>Jl.Let.Jend.S.Parman No.62 Purbalingga Telp. (0281) 891706 Fax. (0281) 895534</p>
        <hr>
        <h4>SURAT PERINTAH PERJALANAN DINAS</h4>
        <p>No.: {{ $surat_tugas->registration_number }}</p>
    </div>

    <div class="content">
        <p class="section-title">DIPERINTAHKAN KEPADA:</p>
        <table>
            <tr>
                <td class="label">1. NAMA</td>
                <td class="colon">:</td>
                <td class="value">{{ $surat_tugas->businessTravelEmployee->name }}</td>
            </tr>
            <tr>
                <td class="label">2. JABATAN/PANGKAT</td>
                <td class="colon">:</td>
                <td class="value">{{ $surat_tugas->businessTravelEmployee->position ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">3. NAMA TEMPAT YANG DITUJU</td>
                <td class="colon">:</td>
                <td class="value">{{ $surat_tugas->destination }}</td>
            </tr>
            <tr>
                <td class="label">4. UNTUK SELAMA WAKTU</td>
                <td class="colon">:</td>
                <td class="value">{{ \Carbon\Carbon::parse($surat_tugas->start_date)->diffInDays(\Carbon\Carbon::parse($surat_tugas->end_date)) + 1 }} hari</td>
            </tr>
            <tr>
                <td class="label">5. PERJALANAN DINAS DIBIAYAI</td>
                <td class="colon">:</td>
                <td class="value">{{ $surat_tugas->business_trip_expenses }}</td>
            </tr>
            <tr>
                <td class="label">6. PASAL</td>
                <td class="colon">:</td>
                <td class="value">{{ $surat_tugas->pasal }}</td>
            </tr>
            <tr>
                <td class="label">7. PENGIKUT</td>
                <td class="colon">:</td>
                <td class="value">
                    @foreach ($surat_tugas->followers as $follower)
                        - {{ $follower->name }}<br>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td class="label">8. KETERANGAN LAIN-LAIN</td>
                <td class="colon">:</td>
                <td class="value">{{ $surat_tugas->description }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div class="signature">
            <p>Purbalingga, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <p class="center">Perumda Air Minum Tirta Perwira</p>
            <p class="center">Kabupaten Purbalingga</p>
            <br><br><br>
            <p class="center">{{ $surat_tugas->businessTravelEmployee->name }}</p>
            <p class="center">{{ $surat_tugas->businessTravelEmployee->position ?? 'Kepala Bagian' }}</p>
        </div>
    </div>
</body>
</html>