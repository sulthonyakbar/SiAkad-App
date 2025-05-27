<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Angkatan;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class EnsureAcademicYearExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $now = Carbon::now();
        $startYear = $now->month >= 7 ? $now->year : $now->year - 1;
        $endYear = $startYear + 1;
        $tahunAjaran = $startYear . '/' . $endYear;

        $angkatan = Angkatan::firstOrCreate([
            'tahun_ajaran' => $tahunAjaran,
        ]);

        View::share('currentAcademicYear', $tahunAjaran);
        session(['angkatan_aktif' => $angkatan->id]);

        return $next($request);
    }
}
