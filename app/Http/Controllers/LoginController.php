<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            $welcomeMessage = "Welcome back, {$user->username}!";
            
            // Redirect berdasarkan role jika diperlukan di masa depan
            if (in_array($user->role, ['admin', 'pemilik'])) {
                return redirect()->intended(route('home'))->with('success', $welcomeMessage);
            }
            
            return redirect()->intended(route('home'))->with('success', $welcomeMessage);
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('username'));
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
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'Logged out successfully. See you soon!');
    }
}
