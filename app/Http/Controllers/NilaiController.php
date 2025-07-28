<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KartuStudi;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\Semester;
use Yajra\DataTables\Facades\DataTables;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.guru.nilai.index');
    }

    public function getNilaiData()
    {
        $guru = auth()->user()->guru;
        $angkatanId = session('angkatan_aktif');
        $semesterId = session('semester_aktif');

        $kelasIds = Kelas::where('guru_id', $guru->id)
            ->where('angkatan_id', $angkatanId)
            ->pluck('id');

        $siswaList = Siswa::whereHas('kartuStudi', function ($query) use ($kelasIds, $semesterId) {
            $query->whereIn('kelas_id', $kelasIds)
                ->where('semester_id', $semesterId);
        })
            ->with(['kartuStudi' => function ($q) use ($semesterId) {
                $q->where('semester_id', $semesterId)->with('kelas');
            }])
            ->get();

        return DataTables::of($siswaList)
            ->addColumn('NISN', function ($row) {
                return $row->NISN;
            })
            ->addColumn('nama_siswa', function ($row) {
                return $row->nama_siswa;
            })
            ->addColumn('aksi_nilai', function ($row) {
                return '
                   <a href="' . route('nilai.create', $row->id) . '" class="btn btn-primary btn-action" data-toggle="tooltip" title="Input Nilai">
                    <i class="fa-solid fa-plus"></i>
                </a>';
            })
            ->addColumn('aksi', function ($row) {
                $semesterId = session('semester_aktif');

                $kartuStudi = KartuStudi::where('siswa_id', $row->id)
                    ->where('semester_id', $semesterId)
                    ->first();

                if (!$kartuStudi) {
                    return '-';
                }

                $adaNilai = Nilai::where('ks_id', $kartuStudi->id)->exists();

                $detailBtn = '
                    <a href="' . route('nilai.detail', $kartuStudi->id) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                        <i class="fa-solid fa-eye"></i>
                    </a>';

                $editBtn = $adaNilai
                    ? '<a href="' . route('nilai.edit', $kartuStudi->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>'
                    : '<button class="btn btn-secondary btn-action" title="Belum ada nilai" disabled>
                        <i class="fas fa-pencil-alt"></i>
                    </button>';

                return $detailBtn . ' ' . $editBtn;
            })
            ->rawColumns(['aksi_nilai', 'aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $guru = auth()->user()->guru;
        $siswa = Siswa::findOrFail($id);

        $kelasList = Kelas::where('guru_id', $guru->id)->pluck('id');

        if ($kelasList->isEmpty()) {
            return back()->withErrors(['kelas' => 'Guru tidak mengajar di kelas manapun.']);
        }

        $semesterId = session('semester_aktif');
        $angkatanId = session('angkatan_aktif');

        $kartuStudi = KartuStudi::where('siswa_id', $id)
            ->whereIn('kelas_id', $kelasList)
            ->where('semester_id', $semesterId)
            ->whereHas('semester', function ($query) use ($angkatanId) {
                $query->where('angkatan_id', $angkatanId);
            })
            ->first();

        if (!$kartuStudi) {
            return back()->withErrors(['kartu_studi' => 'Siswa belum terdaftar di kelas Anda pada semester dan tahun ajaran aktif ini.']);
        }

        $mapels = MataPelajaran::whereHas('jadwalPelajaran', function ($query) use ($kelasList, $guru) {
            $query->whereIn('kelas_id', $kelasList)
                ->where('guru_id', $guru->id);
        })->with('bobotPenilaian')->get();

        return view('pages.guru.nilai.create', compact('mapels', 'kartuStudi', 'siswa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ks_id' => 'required|exists:kartu_studis,id',
            'nilai'          => 'required|array',
            'nilai.*.uh'     => 'required|numeric|min:0|max:100',
            'nilai.*.uts'    => 'required|numeric|min:0|max:100',
            'nilai.*.uas'    => 'required|numeric|min:0|max:100',
        ]);

        $kartuStudiId = $request->ks_id;

        foreach ($request->nilai as $mapel_id => $nilaiData) {

            $bobot = MataPelajaran::find($mapel_id)?->bobotPenilaian;

            if (!$bobot) {
                return redirect()->back()
                    ->withErrors(['error' => 'Bobot penilaian untuk mata pelajaran ini belum diatur.']);
            }

            $nilaiAkhir = (
                $nilaiData['uh'] * $bobot->bobot_uh / 100 +
                $nilaiData['uts'] * $bobot->bobot_uts / 100 +
                $nilaiData['uas'] * $bobot->bobot_uas / 100
            );

            Nilai::updateOrCreate(
                [
                    'ks_id'    => $kartuStudiId,
                    'mapel_id' => $mapel_id,
                ],
                [
                    'nilai_uh'    => $nilaiData['uh'],
                    'nilai_uts'   => $nilaiData['uts'],
                    'nilai_uas'   => $nilaiData['uas'],
                    'nilai_akhir' => round($nilaiAkhir, 2),
                ]
            );
        }

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kartuStudi = KartuStudi::with(['siswa', 'kelas'])->findOrFail($id);

        $nilaiItems = Nilai::where('ks_id', $kartuStudi->id)
            ->with(['mataPelajaran.bobotPenilaian'])
            ->get();

        return view('pages.guru.nilai.detail', compact('kartuStudi', 'nilaiItems'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $guru = auth()->user()->guru;

        $kartuStudi = KartuStudi::with(['siswa', 'kelas'])->findOrFail($id);

        $mapels = MataPelajaran::whereHas('jadwalPelajaran', function ($query) use ($kartuStudi, $guru) {
            $query->where('kelas_id', $kartuStudi->kelas_id)
                ->where('guru_id', $guru->id);
        })->get();

        $existingNilai = Nilai::where('ks_id', $kartuStudi->id)->pluck('nilai_uh', 'mapel_id')->all();
        $nilaiSiswa = Nilai::where('ks_id', $kartuStudi->id)
            ->get()
            ->keyBy('mapel_id'); // Mengindeks collection berdasarkan mapel_id

        return view('pages.guru.nilai.edit', compact('kartuStudi', 'nilaiSiswa', 'mapels', 'existingNilai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nilai'          => 'required|array',
            'nilai.*.uh'     => 'required|numeric|min:0|max:100',
            'nilai.*.uts'    => 'required|numeric|min:0|max:100',
            'nilai.*.uas'    => 'required|numeric|min:0|max:100',
        ]);

        $kartuStudiId = $id;

        foreach ($request->nilai as $mapel_id => $nilaiData) {
            // Dapatkan bobot penilaian
            $bobot = MataPelajaran::find($mapel_id)?->bobotPenilaian;

            if (!$bobot) {
                return redirect()->back()
                    ->withErrors(['error' => 'Bobot penilaian untuk mata pelajaran ini belum diatur.']);
            }

            // Hitung nilai akhir
            $nilaiAkhir = (
                ($nilaiData['uh'] * $bobot->bobot_uh / 100) +
                ($nilaiData['uts'] * $bobot->bobot_uts / 100) +
                ($nilaiData['uas'] * $bobot->bobot_uas / 100)
            );

            // Gunakan updateOrCreate untuk efisiensi
            Nilai::updateOrCreate(
                [
                    'ks_id'    => $kartuStudiId,
                    'mapel_id' => $mapel_id,
                ],
                [
                    'nilai_uh'    => $nilaiData['uh'],
                    'nilai_uts'   => $nilaiData['uts'],
                    'nilai_uas'   => $nilaiData['uas'],
                    'nilai_akhir' => round($nilaiAkhir, 2),
                ]
            );
        }

        return redirect()->route('nilai.index')->with('success', 'Nilai siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function indexNilaiAdmin()
    {
        $semesters = Semester::with('angkatan')->orderByDesc('nama_semester')->get();
        return view('pages.admin.nilai.index', compact('semesters'));
    }

    public function getNilaiAdminData(Request $request)
    {
        $query = Nilai::with([
            'kartuStudi.siswa',
            'kartuStudi.semester.angkatan',
            'mataPelajaran'
        ]);

        if ($request->filled('semester_id')) {
            $query->whereHas('kartuStudi', function ($q) use ($request) {
                $q->where('semester_id', $request->semester_id);
            });
        }

        $nilai = $query->get();

        return DataTables::of($nilai)
            ->addColumn('angkatan', function ($row) {
                return $row->kartuStudi->semester->angkatan->tahun_ajaran ?? '-';
            })
            ->addColumn('semester', function ($row) {
                return $row->kartuStudi->semester->nama_semester ?? '-';
            })
            ->addColumn('kelas', function ($row) {
                return $row->kartuStudi->kelas->nama_kelas ?? '-';
            })
            ->addColumn('nama_siswa', function ($row) {
                return $row->kartuStudi->siswa->nama_siswa ?? '-';
            })
            ->addColumn('NISN', function ($row) {
                return $row->kartuStudi->siswa->NISN ?? '-';
            })
            ->addColumn('mapel', function ($row) {
                return $row->mataPelajaran->nama_mapel ?? '-';
            })
            ->addColumn('nilai_uh', function ($row) {
                return $row->nilai_uh ?? '-';
            })
            ->addColumn('nilai_uts', function ($row) {
                return $row->nilai_uts ?? '-';
            })
            ->addColumn('nilai_uas', function ($row) {
                return $row->nilai_uas ?? '-';
            })
            ->addColumn('nilai_akhir', function ($row) {
                return $row->nilai_akhir ?? '-';
            })
            ->make(true);
    }

    public function indexNilaiSiswa()
    {
        $semesters = Semester::with('angkatan')->orderByDesc('nama_semester')->get();
        return view('pages.siswa.nilai.index', compact('semesters'));
    }

    public function getNilaiSiswaData(Request $request)
    {
        $user = auth()->user()->siswa;

        $query = Nilai::whereHas('kartuStudi', function ($q) use ($user) {
            $q->where('siswa_id', $user->id);
        })->with(['mataPelajaran', 'kartuStudi.semester.angkatan']);

        if ($request->filled('semester_id')) {
            $query->whereHas('kartuStudi', function ($q) use ($request) {
                $q->where('semester_id', $request->semester_id);
            });
        }

        $nilai = $query->get();

        return DataTables::of($nilai)
            ->addColumn('angkatan', function ($row) {
                return $row->kartuStudi->semester->angkatan->tahun_ajaran ?? '-';
            })
            ->addColumn('semester', function ($row) {
                return $row->kartuStudi->semester->nama_semester ?? '-';
            })
            ->addColumn('mapel', function ($row) {
                return $row->mataPelajaran->nama_mapel ?? '-';
            })
            ->addColumn('nilai_uh', function ($row) {
                return $row->nilai_uh ?? '-';
            })
            ->addColumn('nilai_uts', function ($row) {
                return $row->nilai_uts ?? '-';
            })
            ->addColumn('nilai_uas', function ($row) {
                return $row->nilai_uas ?? '-';
            })
            ->addColumn('nilai_akhir', function ($row) {
                return $row->nilai_akhir ?? '-';
            })
            ->make(true);
    }
}
