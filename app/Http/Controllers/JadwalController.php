<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Guru;
use App\Models\Angkatan;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use Illuminate\Http\JsonResponse;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $angkatans = Angkatan::orderBy('tahun_ajaran', 'desc')->get();
        return view('pages.admin.jadwal.index', compact('angkatans'));
    }

    public function getJadwalData(Request $request)
    {
        $jadwal = JadwalPelajaran::with('kelas', 'mapel', 'guru')->select('jadwal_pelajarans.*');

        if ($request->filled('angkatan_id')) {
            $jadwal->whereHas('kelas', function ($query) use ($request) {
                $query->where('angkatan_id', $request->angkatan_id);
            });
        }

        return DataTables::of($jadwal)
            ->addColumn('kelas', function ($row) {
                return $row->kelas->nama_kelas ?? '-';
            })
            ->addColumn('mapel', function ($row) {
                return $row->mapel->nama_mapel ?? '-';
            })
            ->addColumn('guru', function ($row) {
                return $row->guru->nama_guru ?? '-';
            })
            ->addColumn('jam', function ($row) {
                return $row->jam_mulai . ' - ' . $row->jam_selesai;
            })
            ->addColumn('aksi', function ($row) {
                return '
                <a href="' . route('jadwal.edit', $row->id) . '" class="btn btn-warning btn-action"><i class="fas fa-pencil-alt"></i></a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.jadwal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jadwals' => 'required|array|min:1',
            'jadwals.*.hari' => 'required|string',
            'jadwals.*.jam_mulai' => 'required|date_format:H:i',
            'jadwals.*.jam_selesai' => 'required|date_format:H:i|after:jadwals.*.jam_mulai',
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'kelas_id' => 'required|exists:kelas,id',
            'guru_id' => 'required|exists:gurus,id',
        ]);

        $angkatanId = Kelas::findOrFail($request->kelas_id)->angkatan_id;

        foreach ($request->jadwals as $jadwal) {
            $exists = JadwalPelajaran::whereHas('kelas', function ($query) use ($angkatanId) {
                    $query->where('angkatan_id', $angkatanId);
                })
                ->where('hari', $jadwal['hari'])
                ->where('jam_mulai', '<', $jadwal['jam_selesai'])
                ->where('jam_selesai', '>', $jadwal['jam_mulai'])
                ->where(function ($query) use ($request) {
                    $query->where('guru_id', $request->guru_id)
                        ->orWhere('kelas_id', $request->kelas_id);
                })
                ->exists();

            if ($exists) {
                return back()->withErrors(['jadwal' => 'Jadwal bentrok! Guru atau Kelas sudah memiliki jadwal lain pada hari dan jam yang sama di tahun ajaran ini.'])->withInput();
            }

            JadwalPelajaran::create([
                'hari' => $jadwal['hari'],
                'jam_mulai' => $jadwal['jam_mulai'],
                'jam_selesai' => $jadwal['jam_selesai'],
                'mapel_id' => $request->mapel_id,
                'kelas_id' => $request->kelas_id,
                'guru_id' => $request->guru_id,
            ]);
        }

        return redirect()->route('jadwal.index')->with('success', 'Jadwal Pelajaran berhasil ditambahkan');
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
        $jadwal = JadwalPelajaran::findOrFail($id);
        return view('pages.admin.jadwal.edit', compact('jadwal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->merge([
            'jam_mulai' => substr($request->jam_mulai, 0, 5),
            'jam_selesai' => substr($request->jam_selesai, 0, 5),
        ]);

        $request->validate([
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'kelas_id' => 'required|exists:kelas,id',
            'guru_id' => 'required|exists:gurus,id',
        ]);

        $jadwal = JadwalPelajaran::findOrFail($id);

        $exists = JadwalPelajaran::where('id', '!=', $id)
            ->where('hari', $request->hari)
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<', $request->jam_selesai)
                    ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->where(function ($query) use ($request) {
                $query->where('kelas_id', $request->kelas_id)
                    ->orWhere('guru_id', $request->guru_id);
            })
            ->exists();

        if ($exists) {
            return back()->withErrors(['jadwal' => 'Terdapat jadwal bentrok untuk guru atau kelas pada waktu yang sama.'])->withInput();
        }

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal Pelajaran berhasil diperbarui');
    }

    public function searchGuru(Request $request): JsonResponse
    {
        $query = $request->input('q');

        $data = Guru::select("gurus.id", "gurus.nama_guru", "gurus.NIP")
            ->join('users', 'users.id', '=', 'gurus.user_id')
            ->where('users.role', 'guru')
            ->when($query, function ($q) use ($query) {
                $q->where('gurus.nama_guru', 'LIKE', '%' . $query . '%')
                    ->orWhere('gurus.NIP', 'LIKE', '%' . $query . '%');
            })
            ->get();

        return response()->json($data);
    }

    public function searchKelas(Request $request): JsonResponse
    {
        $query = $request->input('q');

        $data = Kelas::select("kelas.id", "kelas.nama_kelas", "kelas.ruang")
            ->when($query, function ($q) use ($query) {
                $q->where('kelas.nama_kelas', 'LIKE', '%' . $query . '%')
                    ->orWhere('kelas.ruang', 'LIKE', '%' . $query . '%');
            })
            ->get();

        return response()->json($data);
    }

    public function searchMapel(Request $request): JsonResponse
    {
        $query = $request->input('q');

        $data = MataPelajaran::select("mata_pelajarans.id", "mata_pelajarans.nama_mapel", "mata_pelajarans.deskripsi")
            ->when($query, function ($q) use ($query) {
                $q->where('mata_pelajarans.nama_mapel', 'LIKE', '%' . $query . '%')
                    ->orWhere('mata_pelajarans.deskripsi', 'LIKE', '%' . $query . '%');
            })
            ->get();

        return response()->json($data);
    }
}
