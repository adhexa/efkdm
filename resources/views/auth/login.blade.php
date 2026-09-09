@extends('layouts.app', ['hasSidebar' => false])

@section('title', 'Login Portal e-FKDM Pemda')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden" x-data="{ email: '{{ old('email') }}', password: '' }">
    <!-- Background Image with Overlay (Bright & High Clarity) -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('img/login-bg.jpg') }}" class="w-full h-full object-cover object-center filter brightness-105 scale-100" alt="Background e-FKDM">
        <div class="absolute inset-0 bg-white/20 dark:bg-slate-950/60 backdrop-blur-[1px]"></div>
    </div>

    <div class="max-w-lg w-full bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl p-8 sm:p-10 rounded-3xl border border-white/40 dark:border-slate-800 shadow-2xl relative z-10 space-y-6">
        
        <div class="text-center">
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-3 flex items-center justify-center mx-auto mb-4 shadow-lg">
                <img src="{{ asset('img/logo.png') }}" class="w-full h-full object-contain" alt="e-FKDM Logo">
            </div>
            <h2 class="font-heading font-extrabold text-2xl text-slate-900 dark:text-white">Masuk Portal e-FKDM Pemda</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Akses khusus Kesbangpol, Pengurus Kabupaten, & FKDM Kecamatan</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Alamat Email</label>
                <div class="relative">
                    <input type="email" name="email" x-model="email" required autofocus
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:outline-none focus:border-amber-500 transition-colors pl-11 font-medium"
                           placeholder="nama@domain.com">
                    <i data-lucide="mail" class="w-5 h-5 text-slate-400 absolute left-3.5 top-3.5"></i>
                </div>
                @error('email')
                    <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p>
                    @if(str_contains($message, 'approval') || str_contains($message, 'peninjauan') || str_contains($message, 'proses'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Akun Menunggu Persetujuan',
                                        text: "{{ $message }}",
                                        confirmButtonText: 'Saya Mengerti',
                                        confirmButtonColor: '#d97706',
                                        customClass: {
                                            popup: 'rounded-3xl dark:bg-slate-900 dark:text-white border border-slate-200 dark:border-slate-800',
                                            title: 'font-heading font-extrabold text-xl text-amber-500',
                                            confirmButton: 'px-6 py-2.5 rounded-xl font-bold text-xs shadow-lg'
                                        }
                                    });
                                }
                            });
                        </script>
                    @endif
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative">
                    <input type="password" name="password" x-model="password" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:outline-none focus:border-amber-500 transition-colors pl-11 font-medium"
                           placeholder="••••••••">
                    <i data-lucide="lock" class="w-5 h-5 text-slate-400 absolute left-3.5 top-3.5"></i>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600 dark:text-slate-400 font-medium">
                    <input type="checkbox" name="remember" class="rounded bg-slate-100 dark:bg-slate-900 border-slate-300 dark:border-slate-700 text-amber-500 focus:ring-0">
                    <span>Ingat akun saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-extrabold text-sm shadow-lg shadow-amber-500/20 transition-all hover:scale-[1.01] active:scale-95">
                Masuk Portal Sekarang
            </button>
        </form>

        <!-- Demo Accounts Quick Click Fill Panel -->
        <div class="pt-5 border-t border-slate-200 dark:border-slate-800 space-y-3">
            <div class="flex items-center justify-between">
                <span class="font-extrabold text-amber-600 dark:text-amber-400 uppercase tracking-wider text-[11px]">Pilih Akun Demo Pengujian:</span>
                <span class="text-[10px] text-slate-400">Klik untuk isi otomatis</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                <!-- 1. Super Admin -->
                <button type="button" @click="email = 'admin@efkdm.go.id'; password = 'password123'"
                        class="p-2.5 rounded-xl bg-slate-50 hover:bg-amber-50 dark:bg-slate-800/80 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700/80 text-left transition-all group">
                    <div class="font-bold text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400">🛡️ Super Admin Kesbangpol</div>
                    <div class="text-[10px] text-slate-500 dark:text-slate-400 truncate">admin@efkdm.go.id</div>
                </button>

                <!-- 2. Admin Kabupaten -->
                <button type="button" @click="email = 'kabupaten@efkdm.go.id'; password = 'password123'"
                        class="p-2.5 rounded-xl bg-slate-50 hover:bg-amber-50 dark:bg-slate-800/80 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700/80 text-left transition-all group">
                    <div class="font-bold text-amber-600 dark:text-amber-400 group-hover:underline">🏛️ FKDM Kabupaten Bogor</div>
                    <div class="text-[10px] text-slate-500 dark:text-slate-400 truncate">kabupaten@efkdm.go.id</div>
                </button>

                <!-- 3. FKDM Sukamajubaru (Kecamatan) -->
                <button type="button" @click="email = 'fkdm@efkdm.go.id'; password = 'password123'"
                        class="p-2.5 rounded-xl bg-slate-50 hover:bg-amber-50 dark:bg-slate-800/80 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700/80 text-left transition-all group">
                    <div class="font-bold text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400">📍 Ketua FKDM Kec. Sukamajubaru</div>
                    <div class="text-[10px] text-slate-500 dark:text-slate-400 truncate">fkdm@efkdm.go.id</div>
                </button>

                <!-- 4. FKDM Kelurahan Mekar (Desa/Kelurahan Lapangan) -->
                <button type="button" @click="email = 'desa@efkdm.go.id'; password = 'password123'"
                        class="p-2.5 rounded-xl bg-slate-50 hover:bg-amber-50 dark:bg-slate-800/80 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700/80 text-left transition-all group">
                    <div class="font-bold text-emerald-600 dark:text-emerald-400 group-hover:underline">🏘️ Anggota FKDM Kelurahan Mekar</div>
                    <div class="text-[10px] text-slate-500 dark:text-slate-400 truncate">desa@efkdm.go.id</div>
                </button>





            </div>
            <p class="text-[11px] text-center text-slate-400 dark:text-slate-500 italic mt-1">Kata sandi seluruh akun demo: <strong>password123</strong></p>
        </div>

        <div class="text-center text-xs text-slate-600 dark:text-slate-400 pt-3 border-t border-slate-200 dark:border-slate-800 space-y-2">
            <p class="font-semibold text-slate-700 dark:text-slate-300">Belum memiliki akun terdaftar?</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-2">
                <a href="{{ route('register.kabupaten') }}" class="w-full sm:w-auto px-3.5 py-2 rounded-xl bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 border border-indigo-500/30 hover:bg-indigo-600 hover:text-white font-bold transition-all shadow-sm">
                    🏛️ Pendaftaran Kesbangpol / FKDM Kabupaten
                </a>
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-3.5 py-2 rounded-xl bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/30 hover:bg-amber-500 hover:text-slate-950 font-bold transition-all shadow-sm">
                    📍 Pendaftaran Anggota FKDM
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
