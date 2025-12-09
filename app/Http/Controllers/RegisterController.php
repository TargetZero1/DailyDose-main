<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
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
        $request->validate([
            'username' => 'required|unique:users,username|min:3|max:50|alpha_dash',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',      // harus mengandung huruf kecil
                'regex:/[A-Z]/',      // harus mengandung huruf besar
                'regex:/[0-9]/',      // harus mengandung angka
            ],
            'no_hp' => 'required|unique:users,no_hp|regex:/^[0-9]{10,15}$/',
        ], [
            'username.alpha_dash' => 'Username can only contain letters, numbers, dashes and underscores.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'no_hp.regex' => 'Phone number must be 10-15 digits.',
        ]);

        // Tentukan role berdasarkan akhiran username
        $role = Str::endsWith($request->username, 'ADM') ? 'admin' 
            : (Str::endsWith($request->username, 'OWN') ? 'pemilik' : 'pelanggan');

        // Simpan user baru ke database
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'role' => $role,
        ]);

        // Login otomatis setelah registrasi
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Account created successfully! Welcome to DailyDose.');
    }
}
