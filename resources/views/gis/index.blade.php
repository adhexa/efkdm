@extends('layouts.app')

@section('title', 'Peta GIS Geospasial & Kerawanan Daerah - e-FKDM')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header & Welcome -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 glass-panel p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 shadow-xl">
        <div>
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/30 text-xs font-extrabold uppercase tracking-wider mb-2">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-indigo-500"></i>
                Portal Geospasial Publik se-Indonesia
            </div>
            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 dark:text-white">
                Peta GIS Kerawanan Daerah
            </h1>
            <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm mt-1.5 font-medium leading-relaxed">
                Pemantauan visual sebaran titik lokasi kejadian, tingkat risiko wilayah, dan analisis kategori kerawanan secara real-time.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            @auth
                <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-400 hover:from-amber-400 hover:to-amber-300 text-slate-950 font-extrabold text-sm shadow-xl shadow-amber-500/20 transition-all hover:scale-105 active:scale-95">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>Input Laporan Kejadian (FKDM)</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 text-white font-extrabold text-sm shadow-xl transition-all hover:scale-105 active:scale-95">
                    <i data-lucide="shield-check" class="w-4 h-4 text-amber-500"></i>
                    <span>Login Anggota FKDM</span>
                </a>
            @endauth
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="glass-panel p-4 sm:p-5 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/90 dark:bg-slate-900/90 shadow-lg">
        <form action="{{ route('gis') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2 font-bold text-xs text-slate-700 dark:text-slate-300 uppercase tracking-wider px-2">
                <i data-lucide="filter" class="w-4 h-4 text-amber-500"></i>
                <span>Filter Wilayah:</span>
            </div>

            @if(Auth::check() && Auth::user()->role !== 'vendor_admin' && !empty(Auth::user()->district))
                <div class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-200 font-extrabold flex items-center justify-between min-w-[200px]">
                    <span class="truncate">📍 {{ Auth::user()->district }}</span>
                </div>
            @else
                <select name="district" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500 font-bold min-w-[220px]">
                    <option value="">🏛️ Semua Kabupaten / Kota</option>
                    @foreach($districts as $dst)
                        <option value="{{ $dst }}" {{ request('district') == $dst ? 'selected' : '' }}>🏛️ {{ $dst }}</option>
                    @endforeach
                </select>
            @endif

            <select name="category_id" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500 font-medium">
                <option value="">Semua Kategori Isu</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="risk_level" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500 font-medium">
                <option value="">Semua Tingkat Risiko</option>
                <option value="red" {{ request('risk_level') == 'red' ? 'selected' : '' }}>🔴 Risiko Tinggi (Bahaya)</option>
                <option value="yellow" {{ request('risk_level') == 'yellow' ? 'selected' : '' }}>🟡 Risiko Sedang (Waspada)</option>
                <option value="green" {{ request('risk_level') == 'green' ? 'selected' : '' }}>🟢 Risiko Rendah (Aman)</option>
            </select>

            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-md transition-all hover:scale-105 active:scale-95 ml-auto">
                Tampilkan Peta
            </button>
        </form>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="glass-panel p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 shadow-sm">
            <p class="text-xs text-slate-600 dark:text-slate-400 font-bold">Total Laporan</p>
            <p class="text-2xl font-heading font-bold text-slate-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
            <span class="text-[10px] text-slate-500 font-semibold">Tercatat</span>
        </div>
        <div class="glass-panel p-4 rounded-2xl border border-amber-500/40 dark:border-amber-500/30 bg-amber-500/10 dark:bg-amber-500/5">
            <p class="text-xs text-amber-700 dark:text-amber-400 font-bold">Menunggu Verifikasi</p>
            <p class="text-2xl font-heading font-bold text-amber-800 dark:text-amber-300 mt-1">{{ $stats['pending'] }}</p>
            <span class="text-[10px] text-amber-700 dark:text-amber-500/80 font-bold">Peninjauan Petugas</span>
        </div>
        <div class="glass-panel p-4 rounded-2xl border border-purple-500/40 dark:border-purple-500/30 bg-purple-500/10 dark:bg-purple-500/5">
            <p class="text-xs text-purple-700 dark:text-purple-400 font-bold">Dalam Penanganan</p>
            <p class="text-2xl font-heading font-bold text-purple-800 dark:text-purple-300 mt-1">{{ $stats['in_progress'] }}</p>
            <span class="text-[10px] text-purple-700 dark:text-purple-500/80 font-bold">Tim Lapangan</span>
        </div>
        <div class="glass-panel p-4 rounded-2xl border border-emerald-500/40 dark:border-emerald-500/30 bg-emerald-500/10 dark:bg-emerald-500/5">
            <p class="text-xs text-emerald-700 dark:text-emerald-400 font-bold">Selesai Ditangani</p>
            <p class="text-2xl font-heading font-bold text-emerald-800 dark:text-emerald-300 mt-1">{{ $stats['resolved'] }}</p>
            <span class="text-[10px] text-emerald-700 dark:text-emerald-500/80 font-bold">Kondusif</span>
        </div>
        <div class="glass-panel p-4 rounded-2xl border border-red-500/40 dark:border-red-500/30 bg-red-500/10 dark:bg-red-500/5">
            <p class="text-xs text-red-700 dark:text-red-400 font-bold">Risiko Merah</p>
            <p class="text-2xl font-heading font-bold text-red-800 dark:text-red-400 mt-1">{{ $stats['red_alerts'] }}</p>
            <span class="text-[10px] text-red-700 dark:text-red-500/80 font-bold">Prioritas Utama</span>
        </div>
        <div class="glass-panel p-4 rounded-2xl border border-amber-500/40 dark:border-amber-500/30 bg-amber-500/10 dark:bg-amber-500/5">
            <p class="text-xs text-amber-700 dark:text-amber-400 font-bold">Risiko Kuning</p>
            <p class="text-2xl font-heading font-bold text-amber-800 dark:text-amber-400 mt-1">{{ $stats['yellow_alerts'] }}</p>
            <span class="text-[10px] text-amber-700 dark:text-amber-500/80 font-bold">Monitoring Rutin</span>
        </div>
    </div>

    <!-- Map & Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Interactive Leaflet Map (2 Cols) -->
        <div class="lg:col-span-2 glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 shadow-xl flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div>
                    <h3 class="font-heading font-extrabold text-xl text-slate-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="map" class="w-5 h-5 text-amber-500"></i>
                        Peta GIS Sebaran Potensi Kerawanan
                    </h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 font-medium mt-0.5">Titik geospasial lokasi kejadian masyarakat & FKDM</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-bold text-slate-700 dark:text-slate-300">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-500 shadow-sm"></span> Bahaya</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-500 shadow-sm"></span> Waspada</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm"></span> Aman</span>
                </div>
            </div>

            <div id="incidentMap" class="w-full h-[440px] rounded-2xl z-10 border border-slate-300 dark:border-slate-700/80 shadow-inner"></div>
        </div>

        <!-- Chart Analytics (1 Col) -->
        <div class="glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 shadow-xl flex flex-col justify-between">
            <div>
                <h3 class="font-heading font-extrabold text-xl text-slate-900 dark:text-white mb-1 flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-5 h-5 text-indigo-500"></i>
                    Distribusi Kategori Isu
                </h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 font-medium mb-6">Persentase isu kerawanan daerah</p>
            </div>
            
            <div class="relative w-full h-[280px] flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-800 text-xs text-center text-slate-500 dark:text-slate-400 font-medium">
                Data terhubung sistem EWS Kesbangpol Pemda
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Leaflet Map Initialization with Smart Center per District
        const districtCenters = {
            'Kabupaten Bogor': [-6.4786, 106.8292],
            'Kota Bogor': [-6.5971, 106.8060],
            'Kabupaten Bekasi': [-6.3262, 107.1350],
            'Kota Bandung': [-6.8850, 107.6135],
            'Kabupaten Deli Serdang': [3.5186, 98.7180],
            'Kabupaten Tangerang': [-6.1783, 106.6319]
        };
        const selectedDistrict = "{{ request('district') }}";
        const defaultCenter = districtCenters[selectedDistrict] || [-6.2088, 106.8456];

        const map = L.map('incidentMap').setView(defaultCenter, selectedDistrict ? 11 : 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        const mapData = @json($mapReports);

        if (mapData.length > 0) {
            const bounds = [];
            mapData.forEach(rep => {
                let color = '#3b82f6';
                if (rep.risk_level === 'red') color = '#ef4444';
                if (rep.risk_level === 'yellow') color = '#f59e0b';
                if (rep.risk_level === 'green') color = '#10b981';

                bounds.push([rep.latitude, rep.longitude]);

                const marker = L.circleMarker([rep.latitude, rep.longitude], {
                    radius: 10,
                    fillColor: color,
                    color: '#ffffff',
                    weight: 2.5,
                    opacity: 1,
                    fillOpacity: 0.85
                }).addTo(map);

                marker.bindPopup(`
                    <div style="font-family: sans-serif; color: #0f172a; padding: 4px; max-width: 220px;">
                        <strong style="color: ${color}; font-size: 11px;">[${rep.report_number}]</strong><br>
                        <strong style="font-size: 13px;">${rep.title}</strong><br>
                        <span style="font-size: 11px; color: #475569;">📍 ${rep.village}, ${rep.subdistrict}</span><br>
                        <a href="/reports/${rep.id}" style="display:inline-block; margin-top:8px; font-size:11px; font-weight:bold; color:#4f46e5; text-decoration:none;">Buka Detail Laporan &rarr;</a>
                    </div>
                `);
            });
            
            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [50, 50], maxZoom: 14 });
            }
        }

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
                            font: { size: 10, weight: 'bold' },
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
