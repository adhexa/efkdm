<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login', ['hasSidebar' => false]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->status === 'pending') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda sedang dalam proses peninjauan & persetujuan (approval) oleh Admin FKDM Kabupaten / Kesbangpol.',
                ])->onlyInput('email');
            }

            if ($user->status === 'rejected') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Pendaftaran akun Anda ditolak oleh Admin.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->name);
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register', ['hasSidebar' => false]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'district' => ['required', 'string', 'max:100'],
            'subdistrict' => ['required', 'string', 'max:100'],
            'village' => ['required', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'district' => $request->district,
            'subdistrict' => $request->subdistrict,
            'village' => $request->village,
            'institution_name' => 'FKDM ' . $request->district,
            'role' => 'fkdm_member',
            'status' => 'pending',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')
            ->with('registration_success', 'Pendaftaran sebagai Anggota FKDM berhasil! Akun Anda sedang menunggu persetujuan (approval) dari Admin FKDM Kabupaten ' . $request->district . '.');
    }

    public function showRegisterKabupaten()
    {
        return view('auth.register_kabupaten', ['hasSidebar' => false]);
    }

    public function registerKabupaten(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'district' => ['required', 'string', 'max:100'],
            'institution_name' => ['required', 'string', 'max:255'],
            'position_title' => ['required', 'string', 'max:255'],
            'sk_number' => ['required', 'string', 'max:100'],
            'sk_document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $skPath = null;
        if ($request->hasFile('sk_document')) {
            $skPath = $request->file('sk_document')->store('sk_documents', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'institution_name' => $request->institution_name,
            'position_title' => $request->position_title,
            'district' => $request->district,
            'subdistrict' => 'Wilayah Kabupaten',
            'village' => 'Pusat Kabupaten',
            'sk_number' => $request->sk_number,
            'sk_document_path' => $skPath,
            'role' => 'fkdm_kabupaten',
            'status' => 'pending',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')
            ->with('registration_success', 'Pendaftaran Akun Pengurus FKDM Kabupaten / Kesbangpol berhasil diajukan! Akun Anda sedang menunggu persetujuan (approval).');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah keluar dari akun.');
    }
}
