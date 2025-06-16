<?php

namespace App\Http\Controllers;

use App\Models\KartuStudi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\JsonResponse;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Angkatan;

class KartuStudiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.kartu_studi.index');
    }

    public function getKSData()
    {
        // $ks = KartuStudi::with('kelas')->select('kartu_studis.*')->withCount('siswa')->groupBy('kelas_id')->get();

        $ks = KartuStudi::with('kelas')
            ->get()
            ->filter(function ($item) {
                return $item->kelas !== null;
            })
            ->groupBy('kelas_id')
            ->map(function ($group) {
                $kelas = $group->first()->kelas;
                return (object) [
                    'id' => $kelas->id,
                    'tahun_ajaran' => $kelas->angkatan->tahun_ajaran ?? '-',
                    'nama_kelas' => $kelas->nama_kelas ?? '-',
                    'jumlah_siswa' => $group->count()
                ];
            })
            ->values();

        // $ks = KartuStudi::with('kelas')
        // ->select('kelas_id')
        // ->withCount('siswa')
        // ->groupBy('kelas_id')
        // ->get();

        return DataTables::of($ks)
            ->addColumn('tahun_ajaran', function ($row) {
                return $row->tahun_ajaran ?? '-';
            })
            ->addColumn('kelas', function ($row) {
                return $row->nama_kelas ?? '-';
            })
            ->addColumn('jumlah_siswa', function ($row) {
                return $row->jumlah_siswa ?? 0;
            })
            ->addColumn('aksi', function ($row) {
                return '
                <a href="' . route('kartu.studi.detail', $row->id) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="' . route('kartu.studi.edit', $row->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                <form id="delete-form-' . $row->id . '" action="' . route('kartu.studi.destroy', $row->id) . '" method="POST" class="d-inline">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-danger btn-action" data-toggle="tooltip" title="Hapus" onclick="confirmDelete(event, \'delete-form-' . $row->id . '\')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $angkatan = session('angkatan_aktif');
        $kelas = Kelas::where('angkatan_id', $angkatan)->get();
        $siswa = Siswa::where('angkatan_id', $angkatan)->get();

        return view('pages.admin.kartu_studi.create', compact('kelas', 'angkatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'siswa_id' => 'required|array',
            'siswa_id.*' => 'exists:siswas,id',
        ]);

        $semesterId = session('semester_aktif');

        foreach ($request->siswa_id as $siswaId) {
            $exists = KartuStudi::where('kelas_id', $request->kelas_id)
                ->where('siswa_id', $siswaId)
                ->exists();

            if ($exists) {
                return back()->withErrors(['siswaId' => 'Siswa sudah ditempatkan di kelas ini.'])->withInput();
            }

            KartuStudi::create([
                'siswa_id' => $siswaId,
                'kelas_id' => $request->kelas_id,
                'semester_id' => $semesterId
            ]);
        }

        return redirect()->route('kartu.studi.index')->with('success', 'Penempatan kelas berhasil disimpan.');
    }

    public function show(string $id)
    {
        $kartuStudi = KartuStudi::with(['siswa', 'kelas'])
            ->where('kelas_id', $id)
            ->get();

        if (!$kartuStudi) {
            return redirect()->route('kartu.studi.index')->with('error', 'Data Kartu Studi tidak ditemukan.');
        }

        $kelas = $kartuStudi->first()->kelas;

        return view('pages.admin.kartu_studi.detail', compact('kartuStudi', 'kelas'));
    }

    public function showSiswa(string $id)
    {
        $kartuStudi = KartuStudi::with('siswa')->where('kelas_id', $id)->get();

        return DataTables::of($kartuStudi)
            ->addColumn('NISN', function ($row) {
                return $row->siswa->NISN ?? '-';
            })
            ->addColumn('nama_siswa', function ($row) {
                return $row->siswa->nama_siswa ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                return '
                <a href="' . route('kartu.studi.siswa', $row->siswa->id) . '" class="btn btn-info btn-action" title="Lihat Kartu Studi Siswa">
                    <i class="fa-solid fa-address-card"></i>
                </a>
            ';
            })
            ->rawColumns(['aksi'])
            ->make(true);;
    }

    public function showKSSiswa(string $id)
    {
        $siswa = Siswa::with([
            'kartuStudi.kelas.angkatan',
            'kartuStudi.kelas.jadwalPelajaran.mapel',
            'kartuStudi.nilai'
        ])->findOrFail($id);

        foreach ($siswa->kartuStudi as $ks) {
            $ks->uniqueMapel = $ks->kelas->jadwalPelajaran
                ->pluck('mapel')
                ->unique('id');
        }

        $kartuStudi = $siswa->kartuStudi;

        return view('pages.admin.kartu_studi.ks_siswa', compact('siswa', 'kartuStudi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kelas = Kelas::with('kartuStudi.siswa')->findOrFail($id);

        $angkatanId = session('angkatan_aktif');
        $siswaTersedia = Siswa::where('angkatan_id', $angkatanId)->get();

        return view('pages.admin.kartu_studi.edit', compact('kelas', 'siswaTersedia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'siswa_id' => 'required|array',
            'siswa_id.*' => 'exists:siswas,id',
        ]);

        KartuStudi::where('kelas_id', $id)->delete();

        foreach ($request->siswa_id as $siswaId) {
            KartuStudi::create([
                'siswa_id' => $siswaId,
                'kelas_id' => $id,
                'nilai_id' => null,
                'presensi_id' => null,
                'semester_id' => null
            ]);
        }

        return redirect()->route('kartu.studi.index')->with('success', 'Penentuan Kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function searchSiswa(Request $request): JsonResponse
    {
        $query = $request->input('q');

        $data = Siswa::where('nama_siswa', 'LIKE', '%' . $query . '%')
            ->orWhere('NISN', 'LIKE', '%' . $query . '%')
            ->get();

        return response()->json($data);
    }

    public function searchKelas(Request $request): JsonResponse
    {
        $query = $request->input('q');

        $data = Kelas::where('nama_kelas', 'LIKE', '%' . $query . '%')
            ->orWhere('ruang', 'LIKE', '%' . $query . '%')
            ->get();

        return response()->json($data);
    }
}
