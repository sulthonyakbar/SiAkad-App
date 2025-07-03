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
            $today = Carbon::now();

            $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);

            $dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

            $dayIndex = array_search($item->hari, $dayNames);

            if ($dayIndex === false) {
                return null;
            }

            $eventDate = $startOfWeek->copy()->addDays($dayIndex)->toDateString();

            $item->loadMissing(['mapel', 'kelas', 'guru']);

            if (!$item->mapel || !$item->kelas) {
                return null;
            }
            if (str_contains($titleFormatter($item), 'Pengajar') && !$item->guru) {
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

        $angkatanId = session('angkatan_aktif');

        $jadwal = JadwalPelajaran::whereHas('kelas', function ($query) use ($angkatanId) {
            $query->where('angkatan_id', $angkatanId);
        })->with(['mapel', 'kelas', 'guru'])->get();

        $titleFormatter = function ($item) {
            $namaGuru = $item->guru ? $item->guru->nama_guru : 'N/A';
            return $item->mapel->nama_mapel . ' - Kelas ' . $item->kelas->nama_kelas . ' - Pengajar ' . $namaGuru;
        };

        $events = $this->mapJadwalToCalendarEvents($jadwal, $titleFormatter);

        return view('pages.admin.dashboard', compact('siswaCount', 'guruCount', 'kelasCount', 'jadwalCount', 'events'));
    }

    public function dashboardGuru()
    {
        $pengumuman = Pengumuman::latest()->take(4)->get();
        $guru = auth()->user()->guru;

        $events = collect();
        $kelasWali = null;
        $jumlahSiswa = 0;
        $rekapPresensi = ['Hadir' => 0];
        $presensiHariIni = null;
        $jumlahKelasAmpu = 0;
        $jumlahMapelAmpu = 0;

        if ($guru) {
            $angkatanId = session('angkatan_aktif');

            $jadwal = JadwalPelajaran::with(['mapel', 'kelas'])
                ->where('guru_id', $guru->id)
                ->whereHas('kelas', function ($query) use ($angkatanId) {
                    $query->where('angkatan_id', $angkatanId);
                })
                ->get();

            $titleFormatter = function ($item) {
                return $item->mapel->nama_mapel . ' - Kelas ' . $item->kelas->nama_kelas;
            };
            $events = $this->mapJadwalToCalendarEvents($jadwal, $titleFormatter);

            $jumlahMapelAmpu = $jadwal->pluck('mapel_id')->unique()->count();

            $kelasWali = Kelas::where('guru_id', $guru->id)->first();

            if ($kelasWali) {
                $jumlahSiswa = Siswa::whereHas('kartuStudi', function ($query) use ($kelasWali, $angkatanId) {
                    $query->where('kelas_id', $kelasWali->id)
                        ->where('angkatan_id', $angkatanId);
                })->count();

                $presensiHariIni = Presensi::where('kelas_id', $kelasWali->id)
                    ->whereDate('tanggal', Carbon::today())
                    ->first();

                if ($presensiHariIni) {
                    $rekapPresensi['Hadir'] = DetailPresensi::where('presensi_id', $presensiHariIni->id)
                        ->where('status', 'Hadir')
                        ->count();
                }
            }
        }

        return view('pages.guru.dashboard', compact('pengumuman', 'events', 'kelasWali', 'jumlahSiswa', 'jumlahMapelAmpu', 'presensiHariIni', 'rekapPresensi', 'jumlahKelasAmpu'));
    }

    public function dashboardSiswa()
    {
        $pengumuman = Pengumuman::latest()->take(4)->get();

        $siswa = auth()->user()->siswa;

        $angkatan = session('angkatan_aktif');

        $kartuStudi = $siswa->kartuStudi()
            ->whereHas('kelas', function ($query) use ($angkatan) {
                $query->where('angkatan_id', $angkatan);
            })
            ->with('kelas')
            ->first();

        $kelasAktif = $kartuStudi?->kelas;
        $kelas = $kelasAktif?->nama_kelas ?? 'Belum Ditentukan';
        $ruang = $kelasAktif?->ruang ?? '-';

        $jadwal = collect();
        if ($kelasAktif) {
            $jadwal = JadwalPelajaran::with(['mapel', 'kelas', 'guru'])
                ->where('kelas_id', $kelasAktif->id)
                ->get();
        }

        $titleFormatter = function ($item) {
            $namaGuru = $item->guru ? $item->guru->nama_guru : 'N/A';
            return $item->mapel->nama_mapel . ' - Kelas ' . $item->kelas->nama_kelas . ' - Pengajar ' . $namaGuru;
        };

        $events = $this->mapJadwalToCalendarEvents($jadwal, $titleFormatter);

        // Cek status presensi hari ini
        $statusPresensi = DetailPresensi::whereHas('presensi', function ($q) use ($kelasAktif) {
            $q->where('kelas_id', $kelasAktif?->id)
                ->whereDate('tanggal', \Carbon\Carbon::today());
        })
            ->where('siswa_id', $siswa->id)
            ->value('status');

        return view('pages.siswa.dashboard', compact('pengumuman', 'events', 'kelas', 'ruang', 'statusPresensi'));
    }
}
