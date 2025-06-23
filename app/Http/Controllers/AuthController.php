<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

        $isEmail = filter_var($request->login, FILTER_VALIDATE_EMAIL);

        $user = $isEmail
            ? User::where('email', $request->login)->first()
            : User::where('username', $request->login)->first();

        if (!$user) {
            $errorMessage = $isEmail ? 'Email tidak ditemukan.' : 'Username tidak ditemukan.';
            return back()->with('error', $errorMessage);
        }

        if (!Auth::guard('web')->attempt(['email' => $user->email, 'password' => $request->password])) {
            return back()->with('error', 'Password yang Anda masukkan salah.');
        }

        $nama_pengguna = match ($user->role) {
            'admin', 'guru' => $user->guru->nama_guru ?? 'Pengguna',
            'orangtua' => $user->siswa->nama_siswa ?? 'Pengguna',
            default => 'Pengguna',
        };

        session()->flash('success', 'Login berhasil! Selamat datang, ' . $nama_pengguna);

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru' => redirect()->route('guru.dashboard'),
            'orangtua' => redirect()->route('siswa.dashboard'),
        };
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
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($user->role === 'guru') {
            return redirect()->route('guru.akun.index')->with('success', 'Akun berhasil diperbarui.');
        } elseif ($user->role === 'orangtua') {
            return redirect()->route('siswa.akun.index')->with('success', 'Akun berhasil diperbarui.');
        } else {
            return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil diperbarui.');
        }
    }

    public function editAkun()
    {
        $user = auth()->user();
        if ($user->role === 'guru') {
            return view('pages.guru.akun', compact('user'));
        } elseif ($user->role === 'orangtua') {
            return view('pages.siswa.akun', compact('user'));
        }
    }

    public function updateAkun(Request $request)
    {
        $authUser = auth()->user();
        $user = User::find($authUser->id);

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($authUser->role === 'guru') {
            return redirect()->route('guru.dashboard')->with('success', 'Akun berhasil diperbarui.');
        } else if ($authUser->role === 'orangtua') {
            return redirect()->route('siswa.dashboard')->with('success', 'Akun berhasil diperbarui.');
        }
    }
}
