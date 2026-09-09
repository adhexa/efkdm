@extends('layouts.app')

@section('title', 'e-FKDM - Dashboard Monitoring & Evaluasi Pemda')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header & Vendor Facilitator Intro -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 glass-panel p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 shadow-xl">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/30 text-xs font-extrabold uppercase tracking-wider mb-2.5">
                <i data-lucide="building-2" class="w-3.5 h-3.5 text-indigo-500"></i>
                Fasilitasi Konsultan Swasta untuk Pemerintah Daerah
            </div>
            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 dark:text-white">
                Monitoring & Evaluasi e-FKDM Pemda
            </h1>
            <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm mt-1.5 font-medium leading-relaxed">
                Sistem Monitoring, Evaluasi, dan Pengukuran Indikator Kinerja Utama (IKU) FKDM per Wilayah Kabupaten / Kota.
            </p>
        </div>

        @if(Auth::check() && (Auth::user()->isVendorAdmin() || Auth::user()->isAdmin()))
            <a href="{{ route('monev.builder') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-amber-500 to-indigo-600 hover:from-amber-400 hover:to-indigo-500 text-white font-extrabold text-sm shadow-xl shadow-indigo-600/20 transition-all hover:scale-105 active:scale-95 shrink-0">
                <i data-lucide="file-plus" class="w-4.5 h-4.5"></i>
                <span>Susun Laporan Evaluasi Pemda</span>
            </a>
        @endif
    </div>

    @guest
        <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-900 dark:text-amber-300 text-xs flex items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center gap-3">
                <i data-lucide="shield-alert" class="w-5 h-5 text-amber-500 shrink-0"></i>
                <span><strong>Mode Publik Terbatas:</strong> Anda sedang meninjau ringkasan statistik e-Monev publik. Fitur pencetakan dan penyusunan dokumen evaluasi internal dikhususkan untuk pejabat Kesbangpol & FKDM.</span>
            </div>
            <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold shrink-0 transition-all shadow-sm">
                Login Petugas
            </a>
        </div>
    @endguest

    <!-- Executive IKU & Monev Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        
        <!-- Risk Index Score (IKW) -->
        <div class="glass-panel p-6 rounded-3xl border border-rose-200 dark:border-red-500/30 bg-rose-50/80 dark:bg-red-500/10 shadow-lg flex flex-col justify-between">
            <div>
                <span class="text-xs text-rose-700 dark:text-red-400 font-extrabold uppercase tracking-wider">Indeks Kerawanan Wilayah (IKW)</span>
                <div class="flex items-baseline gap-2 mt-3">
                    <span class="text-4xl font-heading font-extrabold text-rose-600 dark:text-red-400">{{ $riskIndexScore }}</span>
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">/ 100 Skala Risiko</span>
                </div>
            </div>
            <div class="mt-5 pt-3.5 border-t border-rose-200 dark:border-red-500/20 text-[11px] text-slate-700 dark:text-slate-300 font-medium">
                Status: <strong class="text-amber-800 dark:text-amber-400 bg-amber-100 dark:bg-amber-500/20 px-2 py-0.5 rounded-md font-bold">Waspada - Perlu Monitoring Pengetatan</strong>
            </div>
        </div>

        <!-- Resolution Rate -->
        <div class="glass-panel p-6 rounded-3xl border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50/80 dark:bg-emerald-500/10 shadow-lg flex flex-col justify-between">
            <div>
                <span class="text-xs text-emerald-800 dark:text-emerald-400 font-extrabold uppercase tracking-wider">Tingkat Penyelesaian Laporan</span>
                <div class="flex items-baseline gap-2 mt-3">
                    <span class="text-4xl font-heading font-extrabold text-emerald-600 dark:text-emerald-400">{{ $resolutionRate }}%</span>
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Selesai Ditangani</span>
                </div>
            </div>
            <div class="mt-5 pt-3.5 border-t border-emerald-200 dark:border-emerald-500/20 text-[11px] text-slate-700 dark:text-slate-300 font-bold">
                {{ $resolvedReports }} dari {{ $totalReports }} Laporan Berhasil Dinetralisir
            </div>
        </div>

        <!-- Target Realization -->
        <div class="glass-panel p-6 rounded-3xl border border-indigo-200 dark:border-indigo-500/30 bg-indigo-50/80 dark:bg-indigo-500/10 shadow-lg flex flex-col justify-between">
            <div>
                <span class="text-xs text-indigo-800 dark:text-indigo-300 font-extrabold uppercase tracking-wider">Total Laporan Terverifikasi</span>
                <div class="flex items-baseline gap-2 mt-3">
                    <span class="text-4xl font-heading font-extrabold text-indigo-600 dark:text-indigo-400">{{ $totalReports }}</span>
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Laporan Kejadian</span>
                </div>
            </div>
            <div class="mt-5 pt-3.5 border-t border-indigo-200 dark:border-indigo-500/20 text-[11px] text-slate-700 dark:text-slate-300 font-bold">
                Capaian Target Periode Berjalan
            </div>
        </div>

        <!-- Active Tim Monev -->
        <div class="glass-panel p-6 rounded-3xl border border-amber-200 dark:border-amber-500/30 bg-amber-50/80 dark:bg-amber-500/10 shadow-lg flex flex-col justify-between">
            <div>
                <span class="text-xs text-amber-800 dark:text-amber-400 font-extrabold uppercase tracking-wider">Pencegahan Konflik & Bencana</span>
                <div class="flex items-baseline gap-2 mt-3">
                    <span class="text-4xl font-heading font-extrabold text-amber-600 dark:text-amber-400">{{ $inProgressReports }}</span>
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Dalam Penanganan</span>
                </div>
            </div>
            <div class="mt-5 pt-3.5 border-t border-amber-200 dark:border-amber-500/20 text-[11px] text-slate-700 dark:text-slate-300 font-bold">
                Respon Cepat Tim FKDM Wilayah
            </div>
        </div>

    </div>

    <!-- Leaderboard & Subdistrict Matrix -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Subdistrict Compliance Table (2 Cols) -->
        <div class="lg:col-span-2 glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 shadow-xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
                <div>
                    <h3 class="font-heading font-extrabold text-lg text-slate-900 dark:text-white">Leaderboard Kepatuhan FKDM Per Kecamatan</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 font-medium">Evaluasi keaktifan dan tingkat penyelesaian laporan di setiap wilayah Pemda</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 uppercase font-extrabold tracking-wider bg-slate-100/90 dark:bg-slate-800/80">
                            <th class="p-3.5">Kecamatan</th>
                            <th class="p-3.5 text-center">Total Laporan</th>
                            <th class="p-3.5 text-center">Risiko Merah</th>
                            <th class="p-3.5 text-center">Tingkat Penyelesaian</th>
                            <th class="p-3.5 text-right">Status Kepatuhan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80 font-semibold text-slate-800 dark:text-slate-200">
                        @forelse($subdistrictStats as $sub)
                            @php
                                $rate = $sub->total_reports > 0 ? round(($sub->resolved_reports / $sub->total_reports) * 100) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="p-3.5 font-bold text-slate-900 dark:text-slate-100">{{ $sub->subdistrict }}</td>
                                <td class="p-3.5 text-center font-mono text-amber-600 dark:text-amber-400 font-extrabold text-sm">{{ $sub->total_reports }}</td>
                                <td class="p-3.5 text-center font-mono text-rose-600 dark:text-red-400 font-extrabold text-sm">{{ $sub->red_reports }}</td>
                                <td class="p-3.5 text-center">
                                    <div class="w-full bg-slate-200 dark:bg-slate-800 rounded-full h-2.5 overflow-hidden inline-block align-middle max-w-[100px] shadow-inner">
                                        <div class="bg-emerald-500 h-2.5 rounded-full" style="width: {{ $rate }}%;"></div>
                                    </div>
                                    <span class="ml-2.5 font-extrabold text-slate-900 dark:text-slate-200">{{ $rate }}%</span>
                                </td>
                                <td class="p-3.5 text-right">
                                    @if($rate >= 75)
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 font-extrabold border border-emerald-500/30">Sangat Baik</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-700 dark:text-amber-400 font-extrabold border border-amber-500/30">Monitoring</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-slate-500 font-medium">Belum ada data wilayah tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Issue Categories Breakdown (1 Col) -->
        <div class="glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 shadow-xl space-y-4">
            <h3 class="font-heading font-extrabold text-lg text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-3">
                Kategori Kerawanan e-FKDM
            </h3>

            <div class="space-y-3">
                @foreach($categories as $cat)
                    <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/80 flex items-center justify-between text-xs shadow-sm gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-3.5 h-3.5 rounded-full shadow-sm shrink-0" style="background-color: {{ $cat->color_code }};"></span>
                            <span class="font-extrabold text-slate-900 dark:text-slate-100 leading-snug">{{ $cat->name }}</span>
                        </div>
                        <span class="font-extrabold text-amber-600 dark:text-amber-400 font-mono text-xs sm:text-sm shrink-0 whitespace-nowrap">{{ $cat->reports_count }} Kejadian</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Published Executive Evaluation Reports -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 shadow-xl space-y-6">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
            <div>
                <h3 class="font-heading font-extrabold text-xl text-slate-900 dark:text-white">Paket Laporan Evaluasi Eksekutif Pemda</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 font-medium mt-1">Dokumen evaluasi berseri hasil pengolahan & rekomendasi fasilitator swasta</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($evaluations as $eval)
                <div class="glass-panel p-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 flex flex-col justify-between hover:border-amber-500/50 transition-all shadow-md">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs text-amber-600 dark:text-amber-400 font-extrabold">{{ $eval->evaluation_code }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/30">
                                {{ $eval->period_name }}
                            </span>
                        </div>

                        <h4 class="font-heading font-extrabold text-lg text-slate-900 dark:text-white leading-snug">{{ $eval->title }}</h4>
                        <p class="text-slate-600 dark:text-slate-400 text-xs line-clamp-3 leading-relaxed font-medium">{{ $eval->executive_summary }}</p>
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Oleh: <strong class="text-slate-900 dark:text-slate-200 font-bold">{{ $eval->user->institution_name ?? $eval->user->name }}</strong></span>
                        @auth
                            <a href="{{ route('monev.print_executive', $eval->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold transition-all shadow-sm">
                                <i data-lucide="printer" class="w-3.5 h-3.5"></i> Cetak Laporan Pemda
                            </a>
                        @else
                            <span class="text-xs text-amber-600 dark:text-amber-400 font-bold italic bg-amber-500/10 px-3 py-1.5 rounded-lg border border-amber-500/20">
                                🔒 Laporan Resmi Pemda
                            </span>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-12 glass-panel rounded-2xl border border-slate-200 dark:border-slate-800">
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Belum ada paket laporan evaluasi eksekutif yang disiapkan.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
