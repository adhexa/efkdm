@extends('layouts.app')

@section('title', 'e-FKDM - Portal Resmi Deteksi Dini & Pelaporan Kerawanan Masyarakat')

@section('content')
<!-- Hero Section with Background Image -->
<section class="hero-section relative overflow-hidden pt-6 pb-12 sm:pt-10 sm:pb-16 lg:pt-14 lg:pb-20 min-h-[540px] sm:min-h-[620px]">

    <!-- Hero Image Background (Full clarity in both Light & Dark modes) -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('img/hero.png') }}" class="w-full h-full object-cover object-center" alt="Background e-FKDM">
    </div>

    <!-- Hero Content Overlay -->
    <div class="max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8 relative z-10 w-full">
        <div class="text-center max-w-3xl mx-auto space-y-4 sm:space-y-6 pt-2 sm:pt-4">

            <!-- Pill Badge Tag -->
            <div class="inline-flex items-center gap-2 px-3 py-1 sm:px-4 sm:py-1.5 rounded-full bg-white/95 shadow-md border border-amber-300/80 text-amber-900 text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider max-w-full text-center">
                <span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                <span class="truncate">PLATFORM DIGITALISASI NASIONAL • BAKESBANGPOL SE-INDONESIA</span>
            </div>

            <!-- Main Heading Title -->
            <h1 class="font-heading font-extrabold text-2xl sm:text-4xl lg:text-6xl tracking-tight leading-[1.2] sm:leading-[1.15] text-slate-900">
                Sistem Nasional Kewaspadaan Dini <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-amber-500 via-orange-500 to-rose-500 bg-clip-text text-transparent">Masyarakat & Stabilitas </span>
                <span class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 bg-clip-text text-transparent">Daerah (e-FKDM)</span>
            </h1>

            <!-- Subtitle Paragraph -->
            <p class="text-slate-700 text-xs sm:text-base leading-relaxed font-medium max-w-2xl mx-auto px-1 sm:px-0">
                Infrastruktur digital standar nasional bagi Badan Kesatuan Bangsa dan Politik (Bakesbangpol) Seluruh Kabupaten/Kota & Provinsi se-Indonesia. Diuji pada Pilot Project Kab. Deli Serdang dan siap diadopsi oleh 514 Kabupaten/Kota secara instan.
            </p>

            <!-- Call To Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 pt-1 sm:pt-2">
                <a href="{{ route('gis') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 sm:px-7 sm:py-3.5 rounded-2xl text-xs sm:text-sm font-extrabold bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white shadow-xl shadow-indigo-600/25 transition-all hover:scale-105 active:scale-95">
                    <i data-lucide="map" class="w-4.5 h-4.5"></i>
                    <span>Pantau Peta GIS Geospasial</span>
                </a>

                @auth
                    <a href="{{ route('reports.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 sm:px-7 sm:py-3.5 rounded-2xl text-xs sm:text-sm font-extrabold bg-gradient-to-r from-amber-500 to-amber-400 hover:from-amber-400 hover:to-amber-300 text-slate-950 shadow-xl shadow-amber-500/25 transition-all hover:scale-105 active:scale-95">
                        <i data-lucide="plus-circle" class="w-4.5 h-4.5"></i>
                        <span>Input Laporan Kejadian (FKDM)</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 sm:px-7 sm:py-3.5 rounded-2xl text-xs sm:text-sm font-extrabold bg-white hover:bg-slate-50 text-slate-800 border border-slate-200 shadow-md transition-all hover:scale-105">
                        <i data-lucide="shield-check" class="w-4.5 h-4.5 text-amber-500"></i>
                        <span>Login Anggota FKDM untuk Melapor</span>
                    </a>
                @endauth
            </div>

        </div>

        <!-- Quick Stats Bar (2x2 on mobile, 4 in a row on desktop) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 pt-8 sm:pt-12 max-w-6xl mx-auto">

            <!-- Stat Card 1 -->
            <div class="bg-white/95 backdrop-blur-md p-3.5 sm:p-4.5 rounded-2xl border border-white/80 shadow-xl shadow-slate-900/5 flex items-center gap-3 sm:gap-4">
                <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-indigo-50 flex items-center justify-center shrink-0">
                    <i data-lucide="file-text" class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-xl sm:text-2xl font-heading font-extrabold text-slate-900 leading-none mb-1">{{ $stats['total_reports'] }}</p>
                    <p class="text-[11px] sm:text-xs text-slate-600 font-semibold leading-tight">Total Laporan</p>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white/95 backdrop-blur-md p-3.5 sm:p-4.5 rounded-2xl border border-white/80 shadow-xl shadow-slate-900/5 flex items-center gap-3 sm:gap-4">
                <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-rose-50 flex items-center justify-center shrink-0">
                    <i data-lucide="alert-triangle" class="w-5 h-5 sm:w-6 sm:h-6 text-rose-600"></i>
                </div>
                <div>
                    <p class="text-xl sm:text-2xl font-heading font-extrabold text-slate-900 leading-none mb-1">{{ $stats['red_alerts'] }}</p>
                    <p class="text-[11px] sm:text-xs text-slate-600 font-semibold leading-tight">Risiko Tinggi</p>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-white/95 backdrop-blur-md p-3.5 sm:p-4.5 rounded-2xl border border-white/80 shadow-xl shadow-slate-900/5 flex items-center gap-3 sm:gap-4">
                <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-blue-50 flex items-center justify-center shrink-0">
                    <i data-lucide="clock" class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xl sm:text-2xl font-heading font-extrabold text-slate-900 leading-none mb-1">{{ $stats['in_progress'] }}</p>
                    <p class="text-[11px] sm:text-xs text-slate-600 font-semibold leading-tight">Penanganan</p>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-white/95 backdrop-blur-md p-3.5 sm:p-4.5 rounded-2xl border border-white/80 shadow-xl shadow-slate-900/5 flex items-center gap-3 sm:gap-4">
                <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-emerald-50 flex items-center justify-center shrink-0">
                    <i data-lucide="check-circle-2" class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-xl sm:text-2xl font-heading font-extrabold text-slate-900 leading-none mb-1">{{ $stats['resolved'] }}</p>
                    <p class="text-[11px] sm:text-xs text-slate-600 font-semibold leading-tight">Selesai</p>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- National Rollout & Pilot Project Banner -->
