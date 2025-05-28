<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $feedback = Feedback::with('aktivitasHarian')->get();

        return \Yajra\DataTables\Facades\DataTables::of($feedback)
            ->addColumn('aksi', function ($row) {
                return '
                <a href="' . route('feedback.show', $row->id) . '" class="btn btn-info btn-action" data-toggle="tooltip" title="Detail">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="' . route('feedback.edit', $row->id) . '" class="btn btn-warning btn-action"><i class="fas fa-pencil-alt"></i></a>
                <form id="delete-form-' . $row->id . '" action="' . route('feedback.destroy', $row->id) . '" method="POST" class="d-inline">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-danger btn-action" data-toggle="tooltip" title="Hapus">
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
