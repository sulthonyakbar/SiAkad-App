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
                <a href="' . route('presensi.detail', ['kelas_id' => $row->kelas_id, 'tanggal' => $row->tanggal]) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="' . route('presensi.edit', $row->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
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
            ->addColumn('status', function ($row) {
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
            ->rawColumns(['status'])
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
    public function show(string $kelas_id, string $tanggal)
    {
        $presensi = Presensi::where('kelas_id', $kelas_id)
            ->whereDate('tanggal', $tanggal)
            ->with(['kelas', 'detailPresensi.siswa'])
            ->firstOrFail();

        return view('pages.guru.presensi.detail', compact('presensi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presensi $presensi)
    {
        // $guru = auth()->user()->guru;

        // if (!$guru || $presensi->kelas->guru->guru_id != $guru->id) {
        //     abort(403, 'Anda tidak memiliki hak untuk mengedit presensi ini.');
        // }

        return view('pages.guru.presensi.edit', compact('presensi'));
    }

    public function editPresensiData(Request $request)
    {
        $request->validate(['presensi_id' => 'required|exists:presensis,id']);

        $presensi = Presensi::with('kelas')->findOrFail($request->presensi_id);

        $semesterId = session('semester_aktif');

        $siswaList = Siswa::whereHas('kartuStudi', function ($q) use ($presensi, $semesterId) {
            $q->where('kelas_id', $presensi->kelas_id)
                ->where('semester_id', $semesterId);
        })
            ->with(['detailPresensi' => function ($q) use ($presensi) {
                $q->where('presensi_id', $presensi->id);
            }])
            ->get();

        return DataTables::of($siswaList)
            ->addColumn('NISN', fn($row) => $row->NISN)
            ->addColumn('nama_siswa', fn($row) => $row->nama_siswa)
            ->addColumn('status', function ($row) use ($presensi) {
                $status = optional($row->detailPresensi->first())->status ?? 'Hadir';
                $html = '';

                // Loop untuk membuat radio button dan tandai yang sesuai
                foreach (['Hadir', 'Sakit', 'Izin', 'Alpha'] as $val) {
                    $checked = strtolower($status) === strtolower($val) ? 'checked' : '';
                    $html .= '
                        <label class="selectgroup-item">
                            <input type="radio" name="status[' . $row->id . ']" value="' . $val . '" class="selectgroup-input" ' . $checked . '>
                            <span class="selectgroup-button">' . $val . '</span>
                        </label>';
                }
                return '<div class="selectgroup w-100">' . $html . '</div>';
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Presensi $presensi)
    {
        $request->validate([
            'status'   => 'required|array',
        ]);

        DB::beginTransaction();
        try {
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
            return redirect()->route('presensi.index')->with('success', 'Presensi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui presensi.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function indexPresensiAdmin()
    {
        return view('pages.admin.presensi.index');
    }

    public function getPresensiAdminData(Request $request)
    {
        $detailPresensi = DetailPresensi::with([
            'siswa',
            'presensi.kelas'
        ])->get();

        $presensi = $detailPresensi->sortByDesc(function ($item) {
            return $item->presensi->tanggal ?? now();
        })->values();


        return DataTables::of($presensi)
            ->addColumn('NISN', function ($row) {
                return $row->siswa->NISN ?? '-';
            })
            ->addColumn('nama_siswa', function ($row) {
                return $row->siswa->nama_siswa ?? '-';
            })
            ->addColumn('kelas', function ($row) {
                return $row->presensi->kelas->nama_kelas ?? '-';
            })
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

    public function indexPresensiSiswa()
    {
        return view('pages.siswa.presensi.index');
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
