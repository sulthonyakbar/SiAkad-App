<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        return view('pages.auth-login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->first();

        if ($user && Auth::guard('web')->attempt(['email' => $user->email, 'password' => $request->password])) {

            if ($user->role === 'admin' || $user->role === 'guru') {
                $nama_pengguna = $user->guru->nama_guru;
            } elseif ($user->role === 'orangtua') {
                $nama_pengguna = $user->siswa->nama_siswa;
            }

            session()->flash('success', 'Login berhasil! Selamat datang, ' . $nama_pengguna);

            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'guru':
                    return redirect()->route('guru.dashboard');
                case 'orangtua':
                    return redirect()->route('siswa.dashboard');
                default:
                    Auth::logout();
                    return back()->with('error', 'Role tidak dikenali');
            }
        }

        return back()->with('error', 'Username atau Password salah');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logout berhasil');
    }

    public function edit(User $user)
    {
        return view('pages.admin.akun.edit', [
            'user' => $user,
            'updateRoute' => route('akun.update', $user),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('siswa.akun.index')->with('success', 'Akun berhasil diperbarui.');
    }
}
