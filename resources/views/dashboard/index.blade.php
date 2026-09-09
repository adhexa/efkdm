@extends('layouts.app')

@section('title', 'Dashboard Deteksi Dini & Peta Kerawanan - e-FKDM')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header & Welcome -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 dark:text-white">Dashboard Monitoring e-FKDM</h1>
                <span class="text-xs px-2.5 py-1 rounded-full bg-brand-500/20 text-brand-700 dark:text-brand-300 border border-brand-500/30 font-bold">
                    {{ Auth::check() ? Auth::user()->role_badge : 'Mode Publik Masyakarat' }}
                </span>
            </div>
            <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm mt-1 font-medium">Pemantauan situasi kewaspadaan dini, peta sebaran titik rawan, dan status penanganan laporan.</p>
        </div>

        @guest
            <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-xs text-amber-900 dark:text-amber-300 flex items-center gap-3">
                <i data-lucide="info" class="w-5 h-5 text-amber-500 shrink-0"></i>
                <div class="min-w-0">
                    <strong class="block text-slate-900 dark:text-white font-bold">Mode Publik Masyakarat</strong>
                    <span class="text-[11px]">Informasi umum non-sensitif. Silakan login untuk fitur petugas.</span>
                </div>
                <a href="{{ route('login') }}" class="px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shrink-0 transition-all shadow-sm">
                    Login Petugas
                </a>
            </div>
        @else
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-red-600 to-amber-600 hover:from-red-500 hover:to-amber-500 text-white font-bold text-sm shadow-lg shadow-red-600/20 transition-all hover:scale-105">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>Buat Laporan Baru</span>
                </a>
            </div>
        @endguest
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="glass-panel p-4 rounded-2xl border border-slate-200 dark:border-slate-800">
            <p class="text-xs text-slate-600 dark:text-slate-400 font-bold">Total Laporan</p>
            <p class="text-2xl font-heading font-bold text-slate-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
            <span class="text-[10px] text-slate-500 font-semibold">Semua Wilayah</span>
        </div>
        <div class="glass-panel p-4 rounded-2xl border border-amber-500/40 dark:border-amber-500/30 bg-amber-500/10 dark:bg-amber-500/5">
            <p class="text-xs text-amber-700 dark:text-amber-400 font-bold">Menunggu Verifikasi</p>
            <p class="text-2xl font-heading font-bold text-amber-800 dark:text-amber-300 mt-1">{{ $stats['pending'] }}</p>
            <span class="text-[10px] text-amber-700 dark:text-amber-500/80 font-bold">Perlu Peninjauan</span>
        </div>
        <div class="glass-panel p-4 rounded-2xl border border-purple-500/40 dark:border-purple-500/30 bg-purple-500/10 dark:bg-purple-500/5">
            <p class="text-xs text-purple-700 dark:text-purple-400 font-bold">Dalam Penanganan</p>
            <p class="text-2xl font-heading font-bold text-purple-800 dark:text-purple-300 mt-1">{{ $stats['in_progress'] }}</p>
            <span class="text-[10px] text-purple-700 dark:text-purple-500/80 font-bold">Tim di Lapangan</span>
        </div>
        <div class="glass-panel p-4 rounded-2xl border border-emerald-500/40 dark:border-emerald-500/30 bg-emerald-500/10 dark:bg-emerald-500/5">
            <p class="text-xs text-emerald-700 dark:text-emerald-400 font-bold">Selesai Ditangani</p>
            <p class="text-2xl font-heading font-bold text-emerald-800 dark:text-emerald-300 mt-1">{{ $stats['resolved'] }}</p>
            <span class="text-[10px] text-emerald-700 dark:text-emerald-500/80 font-bold">Kondusif</span>
        </div>
        <div class="glass-panel p-4 rounded-2xl border border-red-500/40 dark:border-red-500/30 bg-red-500/10 dark:bg-red-500/5">
            <p class="text-xs text-red-700 dark:text-red-400 font-bold">Risiko Merah (Bahaya)</p>
            <p class="text-2xl font-heading font-bold text-red-800 dark:text-red-400 mt-1">{{ $stats['red_alerts'] }}</p>
            <span class="text-[10px] text-red-700 dark:text-red-500/80 font-bold">Prioritas Utama</span>
        </div>
        <div class="glass-panel p-4 rounded-2xl border border-amber-500/40 dark:border-amber-500/30 bg-amber-500/10 dark:bg-amber-500/5">
            <p class="text-xs text-amber-700 dark:text-amber-400 font-bold">Risiko Kuning (Waspada)</p>
            <p class="text-2xl font-heading font-bold text-amber-800 dark:text-amber-400 mt-1">{{ $stats['yellow_alerts'] }}</p>
            <span class="text-[10px] text-amber-700 dark:text-amber-500/80 font-bold">Monitoring Rutin</span>
        </div>
    </div>

    <!-- Map & Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Interactive Leaflet Map (2 Cols) -->
        <div class="lg:col-span-2 glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="map" class="w-5 h-5 text-amber-500 dark:text-amber-400"></i>
                        Peta GIS Sebaran Potensi Kerawanan
                    </h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 font-medium">Titik koordinat lokasi insiden laporan masyarakat & FKDM</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-semibold text-slate-700 dark:text-slate-300">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-500"></span> Bahaya</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-amber-500"></span> Waspada</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Aman</span>
                </div>
            </div>

            <div id="incidentMap" class="w-full h-[400px] rounded-2xl z-10 border border-slate-300 dark:border-slate-700/80"></div>
        </div>

        <!-- Chart Analytics (1 Col) -->
        <div class="glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 flex flex-col justify-between">
            <div>
                <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white mb-1 flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-5 h-5 text-indigo-600 dark:text-brand-400"></i>
                    Distribusi Kategori Isu
                </h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 font-medium mb-6">Persentase isu kerawanan daerah</p>
            </div>
            
            <div class="relative w-full h-[280px] flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-800 text-xs text-center text-slate-600 dark:text-slate-400 font-medium">
                Data real-time diperbarui otomatis
            </div>
        </div>
    </div>

    <!-- Filter & Table Section (Auth Officers Only) -->
    @auth
        <div class="glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="font-heading font-bold text-xl text-slate-900 dark:text-white">Daftar Laporan Deteksi Dini</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 font-medium">Tinjau dan kelola seluruh Laporan Kejadian di lapangan</p>
                </div>

                <!-- Search & Filters Form -->
                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari judul, nomor..."
                           class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-amber-500 font-medium">

                    @if(Auth::user()->role !== 'vendor_admin' && !empty(Auth::user()->district))
                        <div class="px-3.5 py-2 rounded-xl bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-200 font-bold flex items-center justify-between">
                            <span class="truncate">📍 {{ Auth::user()->district }}</span>
                        </div>
                    @else
                        <select name="district" class="px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-300 focus:outline-none focus:border-amber-500 font-medium">
                            <option value="">Semua Kabupaten / Kota</option>
                            @foreach($districts as $dst)
                                <option value="{{ $dst }}" {{ request('district') == $dst ? 'selected' : '' }}>🏛️ {{ $dst }}</option>
                            @endforeach
                        </select>
                    @endif

                    <select name="risk_level" class="px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-300 focus:outline-none focus:border-amber-500 font-medium">
                        <option value="">Semua Risiko</option>
                        <option value="red" {{ request('risk_level') == 'red' ? 'selected' : '' }}>Merah (Bahaya)</option>
                        <option value="yellow" {{ request('risk_level') == 'yellow' ? 'selected' : '' }}>Kuning (Waspada)</option>
                        <option value="green" {{ request('risk_level') == 'green' ? 'selected' : '' }}>Hijau (Aman)</option>
                    </select>

                    <select name="status" class="px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-300 focus:outline-none focus:border-amber-500 font-medium">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Dalam Penanganan</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    
                    <button type="submit" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-sm">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 uppercase tracking-wider bg-slate-100/80 dark:bg-slate-900/50 font-bold whitespace-nowrap">
                            <th class="p-4 rounded-l-xl">No. Laporan</th>
                            <th class="p-4">Judul & Kategori</th>
                            <th class="p-4">Lokasi & Wilayah</th>
                            <th class="p-4">Tanggal Kejadian</th>
                            <th class="p-4 text-right rounded-r-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800/80">
                        @forelse($reports as $report)
                            <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="p-4 font-mono font-bold text-amber-600 dark:text-amber-400 whitespace-nowrap">
                                    {{ $report->report_number }}
                                </td>
                                <td class="p-4">
                                    <p class="font-bold text-slate-900 dark:text-slate-200 text-sm truncate max-w-sm mb-1.5">{{ $report->title }}</p>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold bg-slate-100 dark:bg-slate-800/80 px-2 py-0.5 rounded-md border border-slate-200 dark:border-slate-700/60">{{ $report->category->name }}</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-bold text-[10px] border {{ $report->risk_badge_class }}">
                                            {{ $report->risk_label }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-bold text-[10px] border {{ $report->status_badge_class }}">
                                            {{ $report->status_label }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <p class="text-slate-800 dark:text-slate-300 font-semibold">{{ $report->village }}</p>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-500 font-medium">{{ $report->subdistrict }}</span>
                                </td>
                                <td class="p-4 text-slate-600 dark:text-slate-400 font-medium whitespace-nowrap">
                                    {{ $report->incident_date->format('d M Y, H:i') }} WIB
                                </td>
                                <td class="p-4 text-right whitespace-nowrap">
                                    <a href="{{ route('reports.show', $report->id) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-500/10 hover:bg-amber-500 dark:bg-amber-500/10 dark:hover:bg-amber-500 text-amber-600 hover:text-slate-950 dark:text-amber-400 dark:hover:text-slate-950 border border-amber-500/30 font-bold text-xs transition-all shadow-sm">
                                        <i data-lucide="eye" class="w-4 h-4"></i> Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-slate-500">
                                    tidak ada data laporan yang cocok dengan kriteria filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-4">
                {{ $reports->links() }}
            </div>
        </div>
    @endauth

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Leaflet Map Initialization
        const map = L.map('incidentMap').setView([-6.2088, 106.8456], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        const mapData = @json($mapReports);

        mapData.forEach(rep => {
            let color = '#3b82f6';
            if (rep.risk_level === 'red') color = '#ef4444';
            if (rep.risk_level === 'yellow') color = '#f59e0b';
            if (rep.risk_level === 'green') color = '#10b981';

            const marker = L.circleMarker([rep.latitude, rep.longitude], {
                radius: 9,
                fillColor: color,
                color: '#ffffff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map);

            marker.bindPopup(`
                <div style="font-family: sans-serif; color: #0f172a; padding: 4px;">
                    <strong style="color: ${color}; font-size: 11px;">[${rep.report_number}]</strong><br>
                    <strong style="font-size: 13px;">${rep.title}</strong><br>
                    <span style="font-size: 11px; color: #475569;">${rep.village}, ${rep.subdistrict}</span><br>
                    <a href="/reports/${rep.id}" style="display:inline-block; margin-top:6px; font-size:11px; font-weight:bold; color:#4f46e5; text-decoration:none;">Buka Detail Laporan &rarr;</a>
                </div>
            `);
        });

        // 2. Chart.js Category Analytics Initialization
        const ctx = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chartCategoryLabels),
                datasets: [{
                    data: @json($chartCategoryData),
                    backgroundColor: [
                        '#f59e0b',
                        '#ef4444',
                        '#dc2626',
                        '#7c3aed',
                        '#2563eb',
                        '#10b981'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            font: { size: 10 },
                            boxWidth: 12
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endpush
