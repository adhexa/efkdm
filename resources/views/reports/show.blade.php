@extends('layouts.app')

@section('title', 'Rincian Laporan #' . $report->report_number . ' - e-FKDM')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">

    <!-- Top Action Bar -->
    <div class="flex items-center justify-between gap-4">
        <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Daftar Laporan
        </a>
    </div>

    <!-- Main Detail Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

        <!-- Left 2 Cols: Main Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-lg space-y-6">
                <!-- Header badge info -->
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 dark:border-slate-800 pb-5">
                    <div>
                        <span class="font-mono text-xs sm:text-sm font-extrabold text-amber-600 dark:text-amber-400 tracking-wider uppercase">{{ $report->report_number }}</span>
                        <h1 class="font-heading font-extrabold text-xl sm:text-3xl text-slate-900 dark:text-white mt-1 leading-tight">{{ $report->title }}</h1>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $report->risk_badge_class }}">
                            {{ $report->risk_label }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $report->status_badge_class }}">
                            {{ $report->status_label }}
                        </span>
                    </div>
                </div>

                <!-- Chronology -->
                <div>
                    <h3 class="font-heading font-bold text-slate-800 dark:text-slate-200 text-xs sm:text-sm uppercase tracking-wider mb-2.5">Uraian / Kronologi Kejadian</h3>
                    <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-300 text-sm leading-relaxed whitespace-pre-line shadow-inner">
                        {{ $report->chronology }}
                    </div>
                </div>

                <!-- Incident Location Map -->
                @if($report->latitude && $report->longitude)
                    <div>
                        <h3 class="font-heading font-bold text-slate-800 dark:text-slate-200 text-xs sm:text-sm uppercase tracking-wider mb-2.5">Lokasi Kejadian pada Peta</h3>
                        <div id="detailMap" class="w-full h-[250px] rounded-2xl border border-slate-300 dark:border-slate-800 shadow-md"></div>
                    </div>
                @endif
            </div>

            <!-- Timeline Action Logs -->
            <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-lg space-y-6">
                <h3 class="font-heading font-bold text-base sm:text-lg text-slate-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="history" class="w-5 h-5 text-amber-500"></i>
                    Riwayat Tindak Lanjut & Catatan Lapangan
                </h3>

                <div class="relative border-l-2 border-slate-200 dark:border-slate-800 ml-3 space-y-6 pl-6 pt-2">
                    @forelse($report->actions as $action)
                        <div class="relative group">
                            <div class="absolute -left-[31px] top-0.5 w-4 h-4 rounded-full bg-slate-100 dark:bg-slate-900 border-2 border-amber-500 group-hover:scale-125 transition-transform"></div>
                            
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-1">
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $action->user->name ?? 'Sistem' }} ({{ $action->user->role_badge ?? 'Petugas' }})</span>
                                <span>{{ $action->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>
                            <p class="text-xs text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/60 p-3.5 rounded-xl border border-slate-200 dark:border-slate-800/80 leading-relaxed shadow-sm">
                                {{ $action->note }}
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 dark:text-slate-400 italic">Belum ada rincian tindak lanjut tercatat.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Metadata & Officer Update Panel -->
        <div class="space-y-6">
            
            <!-- Metadata Card -->
            <div class="glass-panel p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-lg space-y-4">
                <h3 class="font-heading font-bold text-base text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-3">Informasi Metadata</h3>

                <div class="space-y-3.5 text-xs">
                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block font-semibold">Kategori Isu:</span>
                        <strong class="text-slate-900 dark:text-slate-200 text-sm font-bold">{{ $report->category->name }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block font-semibold">Waktu Kejadian:</span>
                        <strong class="text-slate-900 dark:text-slate-200 text-sm font-bold">{{ $report->incident_date->format('d F Y - H:i') }} WIB</strong>
                    </div>

                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block font-semibold">Wilayah:</span>
                        <strong class="text-slate-900 dark:text-slate-200 text-sm font-bold">{{ $report->village }}, {{ $report->subdistrict }}</strong>
                        @if($report->district)
                            <span class="block text-[11px] text-slate-500 dark:text-slate-400">{{ $report->district }}</span>
                        @endif
                    </div>

                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block font-semibold">Alamat Spesifik:</span>
                        <strong class="text-slate-900 dark:text-slate-200 font-bold leading-snug block mt-0.5">{{ $report->address }}</strong>
                    </div>

                    <div>
                        <span class="text-slate-500 dark:text-slate-400 block font-semibold">Pelapor:</span>
                        @auth
                            <strong class="text-amber-600 dark:text-amber-400 font-extrabold text-sm">{{ $report->is_anonymous ? 'Masyarakat Anonim' : $report->reporter_name }}</strong>
                            @if($report->reporter_phone && Auth::user()->isFkdmMember())
                                <span class="block text-slate-600 dark:text-slate-400 mt-1 font-mono font-semibold">
                                    <i data-lucide="phone" class="w-3.5 h-3.5 inline text-amber-500 mr-1"></i>{{ $report->reporter_phone }}
                                </span>
                            @endif
                        @else
                            <strong class="text-slate-800 dark:text-slate-200 font-bold text-sm">Warga Masyarakat / Tim FKDM</strong>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Officer Action Form (Only for Users Authorized to Manage this Report) -->
            @auth
                @if(Auth::user()->canManageReport($report))
                    <div class="glass-panel p-6 rounded-3xl border border-amber-300 dark:border-amber-500/30 bg-amber-50/50 dark:bg-amber-500/5 shadow-lg space-y-4">
                        <h3 class="font-heading font-bold text-base text-amber-800 dark:text-amber-300 flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-5 h-5 text-amber-500"></i>
                            Panel Petugas FKDM / Kesbangpol
                        </h3>

                        <form action="{{ route('reports.update_status', $report->id) }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Ubah Status Laporan</label>
                                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-white font-semibold focus:outline-none focus:border-amber-500">
                                    <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                    <option value="verified" {{ $report->status == 'verified' ? 'selected' : '' }}>Terverifikasi FKDM</option>
                                    <option value="in_progress" {{ $report->status == 'in_progress' ? 'selected' : '' }}>Dalam Penanganan Lapangan</option>
                                    <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Selesai / Kondusif</option>
                                    <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Ditolak / Tidak Valid</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Penetapan Tingkat Kerawanan</label>
                                <select name="risk_level" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-white font-semibold focus:outline-none focus:border-amber-500">
                                    <option value="green" {{ $report->risk_level == 'green' ? 'selected' : '' }}>🟢 Hijau - Aman (Kondusif)</option>
                                    <option value="yellow" {{ $report->risk_level == 'yellow' ? 'selected' : '' }}>🟡 Kuning - Waspada (Monitoring)</option>
                                    <option value="red" {{ $report->risk_level == 'red' ? 'selected' : '' }}>🔴 Merah - Bahaya (Urgent / Segera)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Catatan Tindak Lanjut & Rekomendasi</label>
                                <textarea name="note" rows="3" required
                                          class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-white focus:outline-none focus:border-amber-500 placeholder-slate-400 dark:placeholder-slate-500"
                                          placeholder="Tuliskan hasil verifikasi lapangan, koordinasi dengan aparat, atau instruksi tindak lanjut..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-extrabold text-xs shadow-md transition-all hover:scale-[1.01] active:scale-95">
                                Simpan Perubahan Status
                            </button>
                        </form>
                    </div>
                @endif
            @endauth

        </div>

    </div>

</div>
@endsection

@push('scripts')
@if($report->latitude && $report->longitude)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const lat = {{ $report->latitude }};
        const lng = {{ $report->longitude }};
        const map = L.map('detailMap').setView([lat, lng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        L.circleMarker([lat, lng], {
            radius: 10,
            fillColor: '#f59e0b',
            color: '#ffffff',
            weight: 2,
            opacity: 1,
            fillOpacity: 0.9
        }).addTo(map).bindPopup('Lokasi Kejadian: {{ $report->address }}').openPopup();
    });
</script>
@endif
@endpush
