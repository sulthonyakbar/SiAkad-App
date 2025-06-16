<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KartuStudi;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\BobotPenilaian;
use App\Models\MataPelajaran;
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
                return '
                <a href="' . route('nilai.detail', $row->id) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="' . route('nilai.edit', $row->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
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

        $semesterId = session('semester_aktif');

        $siswa = Siswa::findOrFail($id);

        // Cek kelas yang diajar guru
        $kelas = Kelas::where('guru_id', $guru->id)->first();

        if (!$kelas) {
            return back()->with('error', 'Guru tidak memiliki kelas.');
        }

        // Ambil kartu studi siswa untuk semester dan kelas ini
        $kartuStudi = KartuStudi::where('siswa_id', $id)
            ->where('kelas_id', $kelas->id)
            ->where('semester_id', $semesterId)
            ->first();

        if (!$kartuStudi) {
            return back()->with('error', 'Siswa belum terdaftar pada semester aktif.');
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
            'nilai' => 'required|array',
        ]);

        foreach ($request->nilai as $mapel_id => $nilaiData) {
            $bobot = MataPelajaran::with('bobotPenilaian')->find($mapel_id)?->bobotPenilaian;

            if (!$bobot) {
                return redirect()->back()
                    ->withErrors(['error' => 'Bobot penilaian untuk mata pelajaran ini belum diatur.']);
            }

            $nilaiAkhir = (
                $nilaiData['uh'] * $bobot->bobot_uh / 100 +
                $nilaiData['uts'] * $bobot->bobot_uts / 100 +
                $nilaiData['uas'] * $bobot->bobot_uas / 100
            );

            $nilai = Nilai::create([
                'mapel_id'     => $mapel_id,
                'nilai_uh'     => $nilaiData['uh'],
                'nilai_uts'    => $nilaiData['uts'],
                'nilai_uas'    => $nilaiData['uas'],
                'nilai_akhir'  => $nilaiAkhir,
            ]);

            // Hubungkan nilai ini ke kartu studi
            KartuStudi::where('id', $request->kartu_studi_id)
                ->update(['nilai_id' => $nilai->id]);
        }

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
