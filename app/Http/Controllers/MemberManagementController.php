<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberManagementController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();

        if (!$currentUser || !($currentUser->isAdmin() || $currentUser->isFkdmKabupaten())) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola pendaftaran anggota FKDM.');
        }

        $query = User::query();

        // 1. Jika Vendor / Fasilitator Swasta: HANYA permohonan Pendaftaran Akun FKDM Kabupaten / Kesbangpol Pemda
        if ($currentUser->role === 'vendor_admin') {
            $query->where('role', 'fkdm_kabupaten');
        }
        // 2. Jika Kesbangpol Pemda / FKDM Kabupaten: HANYA permohonan Anggota FKDM di wilayah Kabupatennya sendiri
        else {
            $query->where('role', 'fkdm_member');
            if (!empty($currentUser->district)) {
                $userDistrict = mb_strtolower(trim($currentUser->district));
                $query->whereRaw('LOWER(district) = ?', [$userDistrict]);
            }
        }

        // Optional search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('institution_name', 'like', "%{$search}%")
                  ->orWhere('position_title', 'like', "%{$search}%")
                  ->orWhere('subdistrict', 'like', "%{$search}%")
                  ->orWhere('village', 'like', "%{$search}%");
            });
        }

        $pendingMembers = (clone $query)->where('status', 'pending')->latest()->get();
        $activeMembers = (clone $query)->where('status', 'active')->latest()->get();
        $rejectedMembers = (clone $query)->where('status', 'rejected')->latest()->get();

        return view('members.index', compact('pendingMembers', 'activeMembers', 'rejectedMembers', 'currentUser'));
    }

    public function approve($id)
    {
        $currentUser = Auth::user();

        if (!$currentUser || !($currentUser->isAdmin() || $currentUser->isFkdmKabupaten())) {
            abort(403, 'Akses ditolak.');
        }

        $user = User::findOrFail($id);

        if ($currentUser->role === 'vendor_admin' && $user->role !== 'fkdm_kabupaten') {
            return back()->with('error', 'Fasilitator Swasta hanya berhak menyetujui pendaftaran Pengurus FKDM Kabupaten / Kesbangpol Pemda.');
        }

        if ($currentUser->role !== 'vendor_admin') {
            if ($user->role !== 'fkdm_member') {
                return back()->with('error', 'Pendaftaran Akun Pengurus FKDM Kabupaten hanya dapat disetujui oleh Fasilitator Swasta (Vendor).');
            }
            if (!empty($currentUser->district) && mb_strtolower(trim($user->district)) !== mb_strtolower(trim($currentUser->district))) {
                return back()->with('error', 'Anda hanya berhak meng-approve Anggota FKDM di wilayah ' . $currentUser->district);
            }
        }

        $user->update(['status' => 'active']);

        return back()->with('success', 'Akun ' . $user->name . ' telah berhasil disetujui (Approved) dan kini dapat login ke sistem.');
    }

    public function reject($id)
    {
        $currentUser = Auth::user();

        if (!$currentUser || !($currentUser->isAdmin() || $currentUser->isFkdmKabupaten())) {
            abort(403, 'Akses ditolak.');
        }

        $user = User::findOrFail($id);

        if ($currentUser->role === 'vendor_admin' && $user->role !== 'fkdm_kabupaten') {
            return back()->with('error', 'Fasilitator Swasta hanya berhak mengelola pendaftaran Pengurus FKDM Kabupaten / Kesbangpol Pemda.');
        }

        if ($currentUser->role !== 'vendor_admin') {
            if ($user->role !== 'fkdm_member') {
                return back()->with('error', 'Pendaftaran Akun Pengurus FKDM Kabupaten hanya dapat dikelola oleh Fasilitator Swasta (Vendor).');
            }
            if (!empty($currentUser->district) && mb_strtolower(trim($user->district)) !== mb_strtolower(trim($currentUser->district))) {
                return back()->with('error', 'Anda hanya berhak mengelola pendaftaran di wilayah ' . $currentUser->district);
            }
        }

        $user->update(['status' => 'rejected']);

        return back()->with('success', 'Pendaftaran akun ' . $user->name . ' telah ditolak.');
    }
}
