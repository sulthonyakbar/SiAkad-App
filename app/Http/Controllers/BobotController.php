<?php

namespace App\Http\Controllers;

use App\Models\BobotPenilaian;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BobotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.guru.bobot.index');
    }

    public function getBobotData()
    {
        $guru = auth()->user()->guru;

        // Ambil mapel yang diajar oleh guru melalui jadwal
        $mapel = MataPelajaran::whereHas('jadwalPelajaran', function ($query) use ($guru) {
            $query->where('guru_id', $guru->id);
        })->with('bobotPenilaian')->get();

        return datatables()->of($mapel)
            ->addColumn('bobot_uh', function ($row) {
                return $row->bobotPenilaian->bobot_uh ?? '-';
            })
            ->addColumn('bobot_uts', function ($row) {
                return $row->bobotPenilaian->bobot_uts ?? '-';
            })
            ->addColumn('bobot_uas', function ($row) {
                return $row->bobotPenilaian->bobot_uas ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                return '
                <a href="' . route('bobot.edit', $row->bobotPenilaian->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mapel = MataPelajaran::all();
        return view('pages.guru.bobot.create', compact('mapel'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'bobot_uh' => 'required|numeric|min:0|max:100',
            'bobot_uts' => 'required|numeric|min:0|max:100',
            'bobot_uas' => 'required|numeric|min:0|max:100',
        ]);

        // Pastikan total bobot = 100
        $total = $request->bobot_uh + $request->bobot_uts + $request->bobot_uas;
        if ($total != 100) {
            return back()->withErrors(['total' => 'Total bobot harus 100%.']);
        }

        // Cek apakah mapel ini sudah punya bobot
        $mapel = MataPelajaran::where('id', $request->mapel_id)->first();
        if ($mapel->bobot_id) {
            return back()->withErrors(['mapel' => 'Bobot untuk mata pelajaran ini sudah ada.']);
        }

        // Simpan bobot penilaian
        $bobot = BobotPenilaian::create([
            'bobot_uh' => $request->bobot_uh,
            'bobot_uts' => $request->bobot_uts,
            'bobot_uas' => $request->bobot_uas,
        ]);

        // Update mapel dengan relasi ke bobot penilaian
        $mapel->update(['bobot_id' => $bobot->id]);

        return redirect()->route('bobot.index')->with('success', 'Bobot penilaian berhasil ditambahkan.');
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
        $bobot = BobotPenilaian::with('mapel')->findOrFail($id);

        return view('pages.guru.bobot.edit', compact('bobot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bobot = BobotPenilaian::findOrFail($id);

        $request->validate([
            'bobot_uh'  => 'required|numeric|min:0|max:100',
            'bobot_uts' => 'required|numeric|min:0|max:100',
            'bobot_uas' => 'required|numeric|min:0|max:100',
        ]);

        if (($request->bobot_uh + $request->bobot_uts + $request->bobot_uas) != 100) {
            return back()->withErrors(['error' => 'Total bobot harus 100 %.']);
        }

        $bobot->update($request->only('bobot_uh', 'bobot_uts', 'bobot_uas'));

        return redirect()->route('bobot.index')->with('success', 'Bobot penilaian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function searchMapel(Request $request): JsonResponse
    {
        $query = $request->input('q');
        $guru = auth()->user()->guru;

        $data = MataPelajaran::select("mata_pelajarans.id", "mata_pelajarans.nama_mapel", "mata_pelajarans.deskripsi")
            ->whereHas('jadwalPelajaran', function ($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('nama_mapel', 'LIKE', '%' . $query . '%')
                        ->orWhere('deskripsi', 'LIKE', '%' . $query . '%');
                });
            })
            ->get();

        return response()->json($data);
    }
}
