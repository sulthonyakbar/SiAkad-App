<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengumuman;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\JsonResponse;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.admin.pengumuman.index');
    }

    public function getPengumumanData()
    {
        $pengumuman = Pengumuman::with(['kategori', 'guru'])->get();

        return DataTables::of($pengumuman)
            ->addColumn('penulis', function ($row) {
                return $row->guru->nama_guru ?? '-';
            })
            ->addColumn('aksi', function ($row) {
                return '
                <a href="' . route('pengumuman.detail', $row->id) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="' . route('pengumuman.edit', $row->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.pengumuman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'required|image|mimes:png|max:2048',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $data = $request->only(['judul', 'isi', 'kategori_id']);
        $data['guru_id'] = Auth::user()->guru->id;

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $slugJudul = Str::slug(Str::limit($request->judul, 20, ''), '_') . '_' . time();
            $imageName = time() . '_' . $image->getClientOriginalName();
            $uploadPath = public_path('images/pengumuman/' . $slugJudul);

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $image->move($uploadPath, $imageName);
            $data['gambar'] = 'images/pengumuman/' . $slugJudul . '/' . $imageName;
        }

        Pengumuman::create($data);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengumuman = Pengumuman::with(['kategori', 'guru'])->findOrFail($id);

        return view('pages.admin.pengumuman.detail', compact('pengumuman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        return view('pages.admin.pengumuman.edit', compact('pengumuman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:png|max:2048',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $data = $request->only(['judul', 'isi', 'kategori_id']);
        $data['guru_id'] = Auth::user()->guru->id;

        if ($request->hasFile('gambar')) {
            if ($pengumuman->gambar && file_exists(public_path($pengumuman->gambar))) {
                unlink(public_path($pengumuman->gambar));
            }

            $image = $request->file('gambar');
            $slugJudul = Str::slug(Str::limit($request->judul, 20, ''), '_') . '_' . time();
            $imageName = time() . '_' . $image->getClientOriginalName();
            $uploadPath = public_path('images/pengumuman/' . $slugJudul);

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $image->move($uploadPath, $imageName);
            $data['gambar'] = 'images/pengumuman/' . $slugJudul . '/' . $imageName;
        }

        $pengumuman->update($data);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function searchKategori(Request $request): JsonResponse
    {
        $query = $request->input('q');

        $data = Kategori::select("kategoris.id", "kategoris.nama_kategori")
            ->when($query, function ($q) use ($query) {
                $q->where('kategoris.nama_kategori', 'LIKE', '%' . $query . '%');
            })
            ->get();

        return response()->json($data);
    }

    public function readPengumuman($id)
    {
        $role = auth()->user()->role;

        if (!in_array($role, ['guru', 'orangtua'])) {
            abort(403, 'Anda tidak memiliki akses');
        }

        $pengumuman = Pengumuman::with(['kategori', 'guru'])->findOrFail($id);

        return view('pages.admin.pengumuman.read', compact('pengumuman'));
    }
}
