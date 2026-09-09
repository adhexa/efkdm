@extends('layouts.app', ['hasSidebar' => false])

@section('title', 'Pendaftaran Pengurus FKDM Kabupaten & Kesbangpol - e-FKDM')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Background Image with Overlay (Bright & High Clarity) -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('img/login-bg.jpg') }}" class="w-full h-full object-cover object-center filter brightness-105 scale-100" alt="Background e-FKDM">
        <div class="absolute inset-0 bg-white/20 dark:bg-slate-950/60 backdrop-blur-[1px]"></div>
    </div>

    <div class="max-w-2xl w-full bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl p-8 sm:p-10 rounded-3xl border border-white/40 dark:border-slate-800 shadow-2xl relative z-10 space-y-6">
        
        <div class="text-center border-b border-slate-200 dark:border-slate-800 pb-5">
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-3 flex items-center justify-center mx-auto mb-3 shadow-lg">
                <img src="{{ asset('img/logo.png') }}" class="w-full h-full object-contain" alt="e-FKDM Logo">
            </div>
            <h2 class="font-heading font-extrabold text-2xl text-slate-900 dark:text-white">Pendaftaran Pengurus FKDM Kabupaten / Kesbangpol</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                Formulir pendaftaran resmi akun Pengurus FKDM Tingkat Kabupaten & Pejabat Kesbangpol Pemda.
            </p>
        </div>

        <!-- Banner Switch Link to Member Form -->
        <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-xs text-amber-900 dark:text-amber-300 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2 min-w-0">
                <i data-lucide="user-check" class="w-4 h-4 text-amber-500 shrink-0"></i>
                <span class="truncate">Ingin mendaftar sebagai <strong>Anggota FKDM (Kecamatan/Kelurahan)</strong>?</span>
            </div>
            <a href="{{ route('register') }}" class="text-[11px] font-bold text-amber-600 dark:text-amber-400 hover:underline shrink-0 whitespace-nowrap">
                Daftar Anggota &rarr;
            </a>
        </div>

        <form action="{{ route('register.kabupaten') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Form Notice -->
            <div class="p-4 rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/30 text-xs text-indigo-900 dark:text-indigo-300 flex items-start gap-3">
                <i data-lucide="shield-check" class="w-5 h-5 text-indigo-500 shrink-0 mt-0.5"></i>
                <div>
                    <span class="font-bold block">Verifikasi Surat Permohonan Resmi</span>
                    <p class="text-[11px] text-indigo-700 dark:text-indigo-300 mt-0.5">
                        Pendaftaran akun tingkat Kabupaten ini memerlukan lampiran berkas <strong>Surat Permohonan Pembuatan Akun</strong> resmi yang ditandatangani oleh <strong>Kepala Dinas / Sekretaris Bakesbangpol</strong>.
                    </p>
                </div>
            </div>

            <!-- Nama & Jabatan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        <span>Nama Lengkap & NIP/NIK</span> <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 font-medium"
                           placeholder="Contoh: Drs. H. Suryana, M.Si">
                    @error('name') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        <span>Jabatan Resmi</span> <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="position_title" value="{{ old('position_title') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 font-medium"
                           placeholder="Contoh: Ketua FKDM Kabupaten / Kabid Kewaspadaan">
                    @error('position_title') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Instansi -->
            <div>
                <label class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                    <span>Nama Instansi / Lembaga</span> <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="institution_name" value="{{ old('institution_name', 'Badan Kesbangpol') }}" required
                       class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-indigo-500 font-medium"
                       placeholder="Badan Kesbangpol / FKDM">
                @error('institution_name') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Email & Kontak -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        <span>Email Kedinasan / Resmi</span> <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 font-medium"
                           placeholder="nama@domain.go.id">
                    @error('email') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        <span>Nomor HP / WhatsApp</span> <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 font-medium"
                           placeholder="081234567890">
                    @error('phone') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Cascading Indonesia Regional Selector (Provinsi -> Kabupaten/Kota) -->
            <div x-data="kabupatenRegionSelector()" x-init="init()" class="space-y-4 pt-2 border-t border-slate-200 dark:border-slate-800">
                <div>
                    <label class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">
                        <span>🏛️ Wilayah Kerja Kabupaten / Kota</span> <span class="text-rose-500">*</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- 1. Provinsi -->
                    <div>
                        <label class="inline-flex items-center gap-1 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            <span>Provinsi</span> <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select x-model="selectedProvinceId" @change="onProvinceChange()" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-indigo-500 font-medium">
                                <option value="">-- Pilih Provinsi --</option>
                                <template x-for="p in provinces" :key="p.id">
                                    <option :value="p.id" x-text="p.name"></option>
                                </template>
                            </select>
                            <div x-show="loadingProvinces" class="absolute right-3 top-3.5" style="display: none;">
                                <svg class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Kabupaten / Kota -->
                    <div>
                        <label class="inline-flex items-center gap-1 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            <span>Kabupaten / Kota</span> <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select x-model="selectedRegencyId" @change="onRegencyChange()" :disabled="!selectedProvinceId" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-indigo-500 disabled:opacity-50 font-medium">
                                <option value="">-- Pilih Kabupaten / Kota --</option>
                                <template x-for="r in regencies" :key="r.id">
                                    <option :value="r.id" x-text="r.name"></option>
                                </template>
                            </select>
                            <div x-show="loadingRegencies" class="absolute right-3 top-3.5" style="display: none;">
                                <svg class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="district" x-model="district" required>
                @error('district') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <!-- Upload Surat Permohonan Pembuatan Akun -->
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/80 space-y-4">
                <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-3">
                    <i data-lucide="file-text" class="w-4 h-4 text-indigo-500"></i>
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-800 dark:text-slate-200">Dokumen Surat Permohonan Pembuatan Akun</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            <span>Nomor Surat Permohonan</span> <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="sk_number" value="{{ old('sk_number') }}" required
                               class="w-full px-4 py-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:outline-none focus:border-indigo-500 font-medium"
                               placeholder="Contoh: 005/123-Kesbangpol/2026">
                        @error('sk_number') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            <span>Unggah Berkas Surat Permohonan</span> <span class="text-rose-500">*</span>
                        </label>
                        <input type="file" name="sk_document" required accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full text-xs text-slate-700 dark:text-slate-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-500/20 dark:file:text-indigo-300 hover:file:bg-indigo-100 cursor-pointer">
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Ditandatangani oleh Kepala Dinas / Sekretaris (Format: PDF, JPG, PNG)</p>
                        @error('sk_document') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        <span>Kata Sandi</span> <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-indigo-500"
                           placeholder="Minimal 8 Karakter">
                    @error('password') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        <span>Konfirmasi Kata Sandi</span> <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-indigo-500"
                           placeholder="Ulangi Kata Sandi">
                </div>
            </div>

            <button type="submit" class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-extrabold text-sm shadow-xl shadow-indigo-600/20 transition-all hover:scale-[1.01] active:scale-95 mt-2">
                Kirimkan Permohonan Akun Kabupaten
            </button>
        </form>

        <div class="text-center text-xs text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-200 dark:border-slate-800">
            Sudah memiliki akun terdaftar? <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">Masuk Ke Sistem</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function kabupatenRegionSelector() {
        return {
            provinces: [],
            regencies: [],
            
            selectedProvinceId: '',
            selectedRegencyId: '',

            district: '{{ old('district', '') }}',

            loadingProvinces: false,
            loadingRegencies: false,

            async init() {
                this.loadingProvinces = true;
                try {
                    const res = await fetch('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
                    this.provinces = await res.json();
                } catch (e) {
                    try {
                        const res = await fetch('https://ibnux.github.io/data-indonesia/provinsi.json');
                        const data = await res.json();
                        this.provinces = data.map(p => ({ id: p.id, name: p.nama }));
                    } catch (err) {}
                } finally {
                    this.loadingProvinces = false;
                }
            },

            async onProvinceChange() {
                this.regencies = [];
                this.selectedRegencyId = '';
                this.district = '';

                if (!this.selectedProvinceId) return;

                this.loadingRegencies = true;
                try {
                    const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${this.selectedProvinceId}.json`);
                    this.regencies = await res.json();
                } catch (e) {
                    try {
                        const res = await fetch(`https://ibnux.github.io/data-indonesia/kabupaten/${this.selectedProvinceId}.json`);
                        const data = await res.json();
                        this.regencies = data.map(r => ({ id: r.id, name: r.nama }));
                    } catch (err) {}
                } finally {
                    this.loadingRegencies = false;
                }
            },

            onRegencyChange() {
                const selected = this.regencies.find(r => r.id == this.selectedRegencyId);
                if (selected) {
                    this.district = selected.name;
                } else {
                    this.district = '';
                }
            }
        }
    }
</script>
@endpush
