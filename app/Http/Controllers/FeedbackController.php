<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\AktivitasHarian;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.siswa.feedback.index');
    }

    public function getFeedbackData()
    {
        $siswa = auth()->user()->siswa;

        $aktivitas = AktivitasHarian::with('feedback')
            ->where('siswa_id', $siswa->id)
            ->get();

        return DataTables::of($aktivitas)
            ->addColumn('aksi_feedback', function ($row) {
                if ($row->feedback) {
                    return '<button class="btn btn-success btn-action" disabled><i class="fa fa-check"></i></button>';
                } else {
                    return '
                    <a href="' . route('feedback.create', ['aktivitas_id' => $row->id]) . '" class="btn btn-primary btn-action" data-toggle="tooltip" title="Tambah Feedback">
                        <i class="fa fa-plus"></i>
                    </a>';
                }
            })
            ->addColumn('aksi', function ($row) {
                $detailBtn = '
                <a href="' . route('feedback.detail', $row->id) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>';

                if ($row->feedback) {
                    $editBtn = '
                    <a href="' . route('feedback.edit', $row->id) . '" class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>';
                } else {
                    $editBtn = '
                    <button class="btn btn-warning btn-action" data-toggle="tooltip" title="Edit" disabled><i class="fas fa-pencil-alt"></i></button>';
                }

                return $detailBtn . $editBtn;
            })
            ->rawColumns(['aksi_feedback', 'aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($aktivitas_id)
    {
        $aktivitas = AktivitasHarian::findOrFail($aktivitas_id);
        return view('pages.siswa.feedback.create', compact('aktivitas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pesan' => 'required|string|max:1000',
            'aktivitas_id' => 'required|exists:aktivitas_harians,id',
        ]);

        $feedback = new Feedback();
        $feedback->pesan = $request->pesan;
        $feedback->save();

        $aktivitas = AktivitasHarian::findOrFail($request->aktivitas_id);
        $aktivitas->feedback_id = $feedback->id;
        $aktivitas->save();

        return redirect()->route('feedback.index')->with('success', 'Feedback berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $aktivitas = AktivitasHarian::with('feedback')->findOrFail($id);
        return view('pages.siswa.feedback.detail', compact('aktivitas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $aktivitas = AktivitasHarian::with('feedback')->findOrFail($id);
        return view('pages.siswa.feedback.edit', compact('aktivitas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'pesan' => 'required|string|max:1000',
        ]);

        $aktivitas = AktivitasHarian::findOrFail($id);

        if ($aktivitas->feedback) {
            $aktivitas->feedback->pesan = $request->pesan;
            $aktivitas->feedback->save();
        } else {
            $feedback = new Feedback();
            $feedback->pesan = $request->pesan;
            $feedback->save();
            $aktivitas->feedback_id = $feedback->id;
            $aktivitas->save();
        }

        return redirect()->route('feedback.index')->with('success', 'Feedback berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $aktivitas = AktivitasHarian::findOrFail($id);
        if ($aktivitas->feedback) {
            $aktivitas->feedback->delete();
        }
        $aktivitas->feedback_id = null;
        $aktivitas->save();

        return redirect()->route('feedback.index')->with('success', 'Feedback berhasil dihapus');
    }
}
