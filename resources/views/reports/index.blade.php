@extends('layouts.app')

@section('title', Auth::check() ? 'Daftar Laporan Kejadian Deteksi Dini - e-FKDM' : 'Arsip Laporan Kejadian Selesai Ditangani - e-FKDM')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 dark:text-white">
                {{ Auth::check() ? 'Daftar Laporan Kejadian Deteksi Dini' : 'Arsip Laporan Kejadian Selesai Ditangani' }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 font-medium">
                {{ Auth::check() ? 'Monitoring dan penanganan laporan potensi kerawanan sosial, keamanan, dan bencana daerah.' : 'Arsip publik daftar laporan kerawanan dan kejadian yang telah berhasil ditindaklanjuti secara tuntas oleh tim FKDM & Kesbangpol.' }}
            </p>
        </div>
        @auth
            <a href="{{ route('reports.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-400 hover:from-amber-400 hover:to-amber-300 text-slate-950 font-extrabold text-sm shadow-xl shadow-amber-500/20 transition-all hover:scale-105 active:scale-95 shrink-0">
                <i data-lucide="plus-circle" class="w-4.5 h-4.5"></i>
                <span>Input Laporan Kejadian (FKDM)</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 text-white font-extrabold text-sm shadow-md transition-all hover:scale-105 active:scale-95 shrink-0">
                <i data-lucide="shield-check" class="w-4.5 h-4.5 text-amber-500"></i>
                <span>Login Anggota untuk Melapor</span>
            </a>
        @endauth
    </div>

    @guest
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-900 dark:text-emerald-300 text-xs flex items-center gap-3 shadow-sm">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 shrink-0"></i>
            <span><strong>Mode Publik Masyarakat:</strong> Menampilkan transparansi seluruh laporan kejadian yang telah <strong>Selesai Ditangani</strong> di lapangan. Identitas & dokumen internal petugas terenkripsi secara aman.</span>
        </div>
    @endguest

    <!-- Filter Card -->
    <div class="glass-panel p-4 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 shadow-lg">
        <form action="{{ route('reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari judul, nomor..."
                   class="px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-amber-500 font-medium">

            @if(Auth::check() && Auth::user()->role !== 'vendor_admin' && !empty(Auth::user()->district))
                <div class="px-3.5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-200 font-bold flex items-center justify-center shrink-0">
                    <span class="truncate">📍 {{ Auth::user()->district }}</span>
                </div>
            @else
                <select name="district" onchange="this.form.submit()" class="px-3 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500 font-medium">
                    <option value="">Semua Kabupaten / Kota</option>
                    @foreach($districts as $dst)
                        <option value="{{ $dst }}" {{ request('district') == $dst ? 'selected' : '' }}>🏛️ {{ $dst }}</option>
                    @endforeach
                </select>
            @endif

            <select name="category_id" onchange="this.form.submit()" class="px-3 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500 font-medium">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="risk_level" onchange="this.form.submit()" class="px-3 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500 font-medium">
                <option value="">Semua Tingkat Risiko</option>
                <option value="red" {{ request('risk_level') == 'red' ? 'selected' : '' }}>🔴 Merah (Bahaya)</option>
                <option value="yellow" {{ request('risk_level') == 'yellow' ? 'selected' : '' }}>🟡 Kuning (Waspada)</option>
                <option value="green" {{ request('risk_level') == 'green' ? 'selected' : '' }}>🟢 Hijau (Aman)</option>
            </select>

            @auth
                <select name="status" class="px-3 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-amber-500 font-medium">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Dalam Penanganan</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Selesai</option>
                </select>
            @else
                <div class="px-3.5 py-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-xs text-emerald-700 dark:text-emerald-300 font-bold flex items-center justify-center gap-1.5 shrink-0">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                    <span>Status: Selesai Ditangani</span>
                </div>
            @endauth

            <button type="submit" class="py-2.5 px-4 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-sm transition-all hover:scale-105 active:scale-95">
                Terapkan Filter
            </button>
        </form>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($reports as $report)
            <div class="glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 shadow-xl flex flex-col justify-between hover:border-amber-500/50 transition-all group">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="font-mono text-xs text-amber-600 dark:text-amber-400 font-extrabold">{{ $report->report_number }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $report->risk_badge_class }}">
                            {{ $report->risk_label }}
                        </span>
                    </div>

                    <h3 class="font-heading font-extrabold text-lg text-slate-900 dark:text-white mb-2.5 line-clamp-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
                        <a href="{{ route('reports.show', $report->id) }}">{{ $report->title }}</a>
                    </h3>

                    <p class="text-slate-600 dark:text-slate-400 text-xs line-clamp-3 mb-4 leading-relaxed font-medium">{{ $report->chronology }}</p>
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                    <div class="flex items-center justify-between text-xs text-slate-700 dark:text-slate-300 font-semibold">
                        <span class="flex items-center gap-1.5 truncate"><i data-lucide="folder" class="w-3.5 h-3.5 text-amber-500 shrink-0"></i> {{ $report->category->name }}</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border shrink-0 {{ $report->status_badge_class }}">
                            {{ $report->status_label }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 font-medium pt-0.5">
                        <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-amber-500 shrink-0"></i> {{ $report->village }}</span>
                        <span>{{ $report->incident_date->format('d M Y, H:i') }} WIB</span>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('reports.show', $report->id) }}" class="w-full text-center block py-2.5 px-4 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-amber-500 hover:text-slate-950 dark:hover:bg-amber-400 dark:hover:text-slate-950 font-extrabold text-xs text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 transition-all shadow-sm">
                            Lihat Rincian Laporan &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 glass-panel rounded-3xl border border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80">
                <i data-lucide="search-x" class="w-12 h-12 text-slate-400 mx-auto mb-3"></i>
                <p class="text-slate-600 dark:text-slate-400 text-sm font-semibold">Tidak ada laporan kejadian yang ditemukan.</p>
            </div>
        @endforelse
    </div>

    <div class="pt-4">
        {{ $reports->links() }}
    </div>
</div>
@endsection
