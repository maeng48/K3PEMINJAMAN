<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:4',
            'role'     => 'required|in:admin,pic,tl,manager',
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Fungsi untuk memperbarui data pengguna
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'role'     => 'required|in:admin,pic,tl,manager',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        
        // Password opsional diisi saat edit (jika kosong, password tidak diubah)
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:4']);
            $user->password = Hash::make($request->password);
        }

        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

  public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Lindungi akun Admin System agar tidak bisa dihapus
        if (strtolower($user->username) === 'admin' || $user->id == 1) {
            return back()->with('error', 'Akun Admin System utama tidak dapat dihapus!');
        }

        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}