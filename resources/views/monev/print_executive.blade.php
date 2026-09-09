<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan e-Monev Pemda #{{ $evaluation->evaluation_code }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
            margin: 20mm;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h3 { margin: 0; font-size: 13pt; text-transform: uppercase; }
        .header h2 { margin: 2px 0; font-size: 15pt; text-transform: uppercase; }
        .header p { margin: 0; font-size: 10pt; font-style: italic; }
        
        .title-doc {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 13pt;
            text-decoration: underline;
            margin-bottom: 4px;
        }
        .num-doc {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table.grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        table.grid th, table.grid td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 10pt;
        }
        table.grid th {
            background-color: #f2f2f2;
            text-transform: uppercase;
        }

        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 11pt;
        }

        .content-box {
            text-align: justify;
            margin-bottom: 15px;
            font-size: 11pt;
        }

        .signature-table {
            width: 100%;
            margin-top: 40px;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            width: 50%;
        }

        @media print {
            body { margin: 10mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="background: #fef08a; padding: 10px; text-align: center; font-weight: bold; margin-bottom: 20px; border: 1px solid #ca8a04;">
        [Mode Cetak Dokumen e-Monev Pemda] - Tekan Ctrl+P atau gunakan tombol cetak browser.
    </div>

    <!-- Kop Surat Resmi Fasilitator Swasta & Pemda -->
    <div class="header">
        <h3>PEMERINTAH DAERAH KABUPATEN / KOTA</h3>
        <h2>BADAN KESATUAN BANGSA DAN POLITIK (KESBANGPOL)</h2>
        <p>Laporan Hasil Penyusunan Monitoring & Evaluasi FKDM Fasilitasi {{ $evaluation->user->institution_name ?? 'Konsultan Swasta' }}</p>
    </div>

    <div class="title-doc">{{ strtoupper($evaluation->title) }}</div>
    <div class="num-doc">DOKUMEN E-MONEV KODE: {{ $evaluation->evaluation_code }} | PERIODE: {{ strtoupper($evaluation->period_name) }}</div>

    <div class="section-title">I. CAPAIAN INDIKATOR KINERJA UTAMA (IKU) & MONEV</div>
    <table class="grid">
        <thead>
            <tr>
                <th>Indikator Kinerja</th>
                <th>Target Minimal</th>
                <th>Capaian Realisasi</th>
                <th>Persentase Kepatuhan</th>
                <th>Skor Indeks Kerawanan (IKW)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pelaporan Deteksi Dini FKDM</td>
                <td style="text-align:center;">{{ $evaluation->total_target_reports }} Laporan</td>
                <td style="text-align:center;">{{ $evaluation->total_realized_reports }} Laporan</td>
                <td style="text-align:center;"><strong>{{ $evaluation->compliance_rate }}%</strong></td>
                <td style="text-align:center;"><strong>{{ $evaluation->risk_index_score }} / 100</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">II. REKAPITULASI KEPATUHAN FKDM PER KECAMATAN</div>
    <table class="grid">
        <thead>
            <tr>
                <th>Kecamatan</th>
                <th>Total Laporan Masuk</th>
                <th>Jumlah Risiko Tinggi (Merah)</th>
                <th>Laporan Selesai Ditangani</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subdistrictStats as $sub)
                <tr>
                    <td>{{ $sub->subdistrict }}</td>
                    <td style="text-align:center;">{{ $sub->total_reports }}</td>
                    <td style="text-align:center;">{{ $sub->red_reports }}</td>
                    <td style="text-align:center;">{{ $sub->resolved_reports }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">III. RINGKASAN EKSEKUTIF MONEV</div>
    <div class="content-box">
        {{ $evaluation->executive_summary }}
    </div>

    <div class="section-title">IV. REKOMENDASI KEBIJAKAN FASILITATOR SWASTA / KONSULTAN</div>
    <div class="content-box" style="white-space: pre-line;">
        {{ $evaluation->consultant_recommendations }}
    </div>

    <!-- Tanda Tangan Konsultan & Kepala Kesbangpol -->
    <table class="signature-table">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>KEPALA BADAN KESBANGPOL PEMDA</strong>
                <br><br><br><br><br>
                ( .................................................... )
            </td>
            <td>
                Disusun & Difasilitasi Oleh,<br>
                <strong>FASILITATOR SWASTA / KONSULTAN</strong><br>
                <em>{{ $evaluation->user->institution_name ?? 'adhexa.id' }}</em>
                <br><br><br><br>
                ( {{ $evaluation->user->name }} )
            </td>
        </tr>
    </table>

</body>
</html>
