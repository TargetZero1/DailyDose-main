<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log};

class LoginController extends Controller
{
    /**
     * Proses login pengguna
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $credentials = $request->only('username', 'password');
            $remember = $request->has('remember');

            if (Auth::attempt($credentials, $remember)) {
                // Regenerasi session untuk keamanan
                $request->session()->regenerate();
                
                $user = Auth::user();
                $welcomeMessage = "Selamat datang kembali, {$user->username}!";
                
                // Redirect berdasarkan role jika diperlukan
                if (in_array($user->role, ['admin', 'pemilik'])) {
                    return redirect()->intended(route('home'))->with('success', $welcomeMessage);
                }
                
                return redirect()->intended(route('home'))->with('success', $welcomeMessage);
            }

            return back()->withErrors([
                'username' => 'Username atau password tidak sesuai.',
            ])->withInput($request->only('username'));
        } catch (\Exception $e) {
            Log::error('Gagal login: ' . $e->getMessage());
            return back()->withErrors([
                'username' => 'Terjadi kesalahan saat login. Coba lagi.',
            ])->withInput($request->only('username'));
        }
    }
    
    /**
     * Logout pengguna dari sistem
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::logout();
        
        // Invalidasi session
        $request->session()->invalidate();
        // Regenerasi token CSRF
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'Berhasil logout!');
    }
}
