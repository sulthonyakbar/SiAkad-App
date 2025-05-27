<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KartuStudi;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\Siswa;
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
                <a href="' . route('nilai.edit', $row->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                <form id="delete-form-' . $row->id . '" action="' . route('nilai.destroy', $row->id) . '" method="POST" class="d-inline">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-danger btn-action" data-toggle="tooltip" title="Hapus" onclick="confirmDelete(event, \'delete-form-' . $row->id . '\')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>';
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
        $angkatanId = session('angkatan_aktif');

        // Ambil kelas yang diajar guru
        $kelas = Kelas::where('guru_id', $guru->id)->first();

        if (!$kelas) {
            return back()->with('error', 'Guru tidak memiliki kelas.');
        }

        // Cek apakah siswa terdaftar dalam kelas guru di angkatan aktif
        $kartuStudis = KartuStudi::with(['siswa', 'kelas', 'nilai'])
            ->whereHas('kelas', function ($query) use ($kelas, $angkatanId) {
                $query->where('id', $kelas->id)->where('angkatan_id', $angkatanId);
            })
            ->where('siswa_id', $id)
            ->get();

        if ($kartuStudis->isEmpty()) {
            return back()->with('error', 'Siswa tidak ditemukan dalam kelas guru.');
        }

        // Ambil satu jadwal (untuk header info saja, misalnya kelas dan mapel pertama)
        $jadwal = $kartuStudis->first();

        return view('pages.guru.nilai.create', [
            'kartuStudis' => $kartuStudis,
            'jadwal' => $jadwal,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        foreach ($request->nilai as $ks_id => $data) {
            $kartuStudi = KartuStudi::find($ks_id);

            if (!$kartuStudi) continue;

            $nilai = $kartuStudi->nilai;

            if (!$nilai) {
                // Jika belum ada nilai, buat baru
                $nilai = Nilai::create([
                    'nilai_uh' => $data['uh'],
                    'nilai_uts' => $data['uts'],
                    'nilai_uas' => $data['uas'],
                    'nilai_akhir' => $this->hitungNilaiAkhir($data, $kartuStudi),
                ]);
                $kartuStudi->nilai_id = $nilai->id;
                $kartuStudi->save();
            } else {
                // Jika sudah ada nilai, update
                $nilai->update([
                    'nilai_uh' => $data['uh'],
                    'nilai_uts' => $data['uts'],
                    'nilai_uas' => $data['uas'],
                    'nilai_akhir' => $this->hitungNilaiAkhir($data, $kartuStudi),
                ]);
            }
        }

        return back()->with('success', 'Nilai berhasil disimpan atau diperbarui.');
    }

    private function hitungNilaiAkhir($data, $kartuStudi)
    {
        $bobot = $kartuStudi->nilai->mapel->bobot ?? null;
        if (!$bobot) return 0;

        return (
            $data['uh'] * $bobot->bobot_uh / 100 +
            $data['uts'] * $bobot->bobot_uts / 100 +
            $data['uas'] * $bobot->bobot_uas / 100
        );
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
