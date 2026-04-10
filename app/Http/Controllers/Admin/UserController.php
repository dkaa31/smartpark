<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbUser;
use App\Models\TbLogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = TbUser::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('status_aktif', $request->status);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(TbUser $user)
    {
        return redirect()->route('admin.users.edit', $user);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|unique:tb_user,username|max:50',
            'password'     => 'required|string|min:6',
            'role'         => 'required|in:admin,petugas,owner',
            'status_aktif' => 'required|in:aktif,nonaktif',
        ]);

        TbUser::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'password'     => Hash::make($request->password),
            'role'         => $request->role,
            'status_aktif' => $request->status_aktif,
        ]);

        TbLogAktivitas::create([
            'id_user'        => auth()->id(),
            'aktivitas'      => 'Menambah user baru: ' . $request->username,
            'waktu_aktivitas'=> now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(TbUser $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, TbUser $user)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|unique:tb_user,username,' . $user->id . '|max:50',
            'role'         => 'required|in:admin,petugas,owner',
            'status_aktif' => 'required|in:aktif,nonaktif',
        ]);

        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'role'         => $request->role,
            'status_aktif' => $request->status_aktif,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        TbLogAktivitas::create([
            'id_user'        => auth()->id(),
            'aktivitas'      => 'Mengubah data user: ' . $user->username,
            'waktu_aktivitas'=> now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(TbUser $user)
    {
        $username = $user->username;
        $user->delete();

        TbLogAktivitas::create([
            'id_user'        => auth()->id(),
            'aktivitas'      => 'Menghapus user: ' . $username,
            'waktu_aktivitas'=> now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
