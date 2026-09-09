@extends('layouts.app')

@section('title', 'Ganti Password Akun - e-FKDM')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="glass-panel p-6 sm:p-10 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl space-y-6">
        
        <div class="flex items-center gap-4 border-b border-slate-200 dark:border-slate-800 pb-5">
            <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-500 shrink-0 shadow-sm">
                <i data-lucide="key-round" class="w-6 h-6"></i>
            </div>
            <div>
                <h1 class="font-heading font-extrabold text-2xl text-slate-900 dark:text-white">Ganti Password Akun</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Perbarui kata sandi demi keamanan akun FKDM / Kesbangpol Anda</p>
            </div>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-5" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
            @csrf

            <!-- Password Saat Ini -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                    Password Saat Ini <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input :type="showCurrent ? 'text' : 'password'" name="current_password" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors pr-11 font-medium"
                        placeholder="Masukkan password saat ini">
                    <button type="button" @click="showCurrent = !showCurrent" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i data-lucide="eye" x-show="!showCurrent" class="w-4.5 h-4.5"></i>
                        <i data-lucide="eye-off" x-show="showCurrent" class="w-4.5 h-4.5" style="display: none;"></i>
                    </button>
                </div>
                @error('current_password') <p class="text-xs text-red-500 mt-1.5 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Password Baru -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                    Password Baru (Minimal 8 Karakter) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input :type="showNew ? 'text' : 'password'" name="new_password" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors pr-11 font-medium"
                        placeholder="Masukkan password baru">
                    <button type="button" @click="showNew = !showNew" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i data-lucide="eye" x-show="!showNew" class="w-4.5 h-4.5"></i>
                        <i data-lucide="eye-off" x-show="showNew" class="w-4.5 h-4.5" style="display: none;"></i>
                    </button>
                </div>
                @error('new_password') <p class="text-xs text-red-500 mt-1.5 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Konfirmasi Password Baru -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input :type="showConfirm ? 'text' : 'password'" name="new_password_confirmation" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors pr-11 font-medium"
                        placeholder="Ulangi password baru">
                    <button type="button" @click="showConfirm = !showConfirm" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <i data-lucide="eye" x-show="!showConfirm" class="w-4.5 h-4.5"></i>
                        <i data-lucide="eye-off" x-show="showConfirm" class="w-4.5 h-4.5" style="display: none;"></i>
                    </button>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-400 hover:from-amber-400 hover:to-amber-300 text-slate-950 font-extrabold text-sm shadow-xl shadow-amber-500/20 transition-all hover:scale-[1.01] active:scale-95 flex items-center justify-center gap-2">
                    <i data-lucide="check-circle-2" class="w-4.5 h-4.5"></i>
                    <span>Simpan Perubahan Password</span>
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
