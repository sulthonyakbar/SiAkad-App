<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use App\Imports\GuruImport;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.pegawai.guru.index');
    }

    public function indexAdmin()
    {
        return view('pages.admin.pegawai.admin.index');
    }

    public function getData($role)
    {
        $guru = Guru::select('gurus.*')->whereHas('user', function ($query) use ($role) {
            $query->where('role', $role);
        })->get();

        return DataTables::of($guru)
            ->addColumn('aksi', function ($row) {
                return '
                <a href="' . route('pegawai.detail', $row->id) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="' . route('pegawai.edit', $row->id) . '" class="btn btn-warning btn-action"><i class="fas fa-pencil-alt"></i></a>
                <form id="status-form-' . $row->id . '" action="' . route('pegawai.status', $row->id) . '" method="POST" class="d-inline">
                    ' . csrf_field() . '
                    ' . method_field('PATCH') . '
                    <button type="submit" class="btn btn-' . ($row->status === 'Aktif' ? 'danger' : 'success') . ' btn-action" data-toggle="tooltip" title="' . ($row->status === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan') . '">
                        <i class="fa-solid ' . ($row->status === 'Aktif' ? 'fa-ban' : 'fa-check') . '"></i>
                    </button>
                </form>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function getGuruData()
    {
        return $this->getData('guru');
    }

    public function getAdminData()
    {
        return $this->getData('admin');
    }

    public function indexAkunGuru()
    {
        return view('pages.admin.akun.guru.index');
    }

    public function indexAkunAdmin()
    {
        return view('pages.admin.akun.admin.index');
    }

    public function getAkunData($role)
    {
        $akun = Guru::with('user')->whereHas('user', function ($query) use ($role) {
            $query->where('role', $role);
        })->select('gurus.*');

        return DataTables::of($akun)
            ->addColumn('email', function ($row) {
                return $row->user->email ?? '-';
            })
            ->addColumn('username', function ($row) {
                return $row->user->username ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                return '
                <a href="' . route('akun.edit', $row->user_id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function getAkunGuruData()
    {
        return $this->getAkunData('guru');
    }

    public function getAkunAdminData()
    {
        return $this->getAkunData('admin');
    }

    public function import(Request $request, $role)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);

        if (!in_array($role, ['guru', 'admin'])) {
            return redirect()->back()->with('error', 'Role tidak valid');
        }

        try {
            Excel::import(new GuruImport($role), $request->file('file'));
            return redirect()->back()->with('success', 'Data berhasil diimport sebagai ' . ucfirst($role));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.pegawai.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'role' => 'required|in:guru,admin',
            'nama_guru' => 'required|string|regex:/^[A-Za-z\s]+$/u|max:255',
            'jabatan' => 'required|string|regex:/^[A-Za-z\s]+$/u|max:255',
            'jenis_kelamin' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|regex:/^[A-Za-z\s]+$/u|max:255',
            'tanggal_lahir' => 'required|date',
            'pendidikan' => 'required|string|max:255',
            'mulai_bekerja' => 'required|date',
            'no_telp' => 'required|string|max:20|regex:/^[0-9]+$/|unique:gurus,no_telp',
            'alamat' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'NIP' => 'required|string|max:255|regex:/^[0-9]+$/|unique:gurus,NIP',
            'pangkat' => 'required|string|max:255',
            'NUPTK' => 'required|string|max:255|regex:/^[0-9]+$/|unique:gurus,NUPTK',
            'sertifikasi' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $namaParts = explode(' ', strtolower($request->nama_guru));
        $namaAwal = implode('', array_slice($namaParts, 0, 2));
        $username = $namaAwal . $request->NIP;
        $password = $username;

        $user = User::create([
            'username' => $username,
            'email' => $validatedData['email'],
            'password' => Hash::make($password),
            'role' => $validatedData['role'],
        ]);

        $guru = new Guru();
        $guru->fill($validatedData);
        $guru->user_id = $user->id;
        $guru->status = 'Aktif';

        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $userImagesPath = public_path('images/guru/' . $guru->nama_guru);

            if (!file_exists($userImagesPath)) {
                mkdir($userImagesPath, 0777, true);
            }

            $image->move($userImagesPath, $imageName);
            $guru->foto = 'images/guru/' . $guru->nama_guru . '/' . $imageName;
        } else {
            $guru->foto = null;
        }

        $guru->save();

        if ($validatedData['role'] == 'guru') {
            return redirect()->route('pegawai.guru.index')->with('success', 'Data Guru berhasil ditambahkan');
        } else {
            return redirect()->route('pegawai.admin.index')->with('success', 'Data Admin berhasil ditambahkan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $guru = Guru::findOrFail($id);

        return view('pages.admin.pegawai.detail', compact('guru'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $guru = Guru::findOrFail($id);
        return view('pages.admin.pegawai.edit', compact('guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id = null)
    {
        $auth = auth()->user();

        if ($auth->role === 'guru') {
            $guru = $auth->guru;
        } else {
            $guru = Guru::findOrFail($id);
        }
        $request->validate([
            'nama_guru' => 'required|string|regex:/^[A-Za-z\s]+$/u|max:255',
            'jabatan' => 'required|string|regex:/^[A-Za-z\s]+$/u|max:255',
            'jenis_kelamin' => 'required|string|max:255',
            'NIP' => 'required|string|max:255|regex:/^[0-9]+$/|unique:gurus,NIP,' . $guru->id,
            'pangkat' => 'required|string|max:255',
            'NUPTK' => 'required|string|max:255|regex:/^[0-9]+$/|unique:gurus,NUPTK,' . $guru->id,
            'tempat_lahir' => 'required|string|regex:/^[A-Za-z\s]+$/u|max:255',
            'tanggal_lahir' => 'required|date',
            'pendidikan' => 'required|string|max:255',
            'mulai_bekerja' => 'required|date',
            'sertifikasi' => 'nullable|string|max:255',
            'no_telp' => 'required|string|max:20|regex:/^[0-9]+$/|unique:gurus,no_telp,' . $guru->id,
            'alamat' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $guru->fill($request->only([
            'nama_guru',
            'jabatan',
            'jenis_kelamin',
            'NIP',
            'pangkat',
            'NUPTK',
            'tempat_lahir',
            'tanggal_lahir',
            'pendidikan',
            'mulai_bekerja',
            'sertifikasi',
            'no_telp',
            'alamat',
        ]));

        // Update foto jika diunggah
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($guru->foto && file_exists(public_path($guru->foto))) {
                unlink(public_path($guru->foto));
            }

            // Simpan foto baru
            $image = $request->file('foto');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $userImagesPath = public_path('images/guru/' . $guru->nama_guru);

            if (!file_exists($userImagesPath)) {
                mkdir($userImagesPath, 0777, true);
            }

            $image->move($userImagesPath, $imageName);
            $guru->foto = 'images/guru/' . $guru->nama_guru . '/' . $imageName;
        }

        $guru->save();

        if ($auth->role === 'admin') {
            return redirect()->route('pegawai.guru.index')->with('success', 'Data pegawai berhasil diperbarui.');
        } else {
            return redirect()->route('guru.dashboard')->with('success', 'Profil Anda berhasil diperbarui.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function status(string $id)
    {
        $guru = Guru::findOrFail($id);
        $guru->status = $guru->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $guru->save();

        return redirect()->route('pegawai.guru.index')->with('success', 'Status pegawai berhasil diubah.');
    }

    public function profile()
    {
        $guru = auth()->user()->guru;
        return view('pages.guru.profile', compact('guru'));
    }
}
