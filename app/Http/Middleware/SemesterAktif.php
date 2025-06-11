<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class SemesterAktif
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
          $now = Carbon::now();

        $semesterName = $now->month >= 7 ? 'Ganjil' : 'Genap';

        $angkatanId = session('angkatan_aktif');

        $semester = Semester::firstOrCreate([
            'nama_semester' => $semesterName,
            'angkatan_id' => $angkatanId,
        ]);

        View::share('currentSemester', $semesterName);
        session(['semester_aktif' => $semester->id]);

        return $next($request);
    }
}
