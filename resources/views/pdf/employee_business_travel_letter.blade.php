<!DOCTYPE html>
<html>

<head>
    <title>Surat Perintah Perjalanan Dinas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 5px;
            font-size: 11pt;
            line-height: 1.3;
        }

        .header {
            text-align: center;
            margin-bottom: 0;
            /* Reduced bottom margin */
            padding-bottom: 0;
            /* Removed padding */
        }

        .header-content {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .kop-surat {
            text-align: center;
            margin-bottom: 5px;
            /* Further reduced bottom margin */
        }

        .kop-surat img {
            max-width: 125%;
            /* Set to 100% to ensure it takes the full width of the container */
            height: auto;
            /* Maintain aspect ratio */
            margin-top: 0px;
            /* Adjust this value to raise the image further */
        }

        .content {
            margin-top: 5px;
        }

        table {
            width: 100%;
            /* Lebar tabel 100% dari kontainer */
            border-collapse: collapse;
            /* Menggabungkan border sel */
            margin: 0px 0;
            /* Margin atas dan bawah tabel */
        }

        th,
        td {
            padding: 2px;
            /* Menambahkan padding di dalam sel tabel */
            text-align: left;
            /* Mengatur teks di dalam sel menjadi rata kiri */
            border: 1px solid #ddd;
            /* Menambahkan border pada sel tabel */
        }

        th {
            background-color: #f2f2f2;
            /* Warna latar belakang untuk header tabel */
        }

        .col-follower-list {
            vertical-align: top;
        }

        .no-border td {
            border: none;
        }

        .travel-details {
            border: 1px solid black;
            margin: 10px 0;
        }

        .travel-details th,
        .travel-details td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            min-height: 100px;
            vertical-align: middle;
        }

        .travel-details2 {
            border: 1px solid black;
            margin: 10px 0;
        }

        .travel-details2 th {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            min-height: 100px;
            vertical-align: middle;
        }

        .travel-details2 td {
            border: 1px solid black;
            padding: 35px;
            text-align: center;
            min-height: 100px;
            vertical-align: middle;
        }

        .signature-container {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature-box p {
            margin: 5px 0;
            text-align: center;
        }

        .signature-space-left {
            height: 125px;
        }

        .signature-space-right {
            height: 105px;
        }

        .signature-place {
            width: 92%;
            text-align: right;
        }

        .follower-list {
            list-style-type: none;
            padding-left: 0;
            margin: 0;
        }

        .follower-list li {
            margin-bottom: 5px;
        }

        .registration-number {
            margin: 5px 0;
            font-size: 11pt;
        }

        .travel-details th {
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="kop-surat">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/kop_surat.png'))) }}"
                alt="Kop Surat Perusahaan">
        </div>


        <div class="content" style="margin: 0 2.5rem;">
            <div style="text-align: center;">
                <h4 style="margin: 2px 0; text-decoration: underline;">SURAT PERINTAH PERJALANAN DINAS</h4>
                <p class="registration-number">No.: {{ $surat_tugas->registration_number }}</p>
            </div>
            <br>
            <p style="text-align: left; margin: 5px 0;">DIPERINTAHKAN KEPADA</p>
            <table class="no-border" style="width: 100%;">
                <tr>
                    <td style="width: 2%">1.</td>
                    <td style="width: 47%">NAMA</td>
                    <td style="width: 2px">:</td>
                    <td>{{ $surat_tugas->businessTravelEmployee->name }}</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td style="width: 47%">JABATAN/PANGKAT</td>
                    <td>:</td>
                    <td>{{ $surat_tugas->businessTravelEmployee->employeePosition->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td style="width: 47%">NAMA TEMPAT YANG DITUJU</td>
                    <td>:</td>
                    <td>{{ $surat_tugas->destination }}</td>
                </tr>
            </table>

            <table class="travel-details" style="width: 100%;">
                <tr>
                    <th style="width: 50%">TEMPAT TUJUAN</th>
                    <th style="width: 50%">MAKSUD PERJALANAN</th>
                </tr>
                <tr>
                    <td>{{ $surat_tugas->destination_detail }}</td>
                    <td>{{ $surat_tugas->purpose_of_trip }}</td>
                </tr>
            </table>

            <table class="no-border" style="width: 100%;">
                <tr>
                    <td style="width: 2%">4.</td>
                    <td style="width: 47%">UNTUK SELAMA WAKTU</td>
                    <td style="width: 2px">:</td>
                    <td>{{ \Carbon\Carbon::parse($surat_tugas->start_date)->diffInDays(\Carbon\Carbon::parse($surat_tugas->end_date)) + 1 }}
                        hari</td>
                </tr>
            </table>

            <table class="travel-details2" style="width: 100%;">
                <tr>
                    <th style="width: 50%">BERANGKAT DAN KEMBALI TANGGAL</th>
                    <th style="width: 50%">CAP DAN TANDA TANGAN</th>
                </tr>
                <tr>
                    <td>{{ \Carbon\Carbon::parse($surat_tugas->start_date)->format('d F Y') }}
                        <br>-<br>
                        {{ \Carbon\Carbon::parse($surat_tugas->end_date)->format('d F Y') }}
                    </td>
                    <td></td>
                </tr>
            </table>

            <table class="no-border" style="width: 100%;">
                <tr>
                    <td style="width: 2%">5.</td>
                    <td style="width: 47%">PERJALANAN DINAS DIBIAYAI</td>
                    <td style="width: 2px">:</td>
                    <td>{{ $surat_tugas->business_trip_expenses }}</td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>PASAL</td>
                    <td>:</td>
                    <td>{{ $surat_tugas->pasal }}</td>
                </tr>
                <tr>
                    <td class="col-follower-list">7.</td>
                    <td style="width: 47%" class="col-follower-list">PENGIKUT</td>
                    <td class="col-follower-list">:</td>
                    <td>
                        <ol class="follower-list">
                            @foreach ($surat_tugas->followers as $index => $follower)
                                <li>{{ $index + 1 }}. {{ $follower->name }}</li>
                            @endforeach
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td>8.</td>
                    <td style="width: 47%">KETERANGAN LAIN-LAIN</td>
                    <td>:</td>
                    <td>{{ $surat_tugas->description }}</td>

                </tr>
            </table>
            <br>
            <div class="signature-place">Purbalingga, {{ \Carbon\Carbon::now()->format('d F Y') }}</div>
            <div class="signature-container">
                <div class="signature-box">
                    <p>Tanda Tangan<br>Pemegang</p>
                    <div class="signature-space-left"></div>
                    <p>{{ $surat_tugas->businessTravelEmployee->name }}</p>
                </div>
                <div class="signature-box">
                    <p>Perumda Air Minum<br>
                        Tirta Perwira Kabupaten Purbalingga<br>
                        {{ $surat_tugas->employeeSignatory->employeePosition->name }}</p>
                    <div class="signature-space-right"></div>
                    <p>{{ $surat_tugas->employeeSignatory->name }}</p>
                </div>
            </div>
        </div>
</body>

</html>
