<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\Angkatan;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\DetailPresensi;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.guru.presensi.index');
    }

    public function getPresensiData()
    {
        $guru = auth()->user()->guru;

        $presensi = Presensi::whereHas('kelas', function ($query) use ($guru) {
            $query->where('guru_id', $guru->id);
        })->with('kelas')->get();

        return DataTables::of($presensi)
            ->addColumn('hari', function ($row) {
                return Carbon::parse($row->tanggal)->translatedFormat('l');
            })
            ->addColumn('tanggal', function ($row) {
                return Carbon::parse($row->tanggal)->translatedFormat('d F Y');
            })
            ->addColumn('aksi', function ($row) {
                return '
                <a href="' . route('presensi.detail', ['kelas_id' => $row->kelas_id]) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="' . route('presensi.edit', ['kelas_id' => $row->kelas_id]) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guru = auth()->user()->guru;
        $kelas = Kelas::where('guru_id', $guru->id)->first();
        if (!$kelas) {
            return redirect()->route('presensi.index')->withErrors(['error' => 'Hanya wali kelas yang dapat membuat presensi harian.']);
        }
        return view('pages.guru.presensi.create', compact('kelas'));
    }

    public function createPresensiData()
    {
        $guru = auth()->user()->guru;

        $siswaList = collect();

        if ($guru) {
            $kelas = Kelas::where('guru_id', $guru->id)->first();

            if ($kelas) {
                $angkatanId = session('angkatan_aktif');
                if ($angkatanId) {
                    $siswaList = Siswa::whereHas('kartuStudi', function ($query) use ($kelas, $angkatanId) {
                        $query->where('kelas_id', $kelas->id)
                            ->where('angkatan_id', $angkatanId);
                    })->get();
                }
            }
        }

        return DataTables::of($siswaList)
            ->addColumn('NISN', function ($row) {
                return $row->NISN;
            })
            ->addColumn('nama_siswa', function ($row) {
                return $row->nama_siswa;
            })
            ->addColumn('aksi', function ($row) {
                return '
                    <input type="hidden" name="siswa_id[]" value="' . $row->id . '">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status[' . $row->id . ']" value="hadir" checked>
                        <label class="form-check-label">Hadir</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status[' . $row->id . ']" value="izin">
                        <label class="form-check-label">Izin</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status[' . $row->id . ']" value="sakit">
                        <label class="form-check-label">Sakit</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status[' . $row->id . ']" value="alfa">
                        <label class="form-check-label">Alfa</label>
                    </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'  => 'required|exists:kelas,id',
            'tanggal'   => 'required|date',
            'status'    => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $presensi = Presensi::firstOrCreate(
                [
                    'kelas_id' => $request->kelas_id,
                    'tanggal'  => Carbon::parse($request->tanggal)->toDateString(),
                ]
            );

            foreach ($request->status as $siswa_id => $statusValue) {

                DetailPresensi::updateOrCreate(
                    [
                        'presensi_id' => $presensi->id,
                        'siswa_id'    => $siswa_id,
                    ],
                    [
                        'status'      => $statusValue,
                    ]
                );
            }

            DB::commit();
            return redirect()->route('presensi.index')->with('success', 'Presensi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan presensi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $kelas_id)
    {
        $semesterId = session('semester_aktif');

        $presensi = Presensi::where('kelas_id', $kelas_id)
            ->whereHas('detailPresensi.siswa.kartuStudi', function ($query) use ($semesterId, $kelas_id) {
                $query->where('semester_id', $semesterId)
                    ->where('kelas_id', $kelas_id);
            })->get();

        return view('pages.guru.presensi.detail', compact('presensi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $kelas_id)
    {
        $kelas = Kelas::findOrFail($kelas_id);
        return view('pages.guru.presensi.edit', compact('kelas_id'));
    }

    public function editPresensiData(Request $request)
    {
        $kelas_id = $request->kelas_id;
        $angkatanId = session('angkatan_aktif');
        $semesterId = session('semester_aktif');

        // Ambil presensi hari ini berdasarkan kelas
        $presensi = Presensi::where('kelas_id', $kelas_id)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        if (!$presensi) {
            return DataTables::of([])->make(true); // return kosong kalau presensi belum ada
        }

        // Ambil siswa dari kelas_id dan angkatan aktif
        $siswaList = Siswa::whereHas('kartuStudi', function ($query) use ($kelas_id, $angkatanId, $semesterId) {
            $query->where('kelas_id', $kelas_id)
                ->where('angkatan_id', $angkatanId)
                ->where('semester_id', $semesterId);
        })
            ->with(['detailPresensi' => function ($q) use ($presensi) {
                $q->where('presensi_id', $presensi->id);
            }])
            ->get();

        return DataTables::of($siswaList)
            ->addColumn('NISN', fn($row) => $row->NISN)
            ->addColumn('nama_siswa', fn($row) => $row->nama_siswa)
            ->addColumn('aksi', function ($row) use ($presensi) {
                $status = optional($row->detailPresensi->first())->status ?? 'Hadir';

                $html = '<input type="hidden" name="siswa_id[]" value="' . $row->id . '">';
                foreach (['hadir', 'izin', 'sakit', 'alfa'] as $val) {
                    $checked = $status === $val ? 'checked' : '';
                    $html .= '<div class="form-check form-check-inline">';
                    $html .= '<input class="form-check-input" type="radio" name="status[' . $row->id . ']" value="' . $val . '" ' . $checked . '>';
                    $html .= '<label class="form-check-label">' . ucfirst($val) . '</label>';
                    $html .= '</div>';
                }
                return $html;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $kelas_id)
    {
        $request->validate([
            'siswa_id' => 'required|array',
            'status'   => 'required|array',
        ]);

        $presensi = Presensi::where('kelas_id', $kelas_id)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        if (!$presensi) {
            return redirect()->back()->with('error', 'Data presensi tidak ditemukan.');
        }

        foreach ($request->siswa_id as $siswa_id) {
            $status = $request->status[$siswa_id];

            DetailPresensi::updateOrCreate(
                [
                    'presensi_id' => $presensi->id,
                    'siswa_id'    => $siswa_id,
                ],
                [
                    'status'      => $status,
                ]
            );
        }

        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function indexPresensiSiswa()
    {
        $semesters = Semester::orderBy('nama_semester', 'desc')->get();
        $angkatans = Angkatan::orderBy('tahun_ajaran', 'desc')->get();
        return view('pages.siswa.presensi.index', compact('semesters', 'angkatans'));
    }

    public function getPresensiSiswaData(Request $request)
    {
        $user = auth()->user()->siswa;

        $presensi = DetailPresensi::with('presensi')
            ->where('siswa_id', $user->id)
            ->get()
            ->sortByDesc(function ($item) {
                return $item->presensi->tanggal ?? now();
            })
            ->values();

        return DataTables::of($presensi)
            ->addColumn('hari', function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat('l');
            })
            ->addColumn('tanggal', function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat('d F Y');
            })
            ->addColumn('status', function ($row) {
                return $row->status ?? '-';
            })
            ->make(true);
    }
}
