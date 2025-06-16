<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\KartuStudi;
use App\Models\Siswa;
use App\Models\Kelas;

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
        $presensi = Presensi::with(['kartuStudi.kelas'])->get();

        return DataTables::of($presensi)
            ->addColumn('hari', function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat('l');
            })
            ->addColumn('tanggal', function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat('d F Y');
            })
            ->addColumn('aksi', function ($row) {
                return '
                <a href="' . route('presensi.detail', ['kelas_id' => $row->kartuStudi->kelas_id]) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="' . route('presensi.edit', ['kelas_id' => $row->kartuStudi->kelas_id]) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.guru.presensi.create');
    }

    public function createPresensiData()
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
            'siswa_id' => 'required|array',
            'status' => 'required|array',
        ]);

        $semesterId = session('semester_aktif');

        foreach ($request->siswa_id as $siswa_id) {
            $status = $request->status[$siswa_id];

            // Cari kartu studi aktif milik siswa
            $kartuStudi = KartuStudi::where('siswa_id', $siswa_id)
                ->where('semester_id', $semesterId)
                ->latest()->first();

            if ($kartuStudi) {
                // Cek apakah sudah ada presensi untuk hari ini
                $alreadyPresensiToday = Presensi::whereDate('created_at', now()->toDateString())
                    ->where('id', $kartuStudi->presensi_id)
                    ->exists();

                if (!$alreadyPresensiToday) {
                    // Buat presensi baru
                    $presensi = Presensi::create([
                        'status' => $status,
                    ]);

                    // Update kartu studi dengan id presensi
                    $kartuStudi->update([
                        'presensi_id' => $presensi->id,
                        'semester_id' => $semesterId,
                    ]);
                } else {
                    // Jika sudah ada presensi hari ini, update statusnya
                    $presensi = Presensi::whereDate('created_at', now()->toDateString())
                        ->where('id', $kartuStudi->presensi_id)
                        ->first();

                    $presensi->update([
                        'status' => $status,
                    ]);
                }
            }
        }

        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $kelas_id)
    {
        $semesterId = session('semester_aktif');

        $kartuStudi = KartuStudi::with(['siswa', 'presensi'])
            ->where('kelas_id', $kelas_id)
            ->where('semester_id', $semesterId)
            ->get();

        return view('pages.guru.presensi.detail', compact('kartuStudi'));
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

        $guru = auth()->user()->guru;

        if ($guru) {
            $kelas = Kelas::where('guru_id', $guru->id)->first();

            if ($kelas) {
                $angkatanId = session('angkatan_aktif');
                $semesterId = session('semester_aktif');

                $siswaList = Siswa::whereHas('kartuStudi', function ($query) use ($kelas, $angkatanId, $semesterId) {
                    $query->whereHas('kelas', function ($subQuery) use ($kelas, $angkatanId) {
                        $subQuery->where('id', $kelas->id)
                            ->where('angkatan_id', $angkatanId);
                    })->where('semester_id', $semesterId);
                })->with(['kartuStudi.presensi' => function ($q) {
                    $q->whereDate('created_at', now()->toDateString());
                }])->get();
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
                $status = optional($row->kartuStudi->presensi)->status ?? 'hadir';
                $html = '<input type="hidden" name="siswa_id[]" value="' . $row->id . '">';
                foreach (['hadir', 'izin', 'sakit', 'alfa'] as $val) {
                    $checked = $status === $val ? 'checked' : '';
                    $html .= '<div class="form-check form-check-inline">';
                    $html .= '<input class="form-check-input" type="radio" name="status[' . $row->id . ']" value="' . $val . '" ' . $checked . '> ';
                    $html .= '<label class="form-check-label">' . ucfirst($val) . '</label></div>';
                }
                return $html;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'siswa_id' => 'required|array',
            'status' => 'required|array',
        ]);

        $semesterId = session('semester_aktif');

        foreach ($request->siswa_id as $siswa_id) {
            $status = $request->status[$siswa_id];

            $kartuStudi = KartuStudi::where('siswa_id', $siswa_id)
                ->where('semester_id', $semesterId)
                ->latest()->first();

            if ($kartuStudi) {
                $presensi = Presensi::where('kartu_studi_id', $kartuStudi->id)
                    ->whereDate('created_at', now()->toDateString())
                    ->first();

                if ($presensi) {
                    $presensi->update(['status' => $status]);
                }
            }
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
}
