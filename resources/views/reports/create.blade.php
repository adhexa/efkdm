@extends('layouts.app')

@section('title', 'Buat Laporan Deteksi Dini Kejadian - e-FKDM')

@section('content')
    <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-8">

        <div
            class="glass-panel p-6 sm:p-10 rounded-2xl sm:rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl space-y-6 sm:space-y-8">

            <div class="border-b border-slate-200 dark:border-slate-800 pb-4 sm:pb-6">

                <h1 class="font-heading font-extrabold text-xl sm:text-3xl text-slate-900 dark:text-white">Laporkan Potensi
                    Kerawanan / Kejadian</h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 font-medium">Isi formulir berikut
                    dengan informasi sejelas mungkin untuk ditindaklanjuti oleh petugas FKDM & Kesbangpol.</p>
            </div>

            <form action="{{ route('reports.store') }}" method="POST"
                enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Category & Risk Level -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Kategori Isu / Kerawanan <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-200 text-sm focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors">
                            <option value="">-- Pilih Kategori Isu --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Tingkat Kerawanan / Potensi Dampak <span class="text-red-500">*</span>
                        </label>
                        <select name="risk_level" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-slate-200 text-sm focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors">
                            <option value="yellow" {{ old('risk_level') == 'yellow' ? 'selected' : '' }}>🟡 Kuning - Waspada (Monitoring)</option>
                            <option value="red" {{ old('risk_level') == 'red' ? 'selected' : '' }}>🔴 Merah - Bahaya (Urgent / Segera)</option>
                            <option value="green" {{ old('risk_level') == 'green' ? 'selected' : '' }}>🟢 Hijau - Aman (Kondusif)</option>
                        </select>
                        @error('risk_level') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Title -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        Judul Laporan / Peristiwa <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors"
                        placeholder="Contoh: Potensi Gesekan Ormas Terkait Retribusi Parkir Pasar">
                    @error('title') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- Chronology -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        Uraian Kejadian / Kronologi Peristiwa <span class="text-red-500">*</span>
                    </label>
                    <textarea name="chronology" rows="5" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors"
                        placeholder="Jelaskan kronologi, latar belakang, pihak-pihak yang terlibat, dan situasi terkini di lapangan..."></textarea>
                    @error('chronology') <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- Location Fields (Live API Cascading Selector Defaulting to User Registered Work Area) -->
                @php
                    $userDistrict = Auth::user()?->district ?? 'Kabupaten Bogor';
                    $userSubdistrict = Auth::user()?->subdistrict ?? 'Kecamatan Sukamajubaru';
                    $userVillage = Auth::user()?->village ?? 'Kelurahan Mekar';
                @endphp

                <div x-data="createLocationSelector()" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Waktu
                            Kejadian <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="incident_date"
                            value="{{ old('incident_date', date('Y-m-d\TH:i')) }}" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors">
                    </div>

                    <!-- Kabupaten / Kota Field (Readonly - Locked to User Registered District) -->
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Kabupaten / Kota
                        </label>
                        <input type="text" name="district" x-model="district" readonly required
                            class="w-full px-4 py-3 rounded-xl bg-slate-200/80 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 text-sm font-bold cursor-not-allowed focus:outline-none"
                            title="Kabupaten/Kota dikunci sesuai wilayah kerja akun Anda">
                    </div>

                    <!-- Kecamatan Dropdown (Live API Kemendagri) -->
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Kecamatan <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="subdistrict" :value="subdistrict">
                        <select x-model="selectedDistrictId" @change="onSubdistrictChange()" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors">
                            <template x-for="item in districtsList" :key="item.id">
                                <option :value="item.id" x-text="item.name" :selected="item.id == selectedDistrictId"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Kelurahan / Desa Dropdown (Live API Kemendagri) -->
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Kelurahan / Desa <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="village" :value="village">
                        <select x-model="selectedVillageId" @change="onVillageChange()" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors">
                            <template x-for="vItem in villagesList" :key="vItem.id">
                                <option :value="vItem.id" x-text="vItem.name" :selected="vItem.id == selectedVillageId"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div>
                    <label
                        class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Alamat
                        Spesifik / RT/RW / Patokan</label>
                    <input type="text" name="address" value="{{ old('address') }}" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors"
                        placeholder="Jl. Raya Utama No. 12, Samping Bank BNI">
                </div>

                <!-- Map Location Picker -->
                <div class="space-y-2">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Pilih Titik Lokasi Peta (Klik pada Peta untuk Mengatur Koordinat GPS)
                        </label>
                        <button type="button" id="btnDetectLocation"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-700 dark:text-amber-400 border border-amber-500/30 text-xs font-bold transition-all shadow-sm shrink-0">
                            <i data-lucide="crosshair" class="w-3.5 h-3.5 text-amber-500"></i>
                            <span>Gunakan Lokasi Terkini Saya (GPS)</span>
                        </button>
                    </div>
                    <div id="pickerMap"
                        class="w-full h-[220px] sm:h-[280px] rounded-2xl border border-slate-300 dark:border-slate-700 z-10">
                    </div>
                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 font-bold">Latitude:</span>
                            <input type="text" id="latInput" name="latitude" value="{{ old('latitude', '-6.2088') }}"
                                readonly
                                class="w-full px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 text-xs text-amber-600 dark:text-amber-400 font-mono font-bold">
                        </div>
                        <div>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 font-bold">Longitude:</span>
                            <input type="text" id="lngInput" name="longitude" value="{{ old('longitude', '106.8456') }}"
                                readonly
                                class="w-full px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-950 border border-slate-300 dark:border-slate-800 text-xs text-amber-600 dark:text-amber-400 font-mono font-bold">
                        </div>
                    </div>
                </div>

                <!-- Reporter Details Section (Guest Only) -->
                @guest
                    <div class="border-t border-slate-200 dark:border-slate-800 pt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Nama
                                Pelapor <span class="text-red-500">*</span></label>
                            <input type="text" name="reporter_name" value="{{ old('reporter_name') }}" required
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors"
                                placeholder="Nama Lengkap Anda">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Nomor
                                Telepon / WhatsApp</label>
                            <input type="text" name="reporter_phone" value="{{ old('reporter_phone') }}"
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-amber-500 focus:bg-white dark:focus:bg-slate-950 transition-colors"
                                placeholder="081234567890">
                        </div>
                    </div>
                @endguest

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_anonymous" name="is_anonymous" value="1"
                        class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-amber-500 focus:ring-amber-500">
                    <label for="is_anonymous" class="text-xs text-slate-700 dark:text-slate-300 font-medium cursor-pointer">
                        Kirim sebagai <strong>Laporan Rahasia / Anonim</strong> (Nama & Kontak Anda disembunyikan dari
                        publik)
                    </label>
                </div>

                <!-- Attachment upload -->
                <div>
                    <label
                        class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Unggah
                        Foto Bukti Lapangan (Opsional, Max 4MB)</label>
                    <input type="file" name="attachment" accept="image/*"
                        class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-200 dark:file:bg-slate-800 file:text-slate-800 dark:file:text-amber-400 hover:file:bg-slate-300 dark:hover:file:bg-slate-700 transition-colors">
                </div>

                <button type="submit"
                    class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-red-600 via-amber-600 to-amber-500 hover:from-red-500 hover:to-amber-400 text-white font-extrabold text-base shadow-xl shadow-red-600/20 transition-all hover:scale-[1.01] active:scale-95">
                    Kirimkan Laporan Kejadian Sekarang
                </button>
            </form>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function cleanTitleCase(str) {
            if (!str) return '';
            let cleaned = str.trim()
                .replace(/^(KECAMATAN|KEC\.|KELURAHAN|KEL\.|DESA)\s+/i, '');
            return cleaned.toLowerCase().replace(/(?:^|\s|-|\/)\S/g, function(a) { return a.toUpperCase(); });
        }

        function createLocationSelector() {
            return {
                district: '{{ old('district', Auth::user()?->district ?? 'Kabupaten Bogor') }}',
                subdistrict: '{{ old('subdistrict', Auth::user()?->subdistrict ?? '') }}',
                village: '{{ old('village', Auth::user()?->village ?? '') }}',

                selectedDistrictId: '',
                selectedVillageId: '',

                districtsList: [],
                villagesList: [],

                loadingDistricts: false,
                loadingVillages: false,

                async init() {
                    this.districtsList = [];
                    await this.loadDistrictsFromApi();
                },

                async loadDistrictsFromApi() {
                    this.loadingDistricts = true;
                    try {
                        const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/3201.json`);
                        if (res.ok) {
                            const data = await res.json();
                            this.districtsList = data.map(d => ({
                                id: d.id,
                                name: cleanTitleCase(d.name)
                            }));
                        }
                    } catch (e) {
                        try {
                            const res = await fetch('https://ibnux.github.io/data-indonesia/kecamatan/3201.json');
                            if (res.ok) {
                                const data = await res.json();
                                this.districtsList = data.map(d => ({
                                    id: d.id,
                                    name: cleanTitleCase(d.nama)
                                }));
                            }
                        } catch (err) {}
                    } finally {
                        this.loadingDistricts = false;

                        let found = this.districtsList.find(d => 
                            d.name.toLowerCase().includes(this.subdistrict.toLowerCase()) || 
                            (this.subdistrict && this.subdistrict.toLowerCase().includes(d.name.toLowerCase()))
                        );

                        if (found) {
                            this.selectedDistrictId = found.id;
                            this.subdistrict = found.name;
                        } else if (this.districtsList.length > 0) {
                            this.selectedDistrictId = this.districtsList[0].id;
                            this.subdistrict = this.districtsList[0].name;
                        }

                        await this.loadVillagesFromApi();
                    }
                },

                async onSubdistrictChange() {
                    const sel = this.districtsList.find(d => d.id == this.selectedDistrictId);
                    if (sel) {
                        this.subdistrict = sel.name;
                    }
                    await this.loadVillagesFromApi();
                },

                async loadVillagesFromApi() {
                    if (!this.selectedDistrictId) return;

                    this.loadingVillages = true;
                    try {
                        const res = await fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${this.selectedDistrictId}.json`);
                        if (res.ok) {
                            const data = await res.json();
                            this.villagesList = data.map(v => ({
                                id: v.id,
                                name: cleanTitleCase(v.name)
                            }));
                        }
                    } catch (e) {
                        try {
                            const res = await fetch(`https://ibnux.github.io/data-indonesia/kelurahan/${this.selectedDistrictId}.json`);
                            if (res.ok) {
                                const data = await res.json();
                                this.villagesList = data.map(v => ({
                                    id: v.id,
                                    name: cleanTitleCase(v.nama)
                                }));
                            }
                        } catch (err) {}
                    } finally {
                        this.loadingVillages = false;

                        let foundV = this.villagesList.find(v => 
                            v.name.toLowerCase().includes(this.village.toLowerCase()) || 
                            (this.village && this.village.toLowerCase().includes(v.name.toLowerCase()))
                        );

                        if (foundV) {
                            this.selectedVillageId = foundV.id;
                            this.village = foundV.name;
                        } else if (this.villagesList.length > 0) {
                            this.selectedVillageId = this.villagesList[0].id;
                            this.village = this.villagesList[0].name;
                        }
                    }
                },

                onVillageChange() {
                    const selV = this.villagesList.find(v => v.id == this.selectedVillageId);
                    if (selV) {
                        this.village = selV.name;
                    }
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const defaultLat = parseFloat(document.getElementById('latInput').value) || -6.2088;
            const defaultLng = parseFloat(document.getElementById('lngInput').value) || 106.8456;

            const map = L.map('pickerMap').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

            function updateInputs(lat, lng) {
                document.getElementById('latInput').value = lat.toFixed(7);
                document.getElementById('lngInput').value = lng.toFixed(7);
            }

            map.on('click', function (e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                marker.setLatLng([lat, lng]);
                updateInputs(lat, lng);
            });

            // HTML5 Geolocation API User Location Detection
            function locateUserGPS() {
                if ("geolocation" in navigator) {
                    const btn = document.getElementById('btnDetectLocation');
                    if (btn) {
                        btn.innerHTML = `<i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> <span>Mendeteksi GPS...</span>`;
                        if (window.lucide) lucide.createIcons();
                    }

                    navigator.geolocation.getCurrentPosition(function (pos) {
                        const userLat = pos.coords.latitude;
                        const userLng = pos.coords.longitude;

                        map.setView([userLat, userLng], 16);
                        marker.setLatLng([userLat, userLng]);
                        updateInputs(userLat, userLng);

                        if (btn) {
                            btn.innerHTML = `<i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-500"></i> <span class="text-emerald-600 dark:text-emerald-400 font-bold">Lokasi GPS Terdeteksi</span>`;
                            if (window.lucide) lucide.createIcons();
                        }
                    }, function (err) {
                        console.warn("Gagal mendeteksi lokasi GPS:", err.message);
                        if (btn) {
                            btn.innerHTML = `<i data-lucide="crosshair" class="w-3.5 h-3.5"></i> <span>Gunakan Lokasi Terkini Saya (GPS)</span>`;
                            if (window.lucide) lucide.createIcons();
                        }
                    }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
                }
            }

            // Auto-detect location on load
            locateUserGPS();

            // Button click trigger
            const btnDetect = document.getElementById('btnDetectLocation');
            if (btnDetect) {
                btnDetect.addEventListener('click', locateUserGPS);
            }
        });
    </script>
@endpush