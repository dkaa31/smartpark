<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TbLogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $credentials = [
            'username'    => $request->username,
            'password'    => $request->password,
            'status_aktif'=> 'aktif',
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            TbLogAktivitas::create([
                'id_user'        => $user->id,
                'aktivitas'      => 'Login ke sistem',
                'waktu_aktivitas'=> now(),
            ]);

            return match ($user->role) {
                'admin'   => redirect()->route('admin.dashboard'),
                'petugas' => redirect()->route('petugas.dashboard'),
                'owner'   => redirect()->route('owner.dashboard'),
                default   => redirect('/'),
            };
        }

        return back()->withErrors(['username' => 'Username atau kata sandi salah, atau akun tidak aktif.'])->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            TbLogAktivitas::create([
                'id_user'        => $user->id,
                'aktivitas'      => 'Logout dari sistem',
                'waktu_aktivitas'=> now(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
