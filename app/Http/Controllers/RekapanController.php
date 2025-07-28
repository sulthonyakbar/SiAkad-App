<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\KartuStudi;
use App\Models\Rekapan;
use App\Models\Semester;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class RekapanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.guru.rekapan.index');
    }

    public function getRekapanData(Request $request)
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
            ->with([
                'kartuStudi' => function ($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)
                        ->with('kelas');
                }
            ])
            ->get();

        return DataTables::of($siswaList)
            ->addColumn('NISN', function ($row) {
                return $row->NISN;
            })
            ->addColumn('nama_siswa', function ($row) {
                return $row->nama_siswa;
            })
            ->addColumn('aksi', function ($row) {
                $semesterId = session('semester_aktif');

                $kartuStudi = KartuStudi::where('siswa_id', $row->id)
                    ->where('semester_id', $semesterId)
                    ->first();

                if (!$kartuStudi) {
                    return '-';
                }

                return '
                    <a href="' . route('rekapan.detail', $kartuStudi->id) . '"
                    class="btn btn-info btn-action"
                    title="Detail">
                        <i class="fas fa-eye"></i>
                    </a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function indexSiswa()
    {
        $semesters = Semester::with('angkatan')->orderByDesc('nama_semester')->get();
        return view('pages.siswa.rekapan.index', compact('semesters'));
    }

    public function getRekapanDataSiswa(Request $request)
    {
        $user = auth()->user()->siswa;
        $semesterId = $request->semester_id;

        $query = KartuStudi::with(['semester.angkatan'])
            ->where('siswa_id', $user->id)
            ->orderByDesc('semester_id');

        if ($semesterId) {
            $query->where('semester_id', $semesterId);
        }

        $kartuStudis = $query->get();

        return DataTables::of($kartuStudis)
            ->addColumn('tahun_ajaran', function ($row) {
                return $row->semester->angkatan->tahun_ajaran ?? '-';
            })
            ->addColumn('semester', function ($row) {
                return $row->semester->nama_semester ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                return '
                <a href="' . route('siswa.rekapan.detail', $row->id) . '"
                class="btn btn-info btn-action" title="Detail">
                    <i class="fas fa-eye"></i>
                </a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ks_id' => 'required|exists:kartu_studis,id',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        Rekapan::updateOrCreate(
            ['ks_id' => $validated['ks_id']],
            ['keterangan' => $validated['keterangan']]
        );

        return redirect()->back()->with('success', 'Keterangan berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kartuStudi = KartuStudi::with(['siswa', 'nilai.mataPelajaran.bobotPenilaian'])->findOrFail($id);

        $rekapan = Rekapan::where('ks_id', $id)->first();

        $semesterId = $kartuStudi->semester_id;
        $siswaId = $kartuStudi->siswa_id;

        $rekapPresensi = DB::table('detail_presensis')
            ->join('presensis', 'detail_presensis.presensi_id', '=', 'presensis.id')
            ->join('kartu_studis', function ($join) {
                $join->on('kartu_studis.kelas_id', '=', 'presensis.kelas_id')
                    ->on('kartu_studis.siswa_id', '=', 'detail_presensis.siswa_id');
            })
            ->select(
                'detail_presensis.siswa_id',
                DB::raw("SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) as Hadir"),
                DB::raw("SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) as Izin"),
                DB::raw("SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) as Sakit"),
                DB::raw("SUM(CASE WHEN status = 'Alpa' THEN 1 ELSE 0 END) as Alpa")
            )
            ->where('kartu_studis.semester_id', $semesterId)
            ->where('detail_presensis.siswa_id', $siswaId)
            ->groupBy('detail_presensis.siswa_id')
            ->first();

        $nilaiItems = $kartuStudi->nilai;

        return view('pages.guru.rekapan.detail', compact('kartuStudi', 'rekapan', 'nilaiItems', 'rekapPresensi'));
    }

    public function showSiswa(string $id)
    {
        $kartuStudi = KartuStudi::with(['siswa', 'nilai.mataPelajaran.bobotPenilaian'])->findOrFail($id);

        $rekapan = Rekapan::where('ks_id', $id)->first();

        $semesterId = $kartuStudi->semester_id;
        $siswaId = $kartuStudi->siswa_id;

        $rekapPresensi = DB::table('detail_presensis')
            ->join('presensis', 'detail_presensis.presensi_id', '=', 'presensis.id')
            ->join('kartu_studis', function ($join) {
                $join->on('kartu_studis.kelas_id', '=', 'presensis.kelas_id')
                    ->on('kartu_studis.siswa_id', '=', 'detail_presensis.siswa_id');
            })
            ->select(
                'detail_presensis.siswa_id',
                DB::raw("SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) as Hadir"),
                DB::raw("SUM(CASE WHEN status = 'Izin' THEN 1 ELSE 0 END) as Izin"),
                DB::raw("SUM(CASE WHEN status = 'Sakit' THEN 1 ELSE 0 END) as Sakit"),
                DB::raw("SUM(CASE WHEN status = 'Alpa' THEN 1 ELSE 0 END) as Alpa")
            )
            ->where('kartu_studis.semester_id', $semesterId)
            ->where('detail_presensis.siswa_id', $siswaId)
            ->groupBy('detail_presensis.siswa_id')
            ->first();

        $nilaiItems = $kartuStudi->nilai;

        return view('pages.siswa.rekapan.detail', compact('kartuStudi', 'rekapan', 'nilaiItems', 'rekapPresensi'));
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
