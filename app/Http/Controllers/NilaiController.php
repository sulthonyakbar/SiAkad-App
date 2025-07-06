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

        if ($guru) {
            $kelas = Kelas::where('guru_id', $guru->id)->first();

            if ($kelas) {
                $angkatanId = session('angkatan_aktif');

                $siswaList = Siswa::whereHas('kartuStudi', function ($query) use ($kelas, $angkatanId) {
                    $query->whereHas('kelas', function ($subQuery) use ($kelas, $angkatanId) {
                        $subQuery->where('id', $kelas->id)
                            ->where('angkatan_id', $angkatanId);
                    });
                })->get();
            }
        }

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

                return '
                <a href="' . route('nilai.detail', $kartuStudi->id) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="' . route('nilai.edit', $kartuStudi->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
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

        // Cek kelas yang diajar guru
        $kelas = Kelas::where('guru_id', $guru->id)->first();

        if (!$kelas) {
            return back()->withErrors(['kelas' => 'Guru tidak mengajar di kelas manapun.']);
        }

        $semesterId = session('semester_aktif');

        // Ambil kartu studi siswa untuk semester dan kelas ini
        $kartuStudi = KartuStudi::where('siswa_id', $id)
            ->where('kelas_id', $kelas->id)
            ->where('semester_id', $semesterId)
            ->first();

        if (!$kartuStudi) {
            return back()->with('error', 'Siswa belum terdaftar di kelas Anda pada semester aktif ini.');
        }

        // Ambil semua mapel yg diajar guru di kelas ini (misal berdasarkan jadwal atau relasi khusus)
        $mapels = MataPelajaran::whereHas('jadwalPelajaran', function ($query) use ($kelas, $guru) {
            $query->where('kelas_id', $kelas->id)
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
            'kartu_studi_id' => 'required|exists:kartu_studis,id',
            'nilai'          => 'required|array',
            'nilai.*.uh'     => 'required|numeric|min:0|max:100',
            'nilai.*.uts'    => 'required|numeric|min:0|max:100',
            'nilai.*.uas'    => 'required|numeric|min:0|max:100',
        ]);

        $kartuStudiId = $request->kartu_studi_id;

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
                    // Kunci untuk mencari: apakah nilai untuk mapel dan kartu studi ini sudah ada?
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
            // Tidak perlu validasi kartu_studi_id karena kita sudah dapat dari URL
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
        return view('pages.siswa.nilai.index');
    }

    public function getNilaiSiswaData(Request $request)
    {
        $user = auth()->user()->siswa;

        $nilai = Nilai::whereHas('kartuStudi', function ($query) use ($user) {
            $query->where('siswa_id', $user->id);
        })
            ->with(['mataPelajaran', 'kartuStudi.semester'])
            ->get();

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
