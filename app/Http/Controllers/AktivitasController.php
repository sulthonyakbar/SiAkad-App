<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AktivitasHarian;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Kelas;

class AktivitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.guru.aktivitas_harian.index');
    }

    public function getAktivitasData()
    {
        $guru = auth()->user()->guru;

        $aktivitas = AktivitasHarian::with(['siswa.angkatan.kelas'])
            ->whereHas('siswa.angkatan.kelas', function ($query) use ($guru) {
                $query->where('guru_id', $guru->id);
            });

        return DataTables::of($aktivitas)
            ->addColumn('nama_siswa', function ($row) {
                return $row->siswa->nama_siswa ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                return '
                <a href="' . route('aktivitas.detail', $row->id) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="' . route('aktivitas.edit', $row->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.guru.aktivitas_harian.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'kegiatan' => 'required|string|max:255',
            'kendala' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $aktivitas = AktivitasHarian::create([
            'siswa_id' => $request->siswa_id,
            'kegiatan' => $request->kegiatan,
            'kendala' => $request->kendala,
            'deskripsi' => $request->deskripsi,
        ]);

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $userImagesPath = public_path('images/aktivitas_siswa/' .   $aktivitas->siswa->nama_siswa);

            if (!file_exists($userImagesPath)) {
                mkdir($userImagesPath, 0777, true);
            }

            $image->move($userImagesPath, $imageName);
            $aktivitas->foto = 'images/aktivitas_siswa/' . $aktivitas->siswa->nama_siswa . '/' . $imageName;
        } else {
            $aktivitas->foto = null;
        }

        $aktivitas->save();

        return redirect()->route('aktivitas.index')->with('success', 'Data Aktivitas Harian berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $aktivitas = AktivitasHarian::findOrFail($id);
        return view('pages.guru.aktivitas_harian.detail', compact('aktivitas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $aktivitas = AktivitasHarian::findOrFail($id);

        return view('pages.guru.aktivitas_harian.edit', compact('aktivitas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $aktivitas = AktivitasHarian::findOrFail($id);

        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'kegiatan' => 'required|string|max:255',
            'kendala' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $aktivitas->siswa_id = $request->siswa_id;
        $aktivitas->kegiatan = $request->kegiatan;
        $aktivitas->kendala = $request->kendala;
        $aktivitas->deskripsi = $request->deskripsi;

        // Upload dan ganti foto jika ada file baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($aktivitas->foto && file_exists(public_path($aktivitas->foto))) {
                unlink(public_path($aktivitas->foto));
            }

            $image = $request->file('foto');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $userImagesPath = public_path('images/aktivitas_siswa/' . $aktivitas->siswa->nama_siswa);

            if (!file_exists($userImagesPath)) {
                mkdir($userImagesPath, 0777, true);
            }

            $image->move($userImagesPath, $imageName);
            $aktivitas->foto = 'images/aktivitas_siswa/' . $aktivitas->siswa->nama_siswa . '/' . $imageName;
        }

        $aktivitas->save();

        return redirect()->route('aktivitas.index')->with('success', 'Data Aktivitas Harian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $aktivitas = AktivitasHarian::findOrFail($id);

        if ($aktivitas->foto && file_exists(public_path($aktivitas->foto))) {
            unlink(public_path($aktivitas->foto));
        }

        $aktivitas->delete();

        return redirect()->route('aktivitas.index')->with('success', 'Data Aktivitas Harian berhasil dihapus.');
    }

    public function searchSiswa(Request $request): JsonResponse
    {
        $query = $request->input('q');

        $guru = auth()->user()->guru;
        $siswaList = collect();

        if ($guru) {
            $angkatanId = session('angkatan_aktif');

            $kelas = Kelas::where('guru_id', $guru->id)
                ->where('angkatan_id', $angkatanId)
                ->first();

            if ($kelas) {
                $siswaList = Siswa::whereHas('kartuStudi', function ($q) use ($kelas) {
                    $q->where('kelas_id', $kelas->id);
                })
                    ->where(function ($q) use ($query) {
                        $q->where('nama_siswa', 'like', '%' . $query . '%')
                            ->orWhere('NISN', 'like', '%' . $query . '%');
                    })
                    ->get();
            }
        }

        return response()->json($siswaList);
    }
}
