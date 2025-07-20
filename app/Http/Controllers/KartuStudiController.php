<?php

namespace App\Http\Controllers;

use App\Models\KartuStudi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\JsonResponse;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Semester;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class KartuStudiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $semesters = Semester::with('angkatan')->orderByDesc('nama_semester')->get();
        return view('pages.admin.kartu_studi.index', compact('semesters'));
    }

    public function getKSData(Request $request)
    {
        $semesterId = $request->semester_id;

        $ks = KartuStudi::with(['kelas', 'semester.angkatan'])
            ->when($semesterId, function ($query) use ($semesterId) {
                $query->where('semester_id', $semesterId);
            })
            ->get()
            ->filter(function ($item) {
                return $item->kelas !== null && $item->semester !== null;
            })
            ->groupBy('kelas_id')
            ->map(function ($group) {
                $firstItem = $group->first();
                $kelas = $firstItem->kelas;
                $semester = $firstItem->semester;

                return (object) [
                    'id' => $kelas->id,
                    'tahun_ajaran' => $semester->angkatan->tahun_ajaran ?? '-',
                    'semester' => $semester->nama_semester ?? '-',
                    'nama_kelas' => $kelas->nama_kelas ?? '-',
                    'jumlah_siswa' => $group->count()
                ];
            })
            ->values();

        return DataTables::of($ks)
            ->addColumn('tahun_ajaran', function ($row) {
                return $row->tahun_ajaran ?? '-';
            })
            ->addColumn('semester', function ($row) {
                return $row->semester ?? '-';
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
                <a href="' . route('kartu.studi.edit', $row->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
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

        return view('pages.admin.kartu_studi.create', compact('kelas', 'angkatan', 'siswa'));
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

        $sudahPenempatan = KartuStudi::where('kelas_id', $request->kelas_id)
            ->where('semester_id', $semesterId)
            ->exists();

        if ($sudahPenempatan) {
            return back()->withErrors(['kelas_id' => 'Kelas ini sudah melakukan penempatan siswa pada semester ini.'])->withInput();
        }

        foreach ($request->siswa_id as $siswaId) {
            $sudahTerdaftar = KartuStudi::where('siswa_id', $siswaId)
                ->where('semester_id', $semesterId)
                ->exists();

            if ($sudahTerdaftar) {
                return back()->withErrors(['siswa_id' => 'Siswa sudah ditempatkan di kelas lain pada semester ini.'])->withInput();
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
            'kartuStudi.nilai.mapel'
        ])->findOrFail($id);

        foreach ($siswa->kartuStudi as $ks) {
            $ks->uniqueMapel = $ks->kelas->jadwalPelajaran
                ->pluck('mapel')
                ->unique('id')
                ->values();

            $ks->nilaiByMapel = $ks->nilai->keyBy('mapel_id');
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
        $siswa = Siswa::where('angkatan_id', $angkatanId)->get();

        return view('pages.admin.kartu_studi.edit', compact('kelas', 'siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'siswa_id' => 'nullable|array',
            'siswa_id.*' => 'exists:siswas,id',
        ]);

        $semesterId = session('semester_aktif');

        KartuStudi::where('kelas_id', $id)->where('semester_id', $semesterId)->delete();

        $gagal = [];

        if ($request->filled('siswa_id')) {
            foreach ($request->siswa_id as $siswaId) {
                $sudahTerdaftar = KartuStudi::where('siswa_id', $siswaId)
                    ->where('kelas_id', '!=', $id)
                    ->where('semester_id', $semesterId)
                    ->exists();

                if ($sudahTerdaftar) {
                    $gagal[] = $siswaId;
                    continue;
                }

                KartuStudi::create([
                    'siswa_id' => $siswaId,
                    'kelas_id' => $id,
                    'semester_id' => $semesterId
                ]);
            }
        }

        if (!empty($gagal)) {
            return redirect()->route('kartu.studi.index')
                ->with('warning', 'Sebagian siswa tidak dimasukkan karena sudah ditempatkan di kelas lain pada semester ini.');
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

        $data = Siswa::where('status', 'Aktif')
            ->where(function ($q1) use ($query) {
                $q1->where('nama_siswa', 'LIKE', '%' . $query . '%')
                    ->orWhere('NISN', 'LIKE', '%' . $query . '%');
            })
            ->get();

        return response()->json($data);
    }

    public function searchKelas(Request $request): JsonResponse
    {
        $query = $request->input('q');

        $angkatanId = session('angkatan_aktif');

        $data = Kelas::select("kelas.id", "kelas.nama_kelas", "kelas.ruang")
            ->where('kelas.angkatan_id', $angkatanId)
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('kelas.nama_kelas', 'LIKE', '%' . $query . '%')
                        ->orWhere('kelas.ruang', 'LIKE', '%' . $query . '%');
                });
            })
            ->get();

        return response()->json($data);
    }
}
