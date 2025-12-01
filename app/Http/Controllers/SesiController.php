<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SesiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showlogin()
    {
        return view('Auth.login');
        //
    }
    public function showregister()
    {
        return view('Auth.register');
        //
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $role = Auth::user()->role;
            return match ($role) {
                'admin' => redirect('/admin'),
                'penjual' => redirect('/penjual'),
                'pelanggan' => redirect('/pelanggan'),
                default => redirect('/pelanggan'),
            };
        }

        return back()->withErrors(['login' => 'Email atau password salah.']);
    }

    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'alamat' => 'required|string|max:255',
        'telepon' => 'required|string|max:20',
    ]);

    DB::transaction(function () use ($request) {
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'pelanggan', // Default role
        ]);

        $user->profile()->create([
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
        ]);
    });

    return redirect()->route('login')->with('success', 'Registrasi berhasil, silahkan login!');
}
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');

    }
    
    
}
