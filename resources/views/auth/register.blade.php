@extends('layouts.app', ['hasSidebar' => false])

@section('title', 'Pendaftaran Anggota FKDM - e-FKDM')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Background Image with Overlay (Bright & High Clarity) -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('img/login-bg.jpg') }}" class="w-full h-full object-cover object-center filter brightness-105 scale-100" alt="Background e-FKDM">
        <div class="absolute inset-0 bg-white/20 dark:bg-slate-950/60 backdrop-blur-[1px]"></div>
    </div>

    <div class="max-w-xl w-full bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl p-8 sm:p-10 rounded-3xl border border-white/40 dark:border-slate-800 shadow-2xl relative z-10 space-y-6">
        
        <div class="text-center border-b border-slate-200 dark:border-slate-800 pb-5">
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-3 flex items-center justify-center mx-auto mb-3 shadow-lg">
                <img src="{{ asset('img/logo.png') }}" class="w-full h-full object-contain" alt="e-FKDM Logo">
            </div>
            <h2 class="font-heading font-extrabold text-2xl text-slate-900 dark:text-white">Pendaftaran Akun Anggota FKDM</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftarkan diri Anda untuk dapat mengirim dan memantau status laporan deteksi dini</p>
        </div>

        <!-- Banner Switch Link to Kabupaten Form -->
        <div class="p-3.5 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/30 text-xs text-indigo-900 dark:text-indigo-300 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2 min-w-0">
                <i data-lucide="building-2" class="w-4 h-4 text-indigo-500 shrink-0"></i>
                <span class="truncate">Ingin mendaftar sebagai <strong>Pengurus FKDM Kabupaten / Kesbangpol</strong>?</span>
            </div>
            <a href="{{ route('register.kabupaten') }}" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline shrink-0 whitespace-nowrap">
                Daftar Kabupaten &rarr;
            </a>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="role" value="fkdm_member">

            <div>
                <label class="inline-flex items-center gap-1 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                    <span>Nama Lengkap & Gelar</span> <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:outline-none focus:border-amber-500"
                       placeholder="Contoh: H. Ahmad Subandi, S.E.">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="inline-flex items-center gap-1 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        <span>Email</span> <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:outline-none focus:border-amber-500"
                           placeholder="nama@domain.com">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="inline-flex items-center gap-1 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                        <span>Nomor HP / WhatsApp</span> <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 text-sm focus:outline-none focus:border-amber-500"
                           placeholder="081234567890">
                    @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Cascading Indonesia Regional Selector (Provinsi -> Kabupaten/Kota -> Kecamatan -> Kelurahan) -->
            <div x-data="regionSelector()" x-init="init()" class="space-y-4 pt-2 border-t border-slate-200 dark:border-slate-800">
                <div>
                    <label class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider">
                        <span>📍 Wilayah Tugas (Pilih Dari Daftar Resmi)</span> <span class="text-rose-500">*</span>
                    </label>
                </div>

                <div class="space-y-3">
                    <!-- 1. Provinsi -->
                    <div>
                        <label class="inline-flex items-center gap-1 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            <span>Provinsi</span> <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select x-model="selectedProvinceId" @change="onProvinceChange()" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500">
                                <option value="">-- Pilih Provinsi --</option>
                                <template x-for="p in provinces" :key="p.id">
                                    <option :value="p.id" x-text="p.name"></option>
                                </template>
                            </select>
                            <div x-show="loadingProvinces" class="absolute right-3 top-3.5" style="display: none;">
                                <svg class="animate-spin h-4 w-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Kabupaten / Kota -->
                    <div>
                        <label class="inline-flex items-center gap-1 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            <span>Kabupaten / Kota</span> <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select x-model="selectedRegencyId" @change="onRegencyChange()" :disabled="!selectedProvinceId" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 disabled:opacity-50">
                                <option value="">-- Pilih Kabupaten / Kota --</option>
                                <template x-for="r in regencies" :key="r.id">
                                    <option :value="r.id" x-text="r.name"></option>
                                </template>
                            </select>
                            <div x-show="loadingRegencies" class="absolute right-3 top-3.5" style="display: none;">
                                <svg class="animate-spin h-4 w-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Kecamatan & Kelurahan Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="inline-flex items-center gap-1 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                <span>Kecamatan</span> <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select x-model="selectedDistrictId" @change="onDistrictChange()" :disabled="!selectedRegencyId" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 disabled:opacity-50">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    <template x-for="d in districts" :key="d.id">
                                        <option :value="d.id" x-text="d.name"></option>
                                    </template>
                                </select>
                                <div x-show="loadingDistricts" class="absolute right-3 top-3.5" style="display: none;">
                                    <svg class="animate-spin h-4 w-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="inline-flex items-center gap-1 text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                <span>Kelurahan / Desa</span> <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select x-model="selectedVillageId" @change="onVillageChange()" :disabled="!selectedDistrictId" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 disabled:opacity-50">
                                    <option value="">-- Pilih Kelurahan / Desa --</option>
                                    <template x-for="v in villages" :key="v.id">
                                        <option :value="v.id" x-text="v.name"></option>
                                    </template>
                                </select>
                                <div x-show="loadingVillages" class="absolute right-3 top-3.5" style="display: none;">
                                    <svg class="animate-spin h-4 w-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs passed to Laravel controller -->
                <input type="hidden" name="district" x-model="district" required>
                <input type="hidden" name="subdistrict" x-model="subdistrict" required>
                <input type="hidden" name="village" x-model="village" required>

                @error('district') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @error('subdistrict') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @error('village') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Kata Sandi <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500"
                           placeholder="Minimal 8 Karakter">
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi <span class="text-rose-500">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/90 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500"
                           placeholder="Ulangi Kata Sandi">
                </div>
            </div>

            <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/30 text-[11px] text-amber-800 dark:text-amber-300 flex items-start gap-2">
                <i data-lucide="info" class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5"></i>
                <span>Akun yang baru didaftarkan akan berstatus <strong>Pending Approval</strong>. Pendaftaran Anggota FKDM akan ditinjau & disetujui oleh Admin FKDM Kabupaten terkait.</span>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-white dark:text-slate-950 font-bold text-sm shadow-lg shadow-amber-500/20 transition-all hover:scale-[1.02] active:scale-95 mt-4">
                Kirim Pendaftaran Akun Anggota
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500 dark:text-slate-400">
            Sudah memiliki akun terdaftar? <a href="{{ route('login') }}" class="text-indigo-600 dark:text-amber-400 font-semibold hover:underline">Masuk Ke Sistem</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function regionSelector() {
        return {
            provinces: [],
            regencies: [],
            districts: [],
            villages: [],
            
            selectedProvinceId: '',
            selectedRegencyId: '',
            selectedDistrictId: '',
            selectedVillageId: '',

            district: '{{ old('district', '') }}',
            subdistrict: '{{ old('subdistrict', '') }}',
            village: '{{ old('village', '') }}',

            loadingProvinces: false,
            loadingRegencies: false,
            loadingDistricts: false,
            loadingVillages: false,

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
                this.districts = [];
                this.villages = [];
                this.selectedRegencyId = '';
                this.selectedDistrictId = '';
                this.selectedVillageId = '';
                this.district = '';
                this.subdistrict = '';
                this.village = '';

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

            async onRegencyChange() {
                this.districts = [];
                this.villages = [];
                this.selectedDistrictId = '';
                this.selectedVillageId = '';
                this.subdistrict = '';
                this.village = '';

                const selected = this.regencies.find(r => r.id == this.selectedRegencyId);
                if (selected) {
                    this.district = selected.name;
                }

                if (!this.selectedRegencyId) return;

                this.loadingDistricts = true;
                try {
                    const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${this.selectedRegencyId}.json`);
                    this.districts = await res.json();
                } catch (e) {
                    try {
                        const res = await fetch(`https://ibnux.github.io/data-indonesia/kecamatan/${this.selectedRegencyId}.json`);
                        const data = await res.json();
                        this.districts = data.map(d => ({ id: d.id, name: d.nama }));
                    } catch (err) {}
                } finally {
                    this.loadingDistricts = false;
                }
            },

            async onDistrictChange() {
                this.villages = [];
                this.selectedVillageId = '';
                this.village = '';

                const selected = this.districts.find(d => d.id == this.selectedDistrictId);
                if (selected) {
                    this.subdistrict = selected.name;
                }

                if (!this.selectedDistrictId) return;

                this.loadingVillages = true;
                try {
                    const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${this.selectedDistrictId}.json`);
                    this.villages = await res.json();
                } catch (e) {
                    try {
                        const res = await fetch(`https://ibnux.github.io/data-indonesia/kelurahan/${this.selectedDistrictId}.json`);
                        const data = await res.json();
                        this.villages = data.map(v => ({ id: v.id, name: v.nama }));
                    } catch (err) {}
                } finally {
                    this.loadingVillages = false;
                }
            },

            onVillageChange() {
                const selected = this.villages.find(v => v.id == this.selectedVillageId);
                if (selected) {
                    this.village = selected.name;
                }
            }
        }
    }
</script>
@endpush
