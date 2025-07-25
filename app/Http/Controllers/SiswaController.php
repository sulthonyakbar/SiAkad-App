<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.siswa.index');
    }

    public function getSiswaData()
    {
        $siswa = Siswa::with('kartuStudi.kelas', 'orang_tuas')
            ->whereIn('status', ['Aktif', 'Nonaktif'])
            ->select('siswas.*');

        return DataTables::of($siswa)
            ->addColumn('kelas', function ($row) {
                $kartuStudi = $row->kartuStudi->sortByDesc('created_at')->first();
                return $kartuStudi && $kartuStudi->kelas ? $kartuStudi->kelas->nama_kelas : '-';
            })
            ->addColumn('nama_ayah', function ($row) {
                return $row->orang_tuas->nama_ayah ?? '-';
            })
            ->addColumn('nama_ibu', function ($row) {
                return $row->orang_tuas->nama_ibu ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                $buttons = '
                <a href="' . route('siswa.detail', $row->id) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="' . route('siswa.edit', $row->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                ';

                if ($row->status === 'Aktif') {
                    $buttons .= '
                    <form action="' . route('siswa.status', [$row->id, 'Nonaktif']) . '" method="POST" class="d-inline">
                        ' . csrf_field() . method_field('PATCH') . '
                        <button type="submit" class="btn btn-danger btn-action" data-toggle="tooltip" title="Nonaktifkan">
                            <i class="fa-solid fa-ban"></i>
                        </button>
                    </form>
                    <form action="' . route('siswa.status', [$row->id, 'Lulus']) . '" method="POST" class="d-inline">
                        ' . csrf_field() . method_field('PATCH') . '
                        <button type="submit" class="btn btn-primary btn-action" data-toggle="tooltip" title="Luluskan">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </button>
                    </form>';
                } elseif ($row->status === 'Nonaktif') {
                    $buttons .= '
                    <form action="' . route('siswa.status', [$row->id, 'Aktif']) . '" method="POST" class="d-inline">
                        ' . csrf_field() . method_field('PATCH') . '
                        <button type="submit" class="btn btn-success btn-action" data-toggle="tooltip" title="Aktifkan">
                            <i class="fa-solid fa-check"></i>
                        </button>
                    </form>
                    <form action="' . route('siswa.status', [$row->id, 'Lulus']) . '" method="POST" class="d-inline">
                        ' . csrf_field() . method_field('PATCH') . '
                        <button type="submit" class="btn btn-primary btn-action" data-toggle="tooltip" title="Luluskan">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </button>
                    </form>';
                }
                return $buttons;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function indexAlumni()
    {
        return view('pages.admin.siswa.alumni.index');
    }

    public function getAlumniData()
    {
        $alumni = Siswa::with('kartuStudi.kelas', 'orang_tuas')
            ->where('status', 'Lulus')
            ->select('siswas.*');

        return DataTables::of($alumni)
            ->addColumn('kelas', function ($row) {
                $kartuStudi = $row->kartuStudi->sortByDesc('created_at')->first();
                return $kartuStudi && $kartuStudi->kelas ? $kartuStudi->kelas->nama_kelas : '-';
            })
            ->addColumn('nama_ayah', function ($row) {
                return $row->orang_tuas->nama_ayah ?? '-';
            })
            ->addColumn('nama_ibu', function ($row) {
                return $row->orang_tuas->nama_ibu ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                $buttons = '
                <a href="' . route('siswa.detail', $row->id) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="' . route('siswa.edit', $row->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                ';

                $buttons .= '
                <form action="' . route('siswa.status', [$row->id, 'Aktif']) . '" method="POST" class="d-inline">
                    ' . csrf_field() . method_field('PATCH') . '
                    <button type="submit" class="btn btn-success btn-action" data-toggle="tooltip" title="Aktifkan Kembali">
                        <i class="fa-solid fa-check"></i>
                    </button>
                </form>';

                return $buttons;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function indexAkunSiswa()
    {
        return view('pages.admin.akun.siswa.index');
    }

    public function getAkunSiswaData()
    {
        $akunSiswa = Siswa::with('user')->select('siswas.*');

        return DataTables::of($akunSiswa)
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

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);

        try {
            Excel::import(new SiswaImport, $request->file('file'));
            return back()->with('success', 'Data siswa berhasil di‑import.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::all();
        return view('pages.admin.siswa.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Validasi untuk siswa
            'nama_siswa' => 'required|string|regex:/^[A-Za-z\s]+$/u|max:255',
            'nomor_induk' => 'required|string|regex:/^[0-9]+$/|max:20|unique:siswas,nomor_induk',
            'NISN' => 'required|string|max:20|regex:/^[0-9]+$/|unique:siswas,NISN',
            'NIK' => 'required|string|max:20|regex:/^[0-9]+$/|unique:siswas,NIK',
            'tempat_lahir' => 'required|regex:/^[A-Za-z\s]+$/u|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|string|max:255',
            'no_telp_siswa' => 'required|string|max:20|regex:/^[0-9]+$/|unique:siswas,no_telp_siswa',
            'alamat_siswa' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tamatan' => 'required|string|max:255',
            'tanggal_lulus' => 'required|date|before:today',
            'STTB' => 'required|string|max:255|regex:/^[0-9]+$/|unique:siswas,STTB',
            'lama_belajar' => 'required|string|regex:/^[0-9]+$/|max:2',
            'pindahan' => 'nullable|string|max:255',
            'alasan' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',

            // Validasi untuk orang tua
            'nama_ayah' => 'required|string|regex:/^[A-Za-z\s]+$/u|max:255',
            'pekerjaan_ayah' => 'required|string|max:255',
            'pendidikan_ayah' => 'required|string|max:255',
            'penghasilan_ayah' => 'required|string|regex:/^[0-9]+$/|max:255',
            'nama_ibu' => 'required|string|regex:/^[A-Za-z\s]+$/u|max:255',
            'pekerjaan_ibu' => 'required|string|max:255',
            'pendidikan_ibu' => 'required|string|max:255',
            'penghasilan_ibu' => 'required|string|regex:/^[0-9]+$/|max:255',
            'no_telp_ortu' => 'required|string|max:20|regex:/^[0-9]+$/|unique:orang_tuas,no_telp_ortu',
            'alamat_ortu' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $namaParts = explode(' ', strtolower($request->nama_siswa));
            $namaAwal = implode('', array_slice($namaParts, 0, 2));
            $username = $namaAwal . $request->NISN;
            $password = $username;

            $user = User::create([
                'username' => $username,
                'email' => $request->email,
                'password' => Hash::make($password),
                'role' => 'orangtua',
            ]);

            $orangTua = OrangTua::create([
                'nama_ayah' => $request->nama_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'pendidikan_ayah' => $request->pendidikan_ayah,
                'penghasilan_ayah' => $request->penghasilan_ayah,
                'nama_ibu' => $request->nama_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'pendidikan_ibu' => $request->pendidikan_ibu,
                'penghasilan_ibu' => $request->penghasilan_ibu,
                'no_telp_ortu' => $request->no_telp_ortu,
                'alamat_ortu' => $request->alamat_ortu,
            ]);
            // Simpan data siswa
            $siswa = Siswa::create([
                'nama_siswa' => $request->nama_siswa,
                'nomor_induk' => $request->nomor_induk,
                'NISN' => $request->NISN,
                'NIK' => $request->NIK,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_telp_siswa' => $request->no_telp_siswa,
                'alamat_siswa' => $request->alamat_siswa,
                'tamatan' => $request->tamatan,
                'tanggal_lulus' => $request->tanggal_lulus,
                'STTB' => $request->STTB,
                'lama_belajar' => $request->lama_belajar,
                'pindahan' => $request->pindahan,
                'alasan' => $request->alasan,
                'status' => 'Aktif',
                'orangtua_id' => $orangTua->id,
                'user_id' => $user->id,
                'angkatan_id' => session('angkatan_aktif'),
            ]);

            if ($request->hasFile('foto')) {
                $image = $request->file('foto');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $userImagesPath = public_path('images/siswa/' . $siswa->nama_siswa);

                if (!file_exists($userImagesPath)) {
                    mkdir($userImagesPath, 0777, true);
                }

                $image->move($userImagesPath, $imageName);
                $siswa->foto = 'images/siswa/' . $siswa->nama_siswa . '/' . $imageName;
            } else {
                $siswa->foto = null;
            }

            $siswa->save();

            DB::commit();
            return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan siswa.' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::with('orang_tuas')->findOrFail($id);

        return view('pages.admin.siswa.detail', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $siswa = Siswa::with('orang_tuas')->findOrFail($id);
        return view('pages.admin.siswa.edit', compact('siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            // Validasi siswa
            'nama_siswa' => 'required|string|regex:/^[A-Za-z\s]+$/u|max:255',
            'nomor_induk' => 'required|string|max:20|regex:/^[0-9]+$/|unique:siswas,nomor_induk,' . $siswa->id,
            'NISN' => 'required|string|max:20|regex:/^[0-9]+$/|unique:siswas,NISN,' . $siswa->id,
            'NIK' => 'required|string|max:20|regex:/^[0-9]+$/|unique:siswas,NIK,' . $siswa->id,
            'tempat_lahir' => 'required|string|regex:/^[A-Za-z\s]+$/u|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|max:255',
            'no_telp_siswa' => 'required|string|max:20|regex:/^[0-9]+$/|unique:siswas,no_telp_siswa,' . $siswa->id,
            'alamat_siswa' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tamatan' => 'required|string|max:255',
            'tanggal_lulus' => 'required|date',
            'STTB' => 'required|string|max:255|regex:/^[0-9]+$/|unique:siswas,STTB,' . $siswa->id,
            'lama_belajar' => 'required|string|max:255',
            'pindahan' => 'nullable|string|max:255',
            'alasan' => 'nullable|string|max:255',

            // Validasi orang tua
            'nama_ayah' => 'required|string|regex:/^[A-Za-z\s]+$/u|max:255',
            'pekerjaan_ayah' => 'required|string|max:255',
            'pendidikan_ayah' => 'required|string|max:255',
            'penghasilan_ayah' => 'required|string|regex:/^[0-9]+$/|max:255',
            'nama_ibu' => 'required|string|regex:/^[A-Za-z\s]+$/u|max:255',
            'pekerjaan_ibu' => 'required|string|max:255',
            'pendidikan_ibu' => 'required|string|max:255',
            'penghasilan_ibu' => 'required|string|regex:/^[0-9]+$/|max:255',
            'no_telp_ortu' => 'required|string|max:20|regex:/^[0-9]+$/|unique:orang_tuas,no_telp_ortu,' . $siswa->orang_tuas->id,
            'alamat_ortu' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Update data orang tua
            $siswa->orang_tuas->update([
                'nama_ayah' => $request->nama_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'pendidikan_ayah' => $request->pendidikan_ayah,
                'penghasilan_ayah' => $request->penghasilan_ayah,
                'nama_ibu' => $request->nama_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'pendidikan_ibu' => $request->pendidikan_ibu,
                'penghasilan_ibu' => $request->penghasilan_ibu,
                'no_telp_ortu' => $request->no_telp_ortu,
                'alamat_ortu' => $request->alamat_ortu,
            ]);

            // Update data siswa
            $siswa->update([
                'nama_siswa' => $request->nama_siswa,
                'nomor_induk' => $request->nomor_induk,
                'NISN' => $request->NISN,
                'NIK' => $request->NIK,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_telp_siswa' => $request->no_telp_siswa,
                'alamat_siswa' => $request->alamat_siswa,
                'tamatan' => $request->tamatan,
                'tanggal_lulus' => $request->tanggal_lulus,
                'STTB' => $request->STTB,
                'lama_belajar' => $request->lama_belajar,
                'pindahan' => $request->pindahan,
                'alasan' => $request->alasan,
            ]);

            // Update foto jika diunggah
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($siswa->foto && file_exists(public_path($siswa->foto))) {
                    unlink(public_path($siswa->foto));
                }

                // Simpan foto baru
                $image = $request->file('foto');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $userImagesPath = public_path('images/siswa/' . $siswa->nama_siswa);

                if (!file_exists($userImagesPath)) {
                    mkdir($userImagesPath, 0777, true);
                }

                $image->move($userImagesPath, $imageName);
                $siswa->foto = 'images/siswa/' . $siswa->nama_siswa . '/' . $imageName;
                $siswa->save();
            }

            DB::commit();
            return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data siswa. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function status(string $id, $status)
    {
        $siswa = Siswa::findOrFail($id);

        $allowedStatus = ['Aktif', 'Nonaktif', 'Lulus'];
        if (!in_array($status, $allowedStatus)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $siswa->status = $status;
        $siswa->save();

        return redirect()->back()->with('success', 'Status siswa berhasil diubah menjadi ' . $status . '.');
    }

    public function profile()
    {
        $user = Auth::user();
        $siswa = Siswa::with('user', 'orang_tuas', 'kelas')->first();
        return view('pages.siswa.profile', compact('user', 'siswa'));
    }
}