<section class="py-10 bg-gradient-to-r from-amber-500/10 via-indigo-50/80 to-amber-500/10 dark:from-slate-900 dark:via-indigo-950/60 dark:to-slate-900 text-slate-900 dark:text-white border-y border-amber-200/80 dark:border-indigo-500/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
            <div class="space-y-2 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 text-xs font-extrabold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Multi-Tenancy Platform Ready
                </div>
                <h3 class="text-xl sm:text-2xl font-extrabold font-heading text-slate-900 dark:text-white">
                    Terbuka Pendaftaran Untuk Bakesbangpol Kab/Kota & Provinsi se-Indonesia
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 max-w-2xl font-medium">
                    Sistem e-FKDM dan e-Monev Pemda siap diimplementasikan sebagai platform kewaspadaan dini nasional berbasis multi-tenant daerah.
                </p>
            </div>
            <div class="flex items-center justify-center shrink-0">
                <a href="https://wa.me/6285156643367?text=Halo%20Pengembang,%20kami%20berminat%20mendaftarkan%20Bakesbangpol%20kabupaten/kota%20kami%20ke%20Sistem%20e-FKDM" target="_blank" class="px-6 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-amber-400 hover:from-amber-400 hover:to-amber-300 text-slate-950 font-extrabold text-xs tracking-wide uppercase transition-all shadow-lg shadow-amber-500/20 hover:scale-105 active:scale-95 flex items-center gap-2">
                    <i data-lucide="message-square" class="w-4 h-4"></i>
                    <span>Hubungi Pengembang</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Quick Access Cards: Peta GIS & Daftar Laporan -->
