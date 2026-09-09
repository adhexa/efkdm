@extends('layouts.app')

@section('title', 'Penyusun Laporan Monev Pemda - e-FKDM')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="glass-panel p-6 sm:p-10 rounded-3xl border border-slate-800 shadow-2xl space-y-8">
        
        <div class="border-b border-slate-800 pb-6">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 border border-amber-500/30 text-amber-300 text-xs font-semibold uppercase tracking-wider mb-2">
                <i data-lucide="file-signature" class="w-3.5 h-3.5"></i>
                Instrumen Fasilitator Swasta untuk Pemerintah Daerah
            </div>
            <h1 class="font-heading font-extrabold text-3xl text-white">Susun Paket Laporan Evaluasi e-Monev Pemda</h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Kompilasi data mentah laporan kerawanan & indikator IKU menjadi dokumen Laporan Eksekutif Resmi untuk Kesbangpol & Pimpinan Daerah.</p>
        </div>

        <form action="{{ route('monev.store_builder') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Title & Period -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Judul Laporan Evaluasi Eksekutif <span class="text-red-400">*</span></label>
                    <input type="text" name="title" value="{{ old('title', 'Laporan Hasil Monitoring & Evaluasi Triwulan III Kinerja FKDM & Peta Kerawanan Pemda') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nama Periode Evaluasi <span class="text-red-400">*</span></label>
                    <input type="text" name="period_name" value="{{ old('period_name', 'Triwulan III - 2026') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <!-- Target Region & Target Reports -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Pemerintah Daerah / Wilayah Sasaran</label>
                    <input type="text" name="target_region" value="{{ old('target_region', 'Pemerintah Daerah Kabupaten/Kota') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Target Minimal Laporan (IKU)</label>
                    <input type="number" name="total_target_reports" value="{{ old('total_target_reports', 60) }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Realisasi Laporan Terverifikasi</label>
                    <input type="number" name="total_realized_reports" value="{{ old('total_realized_reports', $totalReports) }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Tingkat Kepatuhan & Penyelesaian (%)</label>
                    <input type="number" step="0.1" name="compliance_rate" value="{{ old('compliance_rate', $complianceRate) }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-amber-400 font-mono text-sm focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Skor Indeks Kerawanan Wilayah (IKW) (0-100)</label>
                    <input type="number" step="0.1" name="risk_index_score" value="{{ old('risk_index_score', $riskIndexScore) }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-red-400 font-mono text-sm focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <!-- Executive Summary -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Ringkasan Eksekutif Hasil Monev <span class="text-red-400">*</span></label>
                <textarea name="executive_summary" rows="4" required
                          class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-amber-500"
                          placeholder="Tuliskan analisis komprehensif mengenai situasi ketertiban, titik rawan dominan, dan kinerja FKDM selama periode berjalan..."></textarea>
            </div>

            <!-- Consultant Recommendations -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Rekomendasi Kebijakan untuk Pimpinan Pemda <span class="text-red-400">*</span></label>
                <textarea name="consultant_recommendations" rows="5" required
                          class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:outline-none focus:border-amber-500"
                          placeholder="Rekomendasi taktis dan strategis dari Konsultan Swasta (Misal: Alokasi operasional, patroli sinergis Kesbangpol/TNI/POLRI, perbaikan infrastruktur sungai)..."></textarea>
            </div>

            <button type="submit" class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-extrabold text-base shadow-xl shadow-amber-500/20 transition-all hover:scale-[1.01] active:scale-95">
                Simpan & Publikasikan Paket Dokumen e-Monev Pemda
            </button>
        </form>
    </div>

</div>
@endsection
