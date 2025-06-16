<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\JadwalPelajaran;
use App\Models\Pengumuman;
use App\Models\Presensi;
use App\Models\DetailPresensi;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    private function mapJadwalToCalendarEvents(Collection $jadwal, callable $titleFormatter): Collection
    {
        return $jadwal->map(function ($item) use ($titleFormatter) {
            // Get today's date
            $today = Carbon::now();

            // Determine the start of the current week (Monday)
            $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);

            // Define the order of days in a week
            $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

            // Find the index of the schedule's day
            $dayIndex = array_search($item->hari, $dayNames);

            // If the day name is not valid, skip this item
            if ($dayIndex === false) {
                // Optionally log an error or handle this case
                // error_log("Invalid day name: " . $item->hari . " for jadwal ID: " . $item->id);
                return null;
            }

            // Calculate the date for the event based on the day of the current week
            $eventDate = $startOfWeek->copy()->addDays($dayIndex)->toDateString();

            // Ensure related models are loaded to prevent N+1 issues if not eager loaded before
            // This is a safeguard; ideally, $jadwal should already have these eager loaded.
            $item->loadMissing(['mapel', 'kelas', 'gurus']);

            // Check if mapel, kelas, or gurus (for admin/siswa) are null to prevent errors
            if (!$item->mapel || !$item->kelas) {
                // error_log("Missing mapel or kelas for jadwal ID: " . $item->id);
                return null;
            }
            // For admin/siswa, gurus relation is also expected
            if (str_contains($titleFormatter($item), 'Pengajar') && !$item->gurus) {
                // error_log("Missing gurus for jadwal ID: " . $item->id . " when Pengajar is expected in title.");
                return null;
            }

            return [
                'title' => $titleFormatter($item),
                'start' => $eventDate . 'T' . $item->jam_mulai,
                'end' => $eventDate . 'T' . $item->jam_selesai,
            ];
        })->filter();
    }

    public function dashboardAdmin()
    {
        $siswaCount = Siswa::count();

        $guruCount = Guru::whereHas('user', function ($query) {
            $query->where('role', 'guru');
        })->count();

        $kelasCount = Kelas::count();

        $jadwalCount = JadwalPelajaran::count();

        $jadwal = JadwalPelajaran::with(['mapel', 'kelas', 'gurus'])->get();

        $titleFormatter = function ($item) {
            $namaGuru = $item->gurus ? $item->gurus->nama_guru : 'N/A';
            return $item->mapel->nama_mapel . ' - Kelas ' . $item->kelas->nama_kelas . ' - Pengajar ' . $namaGuru;
        };

        $events = $this->mapJadwalToCalendarEvents($jadwal, $titleFormatter);

        return view('pages.admin.dashboard', compact('siswaCount', 'guruCount', 'kelasCount', 'jadwalCount', 'events'));
    }

    public function dashboardGuru()
    {
        $pengumuman = Pengumuman::latest()->take(4)->get();

        $guru = auth()->user()->guru;

        $jadwal = JadwalPelajaran::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->get();

        $titleFormatter = function ($item) {
            return $item->mapel->nama_mapel . ' - Kelas ' . $item->kelas->nama_kelas;
        };

        $events = $this->mapJadwalToCalendarEvents($jadwal, $titleFormatter);

        if ($guru) {
            $kelasWali = Kelas::where('guru_id', $guru->id)->first();
            if ($kelasWali) {

                $angkatanId = session('angkatan_aktif');

                // 2. Ambil jumlah siswa di kelasnya pada angkatan aktif
                $jumlahSiswa = Siswa::whereHas('kartuStudi', function ($query) use ($kelasWali, $angkatanId) {
                    $query->where('kelas_id', $kelasWali->id)
                        ->where('angkatan_id', $angkatanId);
                })->count();

                // 3. Ambil rekap presensi hari ini untuk kelas wali
                $presensiHariIni = Presensi::where('kelas_id', $kelasWali->id)
                    ->whereDate('tanggal', Carbon::today())
                    ->first();

                if ($presensiHariIni) {
                    $rekapPresensi['hadir'] = DetailPresensi::where('presensi_id', $presensiHariIni->id)
                        ->where('status', 'Hadir')
                        ->count();
                }
            }
        }

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

        $titleFormatter = function ($item) {
            $namaGuru = $item->gurus ? $item->gurus->nama_guru : 'N/A';
            return $item->mapel->nama_mapel . ' - Kelas ' . $item->kelas->nama_kelas . ' - Pengajar ' . $namaGuru;
        };

        $events = $this->mapJadwalToCalendarEvents($jadwal, $titleFormatter);

        return view('pages.siswa.dashboard', compact('pengumuman', 'events'));
    }
}
