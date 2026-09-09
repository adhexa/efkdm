@extends('layouts.app')

@section('title', 'Manajemen & Approval Anggota FKDM - e-FKDM')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="{ activeTab: 'pending' }">

    <!-- Header Banner -->
    <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-slate-800 shadow-2xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold uppercase tracking-wider mb-2">
                <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                Verifikasi & Pengesahan Pendaftaran
            </div>
            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 dark:text-white">
                Manajemen & Approval Anggota FKDM
            </h1>
            <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-400 mt-1 font-medium">
                Lingkup Verifikasi: <strong class="text-amber-600 dark:text-amber-400">
                    @if($currentUser->role === 'vendor_admin')
                        Pendaftaran Pengurus FKDM Kabupaten & Kesbangpol Pemda
                    @else
                        Anggota FKDM Kecamatan/Kelurahan {{ $currentUser->district ? 'Wilayah ' . $currentUser->district : '' }}
                    @endif
                </strong>
            </p>
        </div>

        <div class="flex items-center gap-3">
            <div class="px-4 py-3 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-center">
                <p class="text-2xl font-extrabold text-amber-500">{{ $pendingMembers->count() }}</p>
                <p class="text-[10px] text-amber-700 dark:text-amber-300 font-bold uppercase tracking-wider">Butuh Approval</p>
            </div>
            <div class="px-4 py-3 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-center">
                <p class="text-2xl font-extrabold text-emerald-500">{{ $activeMembers->count() }}</p>
                <p class="text-[10px] text-emerald-700 dark:text-emerald-300 font-bold uppercase tracking-wider">Anggota Aktif</p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-slate-300 dark:border-slate-800 gap-2">
        <button @click="activeTab = 'pending'"
                :class="activeTab === 'pending' ? 'border-amber-500 text-amber-500 font-bold' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
                class="py-3 px-5 border-b-2 text-sm transition-all flex items-center gap-2">
            <i data-lucide="clock" class="w-4 h-4"></i>
            <span>Menunggu Approval</span>
            <span class="px-2 py-0.5 rounded-full text-xs bg-amber-500/20 text-amber-600 dark:text-amber-300 font-bold">{{ $pendingMembers->count() }}</span>
        </button>
        
        <button @click="activeTab = 'active'"
                :class="activeTab === 'active' ? 'border-emerald-500 text-emerald-500 font-bold' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
                class="py-3 px-5 border-b-2 text-sm transition-all flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            <span>Anggota Terverifikasi (Aktif)</span>
            <span class="px-2 py-0.5 rounded-full text-xs bg-emerald-500/20 text-emerald-600 dark:text-emerald-300 font-bold">{{ $activeMembers->count() }}</span>
        </button>

        <button @click="activeTab = 'rejected'"
                :class="activeTab === 'rejected' ? 'border-red-500 text-red-500 font-bold' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200'"
                class="py-3 px-5 border-b-2 text-sm transition-all flex items-center gap-2">
            <i data-lucide="x-circle" class="w-4 h-4"></i>
            <span>Pendaftaran Ditolak</span>
            <span class="px-2 py-0.5 rounded-full text-xs bg-red-500/20 text-red-600 dark:text-red-300 font-bold">{{ $rejectedMembers->count() }}</span>
        </button>
    </div>

    <!-- TAB 1: PENDING MEMBERS -->
    <div x-show="activeTab === 'pending'" class="space-y-4">
        @if($pendingMembers->isEmpty())
            <div class="glass-panel p-12 text-center rounded-3xl border border-slate-800 space-y-3">
                <i data-lucide="check-check" class="w-12 h-12 text-emerald-400 mx-auto opacity-80"></i>
                <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white">Tidak Ada Pendaftaran Menunggu</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Seluruh pendaftaran anggota FKDM di wilayah Anda telah diperiksa dan disetujui.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pendingMembers as $member)
                    <div class="glass-panel p-6 rounded-2xl border border-amber-500/30 dark:border-amber-500/20 shadow-lg space-y-4 flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-500/40 flex items-center justify-center font-bold text-amber-500 text-lg">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/20 text-amber-600 dark:text-amber-300 border border-amber-500/30">
                                    Pending Approval
                                </span>
                            </div>

                            <div>
                                <h3 class="font-heading font-bold text-base text-slate-900 dark:text-white">{{ $member->name }}</h3>
                                <p class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold whitespace-nowrap">{{ $member->role_badge }}</p>
                            </div>

                            <div class="space-y-1.5 text-xs text-slate-700 dark:text-slate-300 font-medium">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-amber-500 shrink-0"></i>
                                    <span>{{ $member->district ?? 'Kabupaten Bogor' }}</span>
                                </div>
                                @if($member->position_title)
                                    <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-bold">
                                        <i data-lucide="briefcase" class="w-4 h-4 shrink-0"></i>
                                        <span>{{ $member->position_title }}</span>
                                    </div>
                                @endif
                                @if($member->sk_number)
                                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400 font-semibold">
                                        <i data-lucide="file-text" class="w-4 h-4 text-amber-500 shrink-0"></i>
                                        <span>No. SK: {{ $member->sk_number }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center gap-2">
                                    <i data-lucide="navigation" class="w-4 h-4 text-slate-400 shrink-0"></i>
                                    <span>{{ $member->subdistrict }} / {{ $member->village }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="mail" class="w-4 h-4 text-slate-400 shrink-0"></i>
                                    <span class="truncate">{{ $member->email }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="phone" class="w-4 h-4 text-slate-400 shrink-0"></i>
                                    <span>{{ $member->phone }}</span>
                                </div>
                                @if($member->sk_document_path)
                                    <div class="pt-2">
                                        <a href="{{ asset('storage/' . $member->sk_document_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-50 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/30 text-[11px] font-bold hover:bg-indigo-100 dark:hover:bg-indigo-500/30 transition-all shadow-sm">
                                            <i data-lucide="download" class="w-3.5 h-3.5"></i> Periksa / Unduh Berkas SK Resmi
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex items-center gap-3">
                            <form action="{{ route('members.approve', $member->id) }}" method="POST" class="w-1/2">
                                @csrf
                                <button type="submit" class="w-full py-2.5 px-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white font-bold text-xs shadow-md transition-all hover:scale-105 active:scale-95 flex items-center justify-center gap-1.5">
                                    <i data-lucide="user-check" class="w-4 h-4"></i>
                                    <span>Setujui (Approve)</span>
                                </button>
                            </form>

                            <form action="{{ route('members.reject', $member->id) }}" method="POST" class="w-1/2">
                                @csrf
                                <button type="submit" onclick="return confirm('Yakin ingin menolak pendaftaran akun ini?')" class="w-full py-2.5 px-3 rounded-xl bg-slate-200 dark:bg-slate-800 hover:bg-red-500/20 text-slate-700 dark:text-slate-300 hover:text-red-500 dark:hover:text-red-400 font-bold text-xs border border-slate-300 dark:border-slate-700 transition-all flex items-center justify-center gap-1.5">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                    <span>Tolak</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- TAB 2: ACTIVE MEMBERS -->
    <div x-show="activeTab === 'active'" class="space-y-4" style="display: none;">
        @if($activeMembers->isEmpty())
            <div class="glass-panel p-12 text-center rounded-3xl border border-slate-800 space-y-3">
                <i data-lucide="users" class="w-12 h-12 text-slate-500 mx-auto"></i>
                <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white">Belum Ada Anggota Aktif</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Anggota FKDM yang disetujui akan ditampilkan di sini.</p>
            </div>
        @else
            <div class="glass-panel rounded-3xl border border-slate-300 dark:border-slate-800 overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-100/80 dark:bg-slate-900/80 text-xs uppercase font-bold text-slate-600 dark:text-slate-400 tracking-wider">
                                <th class="py-4 px-6">Anggota FKDM</th>
                                <th class="py-4 px-6">Kontak</th>
                                <th class="py-4 px-6">Tingkat / Jabatan</th>
                                <th class="py-4 px-6">Kabupaten / Wilayah</th>
                                <th class="py-4 px-6">Kecamatan / Kelurahan</th>
                                <th class="py-4 px-6">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-200">
                            @foreach($activeMembers as $member)
                                <tr class="hover:bg-slate-100/50 dark:hover:bg-slate-800/40 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center font-bold text-emerald-500 text-sm shrink-0">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm text-slate-900 dark:text-white">{{ $member->name }}</p>
                                                <p class="text-[11px] text-slate-500 dark:text-slate-400">ID: #FKDM-{{ str_pad($member->id, 4, '0', STR_PAD_LEFT) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 space-y-0.5">
                                        <p class="truncate">{{ $member->email }}</p>
                                        <p class="text-slate-500 dark:text-slate-400 font-mono">{{ $member->phone }}</p>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col gap-1 items-start">
                                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold whitespace-nowrap bg-indigo-500/10 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 border border-indigo-500/30 shadow-sm">
                                                {{ $member->role_badge }}
                                            </span>
                                            @if($member->position_title)
                                                <span class="text-[11px] font-semibold text-slate-600 dark:text-slate-400">
                                                    {{ $member->position_title }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 font-bold text-amber-600 dark:text-amber-400">
                                        {{ $member->district ?? 'Kabupaten Bogor' }}
                                    </td>
                                    <td class="py-4 px-6">
                                        {{ $member->subdistrict }} / {{ $member->village }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-600 dark:text-emerald-300 border border-emerald-500/30">
                                            <i data-lucide="check" class="w-3 h-3"></i> Aktif & Terverifikasi
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- TAB 3: REJECTED MEMBERS -->
    <div x-show="activeTab === 'rejected'" class="space-y-4" style="display: none;">
        @if($rejectedMembers->isEmpty())
            <div class="glass-panel p-12 text-center rounded-3xl border border-slate-800 space-y-3">
                <i data-lucide="smile" class="w-12 h-12 text-slate-500 mx-auto"></i>
                <h3 class="font-heading font-bold text-lg text-slate-900 dark:text-white">Tidak Ada Pendaftaran Ditolak</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Tidak ada data pendaftaran yang ditolak saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($rejectedMembers as $member)
                    <div class="glass-panel p-6 rounded-2xl border border-red-500/30 dark:border-red-500/20 space-y-3 opacity-75">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-heading font-bold text-base text-slate-900 dark:text-white">{{ $member->name }}</h3>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-500/20 text-red-500 border border-red-500/30">
                                Ditolak
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $member->email }} ({{ $member->phone }})</p>
                        <p class="text-xs text-slate-400">{{ $member->district }} - {{ $member->subdistrict }}</p>

                        <form action="{{ route('members.approve', $member->id) }}" method="POST" class="pt-3">
                            @csrf
                            <button type="submit" class="w-full py-2 px-3 rounded-xl bg-emerald-600/20 hover:bg-emerald-600 text-emerald-400 hover:text-white font-bold text-xs transition-colors">
                                Pulihkan & Approve Akun
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
