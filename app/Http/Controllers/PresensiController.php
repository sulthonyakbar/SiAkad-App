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
        $presensi = Presensi::select('id', 'created_at')->get();

        return DataTables::of($presensi)
            ->addColumn('hari', function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat('l');
            })
            ->addColumn('tanggal', function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat('d F Y');
            })
            ->addColumn('aksi', function ($row) {
                return '
                <a href="' . route('presensi.detail', $row->id) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="' . route('presensi.edit', $row->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                <form id="delete-form-' . $row->id . '" action="' . route('presensi.destroy', $row->id) . '" method="POST" class="d-inline">
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
        return view('pages.guru.presensi.create');
    }

    public function createPresensiData()
    {
        $guru = auth()->user()->guru;

        $siswaList = collect();

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

        foreach ($request->siswa_id as $index => $siswa_id) {
            $status = $request->status[$siswa_id];

            // Cari kartu studi aktif milik siswa
            $kartuStudi = KartuStudi::where('siswa_id', $siswa_id)->latest()->first();

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
                    ]);
                }
            }
        }

        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil disimpan.');
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
