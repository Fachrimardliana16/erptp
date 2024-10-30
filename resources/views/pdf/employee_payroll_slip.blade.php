<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji Pegawai</title>
    <style>
        @page {
            size: A4 landscape;
            /* Set the page size to landscape */
            margin: 0;
            /* Remove default margin */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 5px;
        }

        .container {
            width: 45%;
            /* Lebar container menjadi 48% */
            margin: 0 auto;
            float: left;
            /* Agar container berada di kiri */
            padding: 5px;
            border: 1px solid #000;
            /* Border untuk container */
        }

        .title {
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 20px;
            padding-left: 10px;
        }

        .section {
            width: 100%;
            margin-bottom: 10px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 10px;
            text-decoration: underline;
        }

        table {
            width: 100%;
            /* Lebar tabel menjadi 46% */
            border-collapse: collapse;
            margin-bottom: 10px;
            border: 1px solid #000;
            /* Border untuk tabel */
            padding: 10px;
        }

        th,
        td {
            padding-left: 10px;
            text-align: left;
            /* Rata kiri untuk isi slip gaji */
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
        }

        .align-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="title">SLIP GAJI PEGAWAI<br>PERUMDAM TIRTA PERWIRA<br>KAB. PURBALINGGA<br>Periode:
            {{ $payroll->periode }}</div>

        <!-- Upper section: Employee identity and salary -->
        <div class="section">
            <table>
                <tr>
                    <th>Nama</th>
                    <td>{{ $payroll->employee->name }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $payroll->status->name }}</td>
                </tr>
                <tr>
                    <th>Golongan</th>
                    <td>{{ $payroll->grade->name }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td>{{ $payroll->position->name }}</td>
                </tr>
            </table>

            <table>
                <tr>
                    <th>GAJI POKOK DAN TUNJANGAN</th>
                    <td></td>
                </tr>
                <tr>
                    <th>Gaji Pokok</th>
                    <td class="align-right">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Tunjangan Keluarga</th>
                    <td class="align-right">Rp {{ number_format($payroll->benefits_1, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Tunjangan Beras</th>
                    <td class="align-right">Rp {{ number_format($payroll->benefits_2, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Tunjangan Jabatan</th>
                    <td class="align-right">Rp {{ number_format($payroll->benefits_3, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Tunjangan Kesehatan</th>
                    <td class="align-right">Rp {{ number_format($payroll->benefits_4, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Tunjangan Air</th>
                    <td class="align-right">Rp {{ number_format($payroll->benefits_5, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Tunjangan DPLK</th>
                    <td class="align-right">Rp {{ number_format($payroll->benefits_6, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Lain-lain</th>
                    <td class="align-right">Rp {{ number_format($payroll->benefits_7, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Pembulatan</th>
                    <td class="align-right">Rp {{ number_format($payroll->rounding, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Insentif</th>
                    <td class="align-right">Rp {{ number_format($payroll->incentive, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Rapel</th>
                    <td class="align-right">Rp {{ number_format($payroll->backpay, 0, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <th>Total Bruto</th>
                    <td class="align-right">Rp {{ number_format($payroll->gross_amount, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Lower section: Salary deductions and total -->
        <div class="section">
            <table>
                <tr>
                    <th>POTONGAN</th>
                    <td></td>
                </tr>
                <tr>
                    <th>Absen</th>
                    <td class="align-right">{{ $payroll->absence_count }} Hari</td>
                </tr>
                <tr>
                    <th>Tabungan Daging</th>
                    <td class="align-right">Rp {{ number_format($payroll->paycut_1, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Rokok</th>
                    <td class="align-right">Rp {{ number_format($payroll->paycut_2, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Arisan Sumber</th>
                    <td class="align-right">Rp {{ number_format($payroll->paycut_3, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Cendera Mata</th>
                    <td class="align-right">Rp {{ number_format($payroll->paycut_4, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>ASTEK</th>
                    <td class="align-right">Rp {{ number_format($payroll->paycut_5, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>BPJS</th>
                    <td class="align-right">Rp {{ number_format($payroll->paycut_6, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Tabungan Koperasi</th>
                    <td class="align-right">Rp {{ number_format($payroll->paycut_7, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Dapenma</th>
                    <td class="align-right">Rp {{ number_format($payroll->paycut_8, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Dana Sosial Koperasi</th>
                    <td class="align-right">Rp {{ number_format($payroll->paycut_9, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Potongan Absensi</th>
                    <td class="align-right">Rp {{ number_format($payroll->paycut_10, 0, ',', '.') }}</td>
                </tr>
                <tr class="total">
                    <th>Jumlah Potongan</th>
                    <td class="align-right">Rp {{ number_format($payroll->cut_amount, 0, ',', '.') }}</td>
                </tr>
            </table>
            <table>
                <tr class="desc">
                    <th>Keterangan Potongan</th>
                    <td class="align-right">{{ $payroll->desc }}</td>
                </tr>
                <tr class="total">
                    <th>TOTAL PENERIMAAN</th>
                    <td class="align-right">Rp {{ number_format($payroll->netto, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>
