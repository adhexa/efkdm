<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: false, theme: localStorage.getItem('theme') || 'light' }" x-init="
        document.documentElement.classList.remove('light', 'dark');
        document.documentElement.classList.add(theme);
        $watch('theme', val => {
            document.documentElement.classList.remove('light', 'dark');
            document.documentElement.classList.add(val);
            localStorage.setItem('theme', val);
        });
    ">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon.png') }}">

    <title>@yield('title', 'e-FKDM - Sistem Kewaspadaan Dini Masyarakat')</title>

    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Apply theme class IMMEDIATELY (before Alpine loads) to prevent flash -->
    <script>
        (function () {
            // Default theme is always 'light' unless user explicitly chose 'dark'
            var saved = localStorage.getItem('theme');
            var theme = (saved === 'dark') ? 'dark' : 'light';
            // Always set/normalize the stored value
            localStorage.setItem('theme', theme);
            document.documentElement.classList.remove('light', 'dark');
            document.documentElement.classList.add(theme);
        })();
    </script>

    <!-- Tailwind CDN - load first, then configure -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#1e1b4b',
                        },
                        darkbg: '#0b0f19',
                        darkcard: '#1e293b',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Leaflet CSS & JS (For Maps) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        h1,
        h2,
        h3,
        h4,
        .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* DISABLE OS/browser dark mode preference - only class-based dark mode allowed */
        @media (prefers-color-scheme: dark) {
            html:not(.dark) {
                color-scheme: light;
                background-color: #f8fafc;
                color: #0f172a;
            }
        }

        /* Dark Mode Global Styles */
        .dark {
            color-scheme: dark;
        }

        .dark body {
            background-color: #0b0f19;
            color: #f1f5f9;
        }

        /* Light Mode override (takes priority over dark body) */
        .light body {
            background-color: #f8fafc !important;
            color: #0f172a !important;
        }

        /* Dark Mode Header */
        .dark header {
            background-color: rgba(15, 23, 42, 0.95) !important;
            border-bottom-color: rgba(255, 255, 255, 0.08) !important;
        }

        .dark header .font-heading,
        .dark header h2,
        .dark header span.text-slate-900,
        .dark header a.text-slate-700 {
            color: #ffffff !important;
        }

        /* Dark Mode Sections below Hero */
        .dark section:not(.hero-section) {
            background-color: #0b0f19 !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        .dark section:not(.hero-section) h2,
        .dark section:not(.hero-section) h3,
        .dark section:not(.hero-section) .font-heading {
            color: #ffffff !important;
        }

        .dark section:not(.hero-section) p,
        .dark section:not(.hero-section) .text-slate-500,
        .dark section:not(.hero-section) .text-slate-600 {
            color: #cbd5e1 !important;
        }

        /* Dark Mode Cards below Hero */
        .dark section:not(.hero-section) .bg-white {
            background-color: #0f172a !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        .dark section:not(.hero-section) .border-t,
        .dark section:not(.hero-section) .border-b {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        /* Hero Section Exceptions: Hero image is untouched, text stays crisp on 3D background */
        .hero-section {
            background-color: transparent !important;
        }

        .hero-section .text-slate-900 {
            color: #0f172a !important;
        }

        .hero-section .text-slate-700 {
            color: #334155 !important;
        }

        .hero-section .bg-white\/95 {
            background-color: rgba(255, 255, 255, 0.95) !important;
        }

        /* Dark Mode Footer */
        .dark footer {
            background-color: #0f172a !important;
            border-top-color: rgba(255, 255, 255, 0.08) !important;
        }

        .dark footer h4,
        .dark footer span {
            color: #ffffff !important;
        }

        .dark footer p,
        .dark footer li,
        .dark footer a {
            color: #cbd5e1 !important;
        }

        /* Soft & High-Contrast Light Mode Styles */

        .light .glass-panel {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
        }

        .light .sidebar-bg {
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
        }

        /* High contrast active states in Light Mode */
        .light .sidebar-link {
            color: #334155;
        }

        .light .sidebar-link:hover {
            color: #4f46e5 !important;
            background-color: #f1f5f9 !important;
        }

        html.light .sidebar-link.active {
            color: #4f46e5 !important;
            background-color: #eef2ff !important;
            border-color: #c7d2fe !important;
        }
    </style>
    @stack('styles')
</head>

<body
    class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased selection:bg-brand-500 selection:text-white flex">

    @php
        // Jika controller/view sudah set $hasSidebar, gunakan itu. 
        // Jika tidak ada, hanya tampilkan sidebar bagi pengguna yang sudah login.
        if (!isset($hasSidebar)) {
            $hasSidebar = Auth::check() && !request()->is('login*') && !request()->is('register*');
        }
    @endphp

    @if($hasSidebar)
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-slate-950/80 backdrop-blur-sm lg:hidden"></div>

        <!-- LEFT SIDEBAR (BILAH SAMPING KIRI) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 w-64 sidebar-bg flex flex-col justify-between transition-transform duration-300 ease-in-out shadow-2xl">

            <!-- Sidebar Header: Brand & Logo -->
            <div class="p-6 border-b border-slate-200 dark:border-slate-800/80">
                <a href="{{ Auth::check() ? route('dashboard') : route('home') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('img/logo.png') }}" class="w-12 h-12 sm:w-13 sm:h-13 object-contain group-hover:scale-105 transition-transform shrink-0" alt="e-FKDM Logo">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span
                                class="font-heading font-extrabold text-xl tracking-tight text-slate-900 dark:text-white">e-FKDM</span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold leading-tight mt-0.5">Sistem
                            Kewaspadaan Masyarakat</p>
                    </div>
                </a>
            </div>

            <!-- Sidebar Navigation Menu -->
            <div class="flex-grow px-4 py-6 overflow-y-auto space-y-6">

                <!-- Primary Navigation Links -->
                <div class="space-y-1">
                    <p
                        class="px-3 text-[10px] uppercase font-extrabold tracking-wider text-slate-400 dark:text-slate-500 mb-2">
                        Navigasi Utama</p>

                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('dashboard') ? 'bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <i data-lucide="layout-dashboard" class="w-4 h-4 text-emerald-500"></i>
                            <span>Dashboard Officer</span>
                        </a>
                    @endauth

                    @guest
                    <a href="{{ route('home') }}"
                        class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('home') ? 'bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-200' }}">
                        <i data-lucide="home" class="w-4 h-4 text-amber-500"></i>
                        <span>Beranda Utama</span>
                    </a>
                    @endguest

                    <a href="{{ route('gis') }}"
                        class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('gis') ? 'bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-200' }}">
                        <i data-lucide="map" class="w-4 h-4 text-indigo-500"></i>
                        <span>Peta GIS Geospasial</span>
                    </a>

                    <a href="{{ route('reports.index') }}"
                        class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('reports.index') || request()->routeIs('reports.show') ? 'bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-200' }}">
                        <i data-lucide="file-text" class="w-4 h-4 text-blue-500"></i>
                        <span>Daftar Laporan Kejadian</span>
                    </a>

                    @auth
                        <a href="{{ route('monev.index') }}"
                            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('monev.*') ? 'bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <i data-lucide="bar-chart-3" class="w-4 h-4 text-amber-500"></i>
                            <span>Monitoring & Evaluasi</span>
                        </a>

                        @if(Auth::user()->isAdmin() || Auth::user()->isFkdmKabupaten() || Auth::user()->isVendorAdmin())
                            <a href="{{ route('members.index') }}"
                                class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('members.*') ? 'bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-200' }}">
                                <i data-lucide="user-check" class="w-4 h-4 text-emerald-500"></i>
                                <span>Approval Anggota</span>
                            </a>
                        @endif

                        <a href="{{ route('password.edit') }}"
                            class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('password.edit') ? 'bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/30' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-200' }}">
                            <i data-lucide="key-round" class="w-4 h-4 text-rose-500"></i>
                            <span>Ganti Password</span>
                        </a>
                    @endauth
                </div>

                <!-- Action Button Section -->
                @if(!request()->is('login*') && !request()->is('register*') && !request()->routeIs('login*') && !request()->routeIs('register*'))
                    <div class="pt-2">
                        <a href="{{ Auth::check() ? route('reports.create') : route('login') }}"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-xs font-extrabold bg-gradient-to-r from-amber-500 to-amber-400 hover:from-amber-400 hover:to-amber-300 text-slate-950 shadow-lg shadow-amber-500/20 transition-all hover:scale-[1.02] active:scale-95">
                            <i data-lucide="{{ Auth::check() ? 'plus-circle' : 'shield-check' }}" class="w-4 h-4"></i>
                            <span>{{ Auth::check() ? 'Buat Lapor Kejadian' : 'Login Anggota Melapor' }}</span>
                        </a>
                    </div>
                @endif


            </div>

            <!-- Sidebar Footer -->
            <div
                class="p-4 border-t border-slate-200 dark:border-slate-800/80 text-[11px] text-slate-500 dark:text-slate-400 text-center">
                &copy; 2026 e-FKDM Kesbangpol Pemda
            </div>
        </aside>
    @endif

    <!-- MAIN WRAPPER (RIGHT SIDE) -->
    <div class="flex-1 {{ $hasSidebar ? 'lg:pl-64' : '' }} flex flex-col min-h-screen w-full transition-all">

        <!-- TOP NAVBAR HEADER -->
        <header x-data="{ mobileNavOpen: false }"
            class="sticky top-0 z-30 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800/80 px-3.5 sm:px-6 lg:px-8 py-2.5 sm:py-3.5 flex flex-col shadow-sm">

            <div class="flex items-center justify-between w-full">
                <!-- Mobile Toggle Hamburger & Breadcrumb OR Brand Logo -->
                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                    @if($hasSidebar)
                        <button @click="sidebarOpen = !sidebarOpen" type="button"
                            class="p-2 rounded-xl text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white lg:hidden border border-slate-200 dark:border-slate-800 shrink-0">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </button>

                        <!-- Mobile Brand display when sidebar exists -->
                        <a href="{{ route('home') }}" class="flex items-center gap-2.5 group lg:hidden">
                            <img src="{{ asset('img/logo.png') }}" class="w-11 h-11 object-contain group-hover:scale-105 transition-transform shrink-0" alt="e-FKDM Logo">
                            <span
                                class="font-heading font-extrabold text-base tracking-tight text-slate-900 dark:text-white">e-FKDM</span>
                        </a>
                    @else
                        <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 group min-w-0">
                            <img src="{{ asset('img/logo.png') }}" class="w-11 sm:w-13 h-11 sm:h-13 object-contain group-hover:scale-105 transition-transform shrink-0" alt="e-FKDM Logo">
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span
                                        class="font-heading font-extrabold text-lg sm:text-xl tracking-tight text-slate-900 dark:text-white truncate">e-FKDM</span>
                                </div>
                                <p
                                    class="hidden sm:block text-[11px] text-slate-500 dark:text-slate-400 font-semibold leading-none mt-0.5 truncate">
                                    Sistem Kewaspadaan Masyarakat</p>
                            </div>
                        </a>
                    @endif
                </div>

                <!-- Top Right: Theme Switcher & User Profile / Desktop Auth -->
                <div class="flex items-center gap-2 sm:gap-4 shrink-0">

                    <!-- Theme Switcher Pill -->
                    <div
                        class="flex items-center p-1 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700/60">
                        <button @click="theme = 'light'" type="button"
                            class="p-1.5 rounded-lg transition-all cursor-pointer flex items-center gap-1 text-xs font-bold"
                            :class="theme === 'light' ? 'bg-white text-amber-500 shadow-sm' : 'text-slate-400 hover:text-slate-700'"
                            title="Mode Terang">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>
                        <button @click="theme = 'dark'" type="button"
                            class="p-1.5 rounded-lg transition-all cursor-pointer flex items-center gap-1 text-xs font-bold"
                            :class="theme === 'dark' ? 'bg-slate-900 text-indigo-400 shadow-sm' : 'text-slate-400 hover:text-slate-700'"
                            title="Mode Gelap">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>
                    </div>

                    @auth
                        <!-- User Dropdown Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="flex items-center gap-2 sm:gap-3 p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/60 transition-colors border border-slate-200 dark:border-slate-700/50">
                                <div
                                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-gradient-to-br from-amber-500 to-indigo-600 flex items-center justify-center font-bold text-white shadow text-xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="text-left hidden sm:block">
                                    <p class="text-xs font-bold leading-tight text-slate-900 dark:text-slate-200">
                                        {{ Auth::user()->name }}
                                    </p>
                                    <p class="text-[10px] text-amber-600 dark:text-amber-400 font-bold leading-tight">
                                        {{ Auth::user()->role_badge }}
                                    </p>
                                </div>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500 dark:text-slate-400"></i>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition
                                class="absolute right-0 mt-2 w-64 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700/80 py-2 z-50">
                                <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700/60">
                                    <p class="text-xs text-slate-400">Masuk sebagai</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-slate-200 truncate">
                                        {{ Auth::user()->email }}
                                    </p>
                                    <span
                                        class="inline-block mt-1 text-[10px] px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 dark:bg-brand-500/20 dark:text-brand-300 font-bold border border-indigo-200 dark:border-brand-500/30">
                                        {{ Auth::user()->role_badge }}
                                    </span>
                                </div>
                                <div class="p-3 my-1">
                                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/60 space-y-1">
                                        <span class="text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold block">Wilayah Kerja Terdaftar</span>
                                        <p class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 inline text-amber-500 mr-1"></i>
                                            {{ Auth::user()->subdistrict ?? 'Kecamatan' }}
                                        </p>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 pl-4">
                                            {{ Auth::user()->village ?? 'Kelurahan' }}, {{ Auth::user()->district ?? 'Kabupaten Bogor' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="border-t border-slate-100 dark:border-slate-700/60 my-1"></div>
                                <a href="{{ route('password.edit') }}"
                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                    <i data-lucide="key-round" class="w-4 h-4 text-amber-500"></i> Ganti Password
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors">
                                        <i data-lucide="log-out" class="w-4 h-4"></i> Keluar Akun
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Desktop Buttons (Visible >= sm) -->
                        <div class="hidden sm:flex items-center gap-3">
                            <a href="{{ route('login') }}"
                                class="text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-white px-3 py-2 transition-colors">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}"
                                class="text-xs font-bold px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-600/20 transition-all hover:scale-105">
                                Daftar FKDM
                            </a>
                        </div>

                        <!-- Mobile Hamburger Button for Guest Header (< sm) -->
                        @if(!$hasSidebar)
                            <button @click="mobileNavOpen = !mobileNavOpen" type="button"
                                class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 sm:hidden border border-slate-200 dark:border-slate-800 transition-colors">
                                <i data-lucide="menu" class="w-5 h-5" x-show="!mobileNavOpen"></i>
                                <i data-lucide="x" class="w-5 h-5 text-rose-500" x-show="mobileNavOpen"
                                    style="display:none;"></i>
                            </button>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Public Mobile Navigation Slide-Down Panel (< sm) -->
            @if(!$hasSidebar)
                <div x-show="mobileNavOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                    class="sm:hidden pt-3 mt-2 border-t border-slate-200 dark:border-slate-800 space-y-2.5">

                    <a href="{{ Auth::check() ? route('reports.create') : route('login') }}"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-amber-500 text-slate-950 shadow-md">
                        <i data-lucide="{{ Auth::check() ? 'plus-circle' : 'shield-check' }}" class="w-4 h-4"></i>
                        <span>{{ Auth::check() ? 'Buat Lapor Kejadian' : 'Login Anggota FKDM' }}</span>
                    </a>

                    <div class="grid grid-cols-2 gap-2 pt-1">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700">
                                <i data-lucide="layout-dashboard" class="w-4 h-4 text-indigo-500"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-white border border-slate-200 dark:border-slate-700">
                                <i data-lucide="log-in" class="w-4 h-4 text-indigo-500"></i> Masuk
                            </a>
                            <a href="{{ route('register') }}"
                                class="flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-xs font-bold bg-blue-600 text-white shadow-sm">
                                <i data-lucide="user-plus" class="w-4 h-4"></i> Daftar FKDM
                            </a>
                        @endauth
                    </div>
                </div>
            @endif

        </header>

        <!-- FLASH MESSAGES & SWEETALERT -->
        <div class="px-4 sm:px-6 lg:px-8 w-full">
            @if(session('success'))
                <div
                    class="p-4 mb-2 rounded-2xl bg-emerald-50 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30 flex items-center gap-3 shadow-sm">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                    <div class="text-xs font-semibold">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('registration_success'))
                <div
                    class="p-4 mb-2 rounded-2xl bg-emerald-50 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30 flex items-center gap-3 shadow-sm">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                    <div class="text-xs font-semibold">{{ session('registration_success') }}</div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pendaftaran Berhasil!',
                                text: "{{ session('registration_success') }}",
                                confirmButtonText: 'Saya Mengerti (Menunggu Approval)',
                                confirmButtonColor: '#4f46e5',
                                customClass: {
                                    popup: 'rounded-3xl dark:bg-slate-900 dark:text-white border border-slate-200 dark:border-slate-800',
                                    title: 'font-heading font-extrabold text-xl',
                                    confirmButton: 'px-6 py-2.5 rounded-xl font-bold text-xs shadow-lg'
                                }
                            });
                        }
                    });
                </script>
            @endif

            @if(session('error'))
                <div
                    class="p-4 mb-2 rounded-2xl bg-rose-50 text-rose-800 dark:bg-rose-500/10 dark:text-rose-300 border border-rose-200 dark:border-rose-500/30 flex items-center gap-3 shadow-sm">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600 shrink-0"></i>
                    <div class="text-xs font-semibold">{{ session('error') }}</div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Pemberitahuan',
                                text: "{{ session('error') }}",
                                confirmButtonText: 'Tutup',
                                confirmButtonColor: '#e11d48',
                                customClass: {
                                    popup: 'rounded-3xl dark:bg-slate-900 dark:text-white border border-slate-200 dark:border-slate-800',
                                    title: 'font-heading font-extrabold text-xl',
                                    confirmButton: 'px-6 py-2.5 rounded-xl font-bold text-xs shadow-lg'
                                }
                            });
                        }
                    });
                </script>
            @endif
        </div>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-grow relative z-10">
            @yield('content')
        </main>

        <!-- FOOTER -->
        @guest
        <footer
            class="mt-auto relative overflow-hidden bg-white dark:bg-slate-950 py-5 sm:py-6 px-4 sm:px-6 lg:px-8">
            <!-- Continuous Top Border Line (z-20 to prevent cutoff by background images) -->
            <div class="absolute top-0 inset-x-0 h-px bg-slate-200/90 dark:bg-slate-800/90 z-20 pointer-events-none"></div>

            <!-- Background Image bgfooter.jpg (Tampil di Setiap Halaman Footer) -->
            <div class="absolute right-0 top-0 bottom-0 w-96 sm:w-[580px] md:w-[760px] lg:w-[880px] pointer-events-none opacity-90 dark:opacity-40 z-0">
                <img src="{{ asset('img/bgfooter.jpg') }}" class="w-full h-full object-contain object-right-bottom" alt="Footer Background">
            </div>

            <!-- Soft Overlay to keep left text 100% crisp & clear -->
            <div class="absolute inset-0 z-0 bg-gradient-to-r from-white via-white/85 to-transparent dark:from-slate-950 dark:via-slate-950/85 dark:to-transparent pointer-events-none"></div>

            <div class="max-w-7xl mx-auto relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 sm:gap-6">
                    <div class="md:col-span-2 space-y-1.5">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('img/logo.png') }}" class="w-12 h-12 object-contain shrink-0" alt="e-FKDM Logo">
                            <span
                                class="font-heading font-extrabold text-lg text-slate-900 dark:text-white">e-FKDM</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 font-medium leading-relaxed max-w-md">
                            Sistem Informasi Pelaporan, Deteksi Dini, Monitoring & Evaluasi Kewaspadaan Dini Masyarakat
                            yang difasilitasi oleh Konsultan Swasta untuk Badan Kesbangpol & Pemerintah Daerah.
                        </p>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold pt-0.5">
                            &copy; 2026 e-FKDM
                        </p>
                    </div>
                    <div>
                        <h4
                            class="font-heading font-extrabold text-slate-900 dark:text-slate-100 mb-2 text-xs uppercase tracking-wider">
                            NAVIGASI</h4>
                        <ul class="space-y-1.5 text-xs text-slate-700 dark:text-slate-300 font-bold">
                            <li><a href="{{ route('home') }}"
                                    class="hover:text-indigo-600 dark:hover:text-amber-400 transition-colors">Beranda
                                    Utama</a></li>
                            <li><a href="{{ route('gis') }}"
                                    class="hover:text-indigo-600 dark:hover:text-amber-400 transition-colors">Peta
                                    GIS Geospasial</a></li>
                            @auth
                                <li><a href="{{ route('monev.index') }}"
                                        class="hover:text-indigo-600 dark:hover:text-amber-400 transition-colors">Monitoring
                                        & Evaluasi</a></li>
                            @endauth
                            <li><a href="{{ route('reports.index') }}"
                                    class="hover:text-indigo-600 dark:hover:text-amber-400 transition-colors">Daftar
                                    Laporan Kejadian</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4
                            class="font-heading font-extrabold text-slate-900 dark:text-slate-100 mb-2 text-xs uppercase tracking-wider">
                            LAYANAN KONSULTAN</h4>
                        <ul class="space-y-1.5 text-xs text-slate-700 dark:text-slate-300 font-bold">
                            <li class="flex items-center gap-2"><i data-lucide="file-text"
                                    class="w-3.5 h-3.5 text-amber-500"></i> adhexa.id</li>
                            <li class="flex items-center gap-2"><i data-lucide="mail"
                                    class="w-3.5 h-3.5 text-amber-500"></i> admin@adhexa.id</li>
                            <li class="flex items-center gap-2"><i data-lucide="clock"
                                    class="w-3.5 h-3.5 text-amber-500"></i> Mitra Fasilitator Kesbangpol</li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        @endguest

    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>

</html>