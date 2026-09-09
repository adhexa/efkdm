<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Dokumen #{{ $report->report_number }} - e-FKDM</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
            margin: 20mm;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h3 { margin: 0; font-size: 14pt; text-transform: uppercase; }
        .header h2 { margin: 2px 0; font-size: 16pt; text-transform: uppercase; }
        .header p { margin: 0; font-size: 10pt; font-style: italic; }
        
        .title-doc {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14pt;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .num-doc {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 25px;
        }

        table.meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.meta td {
            padding: 4px 6px;
            vertical-align: top;
        }
        table.meta td.label {
            width: 25%;
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .content-box {
            text-align: justify;
            margin-bottom: 20px;
        }

        .signature {
            margin-top: 40px;
            float: right;
            width: 250px;
            text-align: center;
        }

        @media print {
            body { margin: 10mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="background: #fef08a; padding: 10px; text-align: center; font-weight: bold; margin-bottom: 20px; border: 1px solid #ca8a04;">
        [Mode Cetak Dokumen] - Tekan Ctrl+P atau gunakan tombol cetak browser.
    </div>

    <!-- Kop Surat Resmi -->
    <div class="header">
        <h3>FORUM KEWASPADAAN DINI MASYARAKAT (FKDM)</h3>
        <h2>BADAN KESATUAN BANGSA DAN POLITIK</h2>
        <p>Sistem Informasi Deteksi Dini & Pelaporan Potensi Kerawanan Daerah (e-FKDM)</p>
    </div>

    <div class="title-doc">LAPORAN KEJADIAN DETEKSI DINI</div>
    <div class="num-doc">NOMOR: {{ $report->report_number }}</div>

    <table class="meta">
        <tr>
            <td class="label">PERISTIWA / ISU</td>
            <td>: <strong>{{ $report->title }}</strong></td>
        </tr>
        <tr>
            <td class="label">KATEGORI ISU</td>
            <td>: {{ $report->category->name }}</td>
        </tr>
        <tr>
            <td class="label">TINGKAT KERAWANAN</td>
            <td>: <strong>{{ strtoupper($report->risk_label) }}</strong></td>
        </tr>
        <tr>
            <td class="label">WAKTU KEJADIAN</td>
            <td>: {{ $report->incident_date->format('d F Y - H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="label">LOKASI / WILAYAH</td>
            <td>: {{ $report->address }}, {{ $report->village }}, {{ $report->subdistrict }}</td>
        </tr>
        <tr>
            <td class="label">NAMA PELAPOR</td>
            <td>: {{ $report->reporter_name }}</td>
        </tr>
        <tr>
            <td class="label">STATUS PENANGANAN</td>
            <td>: {{ strtoupper($report->status_label) }}</td>
        </tr>
    </table>

    <div class="section-title">I. URAIAN / KRONOLOGI PERISTIWA</div>
    <div class="content-box">
        {{ $report->chronology }}
    </div>

    <div class="section-title">II. CATATAN & TINDAK LANJUT PETUGAS FKDM</div>
    <div class="content-box">
        @forelse($report->actions as $action)
            <p style="margin-bottom: 8px;">
                <strong>[{{ $action->created_at->format('d/m/Y H:i') }} WIB - {{ $action->user->name ?? 'Petugas' }}]:</strong><br>
                {{ $action->note }}
            </p>
        @empty
            <p><em>Belum ada catatan tindak lanjut tambahan dari petugas lapangan.</em></p>
        @endforelse
    </div>

    <div class="signature">
        <p>Dicetak Pada: {{ date('d F Y') }}</p>
        <p><strong>PETUGAS / TIM FKDM</strong></p>
        <br><br><br><br>
        <p><strong>( ............................................ )</strong></p>
    </div>

</body>
</html>