<section class="py-14 bg-white/70 dark:bg-slate-950 border-b border-slate-200/80 dark:border-slate-800/80 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs uppercase font-extrabold tracking-widest text-amber-500">AKSES INFORMASI PUBLIK</span>
            <h2 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 dark:text-white mt-1">Layanan Geospasial & Informasi Kejadian</h2>
            <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm mt-1.5 font-medium">Jelajahi peta persebaran isu wilayah dan arsip laporan deteksi dini secara transparan.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Card 1: Peta GIS Geospasial -->
            <a href="{{ route('gis') }}" class="group relative bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-7 border border-indigo-200 dark:border-indigo-500/30 shadow-lg hover:shadow-2xl hover:border-indigo-500 transition-all duration-300 transform hover:-translate-y-1.5 flex flex-col justify-between overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-36 h-36 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-500/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                            <i data-lucide="map" class="w-7 h-7"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 border border-indigo-300 dark:border-indigo-500/30 uppercase tracking-wider">
                            Peta Real-Time
                        </span>
                    </div>

                    <h3 class="font-heading font-extrabold text-xl text-slate-900 dark:text-white mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                        Peta GIS Geospasial
                    </h3>
                    <p class="text-slate-600 dark:text-slate-400 text-xs leading-relaxed mb-6 font-medium">
                        Pantau sebaran titik lokasi kejadian, tingkat risiko merah/kuning/hijau, dan zonasi potensi kerawanan daerah secara visual di seluruh kabupaten/kota.
                    </p>
                </div>

                <div class="inline-flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800/80 text-xs font-extrabold text-indigo-600 dark:text-indigo-400 group-hover:translate-x-1 transition-transform">
                    <span>Buka Peta GIS Geospasial</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </a>

            <!-- Card 2: Daftar Laporan Kejadian -->
            <a href="{{ route('reports.index') }}" class="group relative bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-7 border border-amber-200 dark:border-amber-500/30 shadow-lg hover:shadow-2xl hover:border-amber-500 transition-all duration-300 transform hover:-translate-y-1.5 flex flex-col justify-between overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-36 h-36 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all"></div>
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-amber-50 dark:bg-amber-500/20 border border-amber-200 dark:border-amber-500/30 flex items-center justify-center text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                            <i data-lucide="file-text" class="w-7 h-7"></i>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 dark:bg-amber-500/20 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-500/30 uppercase tracking-wider">
                            Arsip Kejadian
                        </span>
                    </div>

                    <h3 class="font-heading font-extrabold text-xl text-slate-900 dark:text-white mb-2 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
                        Daftar Laporan Kejadian
                    </h3>
                    <p class="text-slate-600 dark:text-slate-400 text-xs leading-relaxed mb-6 font-medium">
                        Tinjau seluruh daftar laporan kejadian terverifikasi, status penanganan petugas lapangan, dan histori laporan publik yang telah ditangani secara terbuka.
                    </p>
                </div>

                <div class="inline-flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800/80 text-xs font-extrabold text-amber-600 dark:text-amber-400 group-hover:translate-x-1 transition-transform">
                    <span>Buka Daftar Laporan Kejadian</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </div>
            </a>

            <!-- Card 3: Portal Anggota FKDM & Input Laporan -->
            @auth
                <a href="{{ route('reports.create') }}" class="group relative bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-7 border border-emerald-200 dark:border-emerald-500/30 shadow-lg hover:shadow-2xl hover:border-emerald-500 transition-all duration-300 transform hover:-translate-y-1.5 flex flex-col justify-between overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-36 h-36 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-500/20 border border-emerald-200 dark:border-emerald-500/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                                <i data-lucide="plus-circle" class="w-7 h-7"></i>
                            </div>
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-500/30 uppercase tracking-wider">
                                Fitur Petugas
                            </span>
                        </div>

                        <h3 class="font-heading font-extrabold text-xl text-slate-900 dark:text-white mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                            Input Laporan Kejadian
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 text-xs leading-relaxed mb-6 font-medium">
                            Formulir resmi pelaporan deteksi dini titik kerawanan daerah bagi Anggota FKDM Desa, Kecamatan, dan Pengurus Kabupaten.
                        </p>
                    </div>

                    <div class="inline-flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800/80 text-xs font-extrabold text-emerald-600 dark:text-emerald-400 group-hover:translate-x-1 transition-transform">
                        <span>Input Laporan Kejadian Baru</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </div>
                </a>
            @else
                <a href="{{ route('register') }}" class="group relative bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-7 border border-emerald-200 dark:border-emerald-500/30 shadow-lg hover:shadow-2xl hover:border-emerald-500 transition-all duration-300 transform hover:-translate-y-1.5 flex flex-col justify-between overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-36 h-36 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-500/20 border border-emerald-200 dark:border-emerald-500/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                                <i data-lucide="user-check" class="w-7 h-7"></i>
                            </div>
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-500/30 uppercase tracking-wider">
                                Registrasi Resmi
                            </span>
                        </div>

                        <h3 class="font-heading font-extrabold text-xl text-slate-900 dark:text-white mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                            Pendaftaran Anggota FKDM
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 text-xs leading-relaxed mb-6 font-medium">
                            Daftarkan diri Anda sebagai Anggota FKDM Kelurahan/Kecamatan/Kabupaten untuk mendapatkan hak pelaporan resmi deteksi dini daerah.
                        </p>
                    </div>

                    <div class="inline-flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800/80 text-xs font-extrabold text-emerald-600 dark:text-emerald-400 group-hover:translate-x-1 transition-transform">
                        <span>Daftar Anggota FKDM</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </div>
                </a>
            @endauth

        </div>
    </div>
</section>

<!-- Categories Section (Darkens smoothly in Dark Mode) -->
<section class="py-16 bg-slate-50/80 dark:bg-slate-950 border-t border-slate-200/80 dark:border-slate-800 relative z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs uppercase font-extrabold tracking-widest text-indigo-600 dark:text-indigo-400">KATEGORI ISU</span>
            <h2 class="font-heading font-extrabold text-3xl text-slate-900 dark:text-white mt-1">Kategori Isu & Kerawanan Daerah</h2>
            <p class="text-slate-600 dark:text-slate-300 text-sm mt-1.5">Jenis laporan deteksi dini yang dipantau oleh tim FKDM bersama Badan Kesbangpol</p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-all group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110" style="background-color: {{ $category->color_code }}18;">
                            <i data-lucide="{{ $category->icon }}" class="w-6 h-6" style="color: {{ $category->color_code }};"></i>
                        </div>
                        <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white mb-2">{{ $category->name }}</h3>
                        <p class="text-slate-600 dark:text-slate-300 text-xs leading-relaxed mb-6">{{ $category->description }}</p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800 text-xs">
                        <span class="text-slate-400 dark:text-slate-400 font-medium text-[11px]">Laporan Tercatat</span>
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-[#0f172a] text-white dark:bg-amber-500/20 dark:text-amber-300 dark:border dark:border-amber-500/40">
                            {{ $category->reports_count }} Laporan
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

@endsection

