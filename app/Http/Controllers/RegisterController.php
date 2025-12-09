<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Log};
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Proses registrasi pengguna baru
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|unique:users,username|min:3|max:50|alpha_dash',
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/[a-z]/',      // minimal satu huruf kecil
                    'regex:/[A-Z]/',      // minimal satu huruf besar
                    'regex:/[0-9]/',      // minimal satu angka
                ],
                'no_hp' => 'required|unique:users,no_hp|regex:/^[0-9]{10,15}$/',
            ], [
                'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dash dan underscore.',
                'password.regex' => 'Password harus minimal 1 huruf besar, 1 huruf kecil, dan 1 angka.',
                'no_hp.regex' => 'Nomor HP harus 10-15 digit.',
            ]);

            // Tentukan role berdasarkan akhiran username
            $role = Str::endsWith($request->username, 'ADM') ? 'admin' 
                : (Str::endsWith($request->username, 'OWN') ? 'pemilik' : 'pelanggan');

            // Buat user baru
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'no_hp' => $request->no_hp,
                'role' => $role,
            ]);

            // Login otomatis setelah registrasi
            Auth::login($user);

            return redirect()->route('home')->with('success', 'Akun berhasil dibuat! Selamat datang di DailyDose.');
        } catch (\Exception $e) {
            Log::error('Gagal registrasi: ' . $e->getMessage());
            return back()->withInput()->withErrors([
                'username' => 'Terjadi kesalahan saat registrasi. Coba lagi.'
            ]);
        }
    }
}
