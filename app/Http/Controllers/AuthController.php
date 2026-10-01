<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi inputan dari form login
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // 2. Cek apakah akun default 'digibar-demo' sudah ada di database, jika belum buatkan otomatis
        $user = User::where('username', 'digibar-demo')->first();

        if (!$user) {
            $user = new User();
            $user->name = 'Admin CatchTime';
            $user->username = 'digibar-demo';
            $user->password = Hash::make('demo123');
            $user->save();
        }

        // 3. Verifikasi username dan password yang diinput user
        if ($request->username === $user->username && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect('/admin/dashboard')->with('success', 'Berhasil login!');
        }

        // 4. Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors(['username' => 'Username atau password salah!'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}