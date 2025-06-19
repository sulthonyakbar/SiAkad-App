<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Angkatan;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $angkatans = Angkatan::orderBy('tahun_ajaran', 'desc')->get();
        return view('pages.admin.kelas.index', compact('angkatans'));
    }

    public function getKelasData(Request $request)
    {
        $kelas = Kelas::with('guru')->select('kelas.*');

        if ($request->has('angkatan_id') && $request->angkatan_id != '') {
            $kelas->where('angkatan_id', $request->angkatan_id);
        }

        return DataTables::of($kelas)
            ->addColumn('guru', function ($row) {
                return $row->guru->nama_guru ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                return '
                <a href="' . route('kelas.edit', $row->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.kelas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'ruang' => 'required|string|max:50|unique:kelas,ruang',
            'guru_id' => [
                'required',
                Rule::unique('kelas', 'guru_id')->where(function ($query) {
                    return $query->where('angkatan_id', session('angkatan_aktif'));
                }),
            ],
        ], [
            'guru_id.unique' => 'Guru ini sudah menjadi walikelas di kelas lain pada tahun ajaran saat ini.',
        ]);

        $data = $request->all();
        $data['angkatan_id'] = session('angkatan_aktif');

        Kelas::create($data);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
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
        $kelas = Kelas::findOrFail($id);
        return view('pages.admin.kelas.edit', compact('kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'ruang' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kelas', 'ruang')->ignore($kelas->id),
            ],
            'guru_id' => [
                'required',
                'exists:gurus,id',
                Rule::unique('kelas', 'guru_id')
                    ->where(function ($query) {
                        return $query->where('angkatan_id', session('angkatan_aktif'));
                    })
                    ->ignore($kelas->id),
            ],
        ], [
            'guru_id.unique' => 'Guru ini sudah menjadi walikelas di kelas lain pada tahun ajaran ini.',
        ]);

        $data = $request->all();
        $kelas->update($data);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
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
}
