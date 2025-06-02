<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\JadwalPelajaran;
use App\Models\Pengumuman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboardAdmin()
    {
        $siswaCount = Siswa::count();

        $guruCount = Guru::whereHas('user', function ($query) {
            $query->where('role', 'guru');
        })->count();

        $kelasCount = Kelas::count();

        $jadwalCount = JadwalPelajaran::count();

        $jadwal = JadwalPelajaran::with(['mapel', 'kelas', 'gurus'])->get();

        $events = $jadwal->map(function ($item) {
            // Mapping nama hari ke tanggal minggu ini (misal, Senin = 2025-05-19)
            $days = [
                'Senin' => '2025-05-19',
                'Selasa' => '2025-05-20',
                'Rabu' => '2025-05-21',
                'Kamis' => '2025-05-22',
                'Jumat' => '2025-05-23',
                'Sabtu' => '2025-05-24',
                'Minggu' => '2025-05-25',
            ];

            $tanggal = $days[$item->hari] ?? now()->toDateString();

            return [
                'title' => $item->mapel->nama_mapel . ' - Kelas ' . $item->kelas->nama_kelas . ' - Pengajar ' . $item->gurus->nama_guru,
                'start' => $tanggal . 'T' . $item->jam_mulai,
                'end' => $tanggal . 'T' . $item->jam_selesai,
            ];
        });

        return view('pages.admin.dashboard', compact('siswaCount', 'guruCount', 'kelasCount', 'jadwalCount', 'events'));
    }

    public function dashboardGuru()
    {
        $pengumuman = Pengumuman::latest()->take(4)->get();

        $guru = auth()->user()->guru;

        $jadwal = JadwalPelajaran::with(['mapel', 'kelas', 'gurus'])
            ->where('guru_id', $guru->id)
            ->get();

        $events = $jadwal->map(function ($item) {
            $today = Carbon::now(); // hari ini

            // Hitung awal minggu ini (Senin)
            $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);

            // Hari keberapa dalam minggu (Senin = 0, Selasa = 1, dst)
            $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

            // Cek index dari nama hari pada jadwal
            $dayIndex = array_search($item->hari, $dayNames);

            if ($dayIndex === false) {
                return null; // skip jika hari tidak cocok
            }

            // Dapatkan tanggal untuk hari tersebut minggu ini
            $date = $startOfWeek->copy()->addDays($dayIndex)->toDateString();

            return [
                'title' => $item->mapel->nama_mapel . ' - Kelas ' . $item->kelas->nama_kelas,
                'start' => $date . 'T' . $item->jam_mulai,
                'end' => $date . 'T' . $item->jam_selesai,
            ];
        })->filter();

        return view('pages.guru.dashboard', compact('pengumuman', 'events'));
    }

    public function dashboardSiswa()
    {
        $pengumuman = Pengumuman::latest()->take(4)->get();

        $siswa = auth()->user()->siswa;

        $jadwal = JadwalPelajaran::with(['mapel', 'kelas', 'gurus'])
            ->whereHas('kelas', function ($query) use ($siswa) {
                $query->where('id', $siswa->kelas_id);
            })
            ->get();

        $events = $jadwal->map(function ($item) {
            // Mapping nama hari ke tanggal minggu ini (misal, Senin = 2025-05-19)
            $days = [
                'Senin' => '2025-05-19',
                'Selasa' => '2025-05-20',
                'Rabu' => '2025-05-21',
                'Kamis' => '2025-05-22',
                'Jumat' => '2025-05-23',
                'Sabtu' => '2025-05-24',
                'Minggu' => '2025-05-25',
            ];

            $tanggal = $days[$item->hari] ?? now()->toDateString();

            return [
                'title' => $item->mapel->nama_mapel . ' - Kelas ' . $item->kelas->nama_kelas . ' - Pengajar ' . $item->gurus->nama_guru,
                'start' => $tanggal . 'T' . $item->jam_mulai,
                'end' => $tanggal . 'T' . $item->jam_selesai,
            ];
        });

        return view('pages.siswa.dashboard', compact('pengumuman', 'events'));
    }
}
