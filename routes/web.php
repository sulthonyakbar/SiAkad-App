<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\KartuStudiController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\FeedbackController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'loginPost'])->name('login.post');
});

Route::group(['middleware' => ['role.admin', 'tahun.ajaran']], function () {
    Route::get('/a/dashboard', [DashboardController::class, 'dashboardAdmin'])->name('admin.dashboard');

    Route::get('/a/pegawai/create', [GuruController::class, 'create'])->name('pegawai.create');
    Route::post('/a/pegawai/store', [GuruController::class, 'store'])->name('pegawai.store');
    Route::get('/a/pegawai/{id}/edit', [GuruController::class, 'edit'])->name('pegawai.edit');
    Route::put('/a/pegawai/{id}', [GuruController::class, 'update'])->name('pegawai.update');
    Route::get('/a/pegawai/{id}/detail', [GuruController::class, 'show'])->name('pegawai.detail');
    Route::patch('/a/pegawai/{id}', [GuruController::class, 'status'])->name('pegawai.status');

    Route::get('/a/pegawai/guru', [GuruController::class, 'index'])->name('pegawai.guru.index');
    Route::get('/a/pegawai/guru/data', [GuruController::class, 'getGuruData'])->name('guru.data');

    Route::get('/a/pegawai/admin', [GuruController::class, 'indexAdmin'])->name('pegawai.admin.index');
    Route::get('/a/pegawai/admin/data', [GuruController::class, 'getAdminData'])->name('admin.data');

    Route::get('/a/alumni', [SiswaController::class, 'indexAlumni'])->name('alumni.index');
    Route::get('/a/alumni/data', [SiswaController::class, 'getAlumniData'])->name('alumni.data');

    Route::get('/a/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/a/siswa/data', [SiswaController::class, 'getSiswaData'])->name('siswa.data');
    Route::get('/a/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
    Route::post('/a/siswa/store', [SiswaController::class, 'store'])->name('siswa.store');
    Route::get('/a/siswa/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
    Route::put('/a/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::get('/a/siswa/{id}/detail', [SiswaController::class, 'show'])->name('siswa.detail');
    Route::patch('/a/siswa/{id}/{status}', [SiswaController::class, 'status'])->name('siswa.status');

    Route::get('/a/akun/siswa', [SiswaController::class, 'indexAkunSiswa'])->name('siswa.akun.index');
    Route::get('/a/akun/siswa/data', [SiswaController::class, 'getAkunSiswaData'])->name('siswa.akun.data');
    Route::get('/a/akun/guru', [GuruController::class, 'indexAkunGuru'])->name('guru.akun.index');
    Route::get('/a/akun/guru/data', [GuruController::class, 'getAkunGuruData'])->name('guru.akun.data');
    Route::get('/a/akun/admin', [GuruController::class, 'indexAkunAdmin'])->name('admin.akun.index');
    Route::get('/a/akun/admin/data', [GuruController::class, 'getAkunAdminData'])->name('admin.akun.data');

    Route::get('/a/akun/{user}/edit', [AuthController::class, 'edit'])->name('akun.edit');
    Route::put('/a/akun/{user}', [AuthController::class, 'update'])->name('akun.update');

    Route::get('/a/kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::get('/a/kelas/data', [KelasController::class, 'getKelasData'])->name('kelas.data');
    Route::get('/a/kelas/search-guru', [KelasController::class, 'searchGuru'])->name('search.guru');
    Route::get('/a/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
    Route::post('/a/kelas/store', [KelasController::class, 'store'])->name('kelas.store');
    Route::get('/a/kelas/{id}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
    Route::put('/a/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/a/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');

    Route::get('/a/kartu-studi/search-siswa', [KartuStudiController::class, 'searchSiswa'])->name('ks.search.siswa');
    Route::get('/a/kartu-studi/search-kelas', [KartuStudiController::class, 'searchKelas'])->name('ks.search.kelas');
    Route::get('/a/kartu-studi', [KartuStudiController::class, 'index'])->name('kartu.studi.index');
    Route::get('/a/kartu-studi/data', [KartuStudiController::class, 'getKSData'])->name('kartu.studi.data');
    Route::get('/a/kartu-studi/create', [KartuStudiController::class, 'create'])->name('kartu.studi.create');
    Route::post('/a/kartu-studi/store', [KartuStudiController::class, 'store'])->name('kartu.studi.store');
    Route::get('/a/kartu-studi/{id}/detail', [KartuStudiController::class, 'show'])->name('kartu.studi.detail');
    Route::get('/a/kartu-studi/{id}/detail/data', [KartuStudiController::class, 'showSiswa'])->name('kartu.studi.detail.data');
    Route::get('/a/kartu-studi/{id}/detail/siswa', [KartuStudiController::class, 'showKSSiswa'])->name('kartu.studi.siswa');
    Route::get('/a/kartu-studi/{id}/edit', [KartuStudiController::class, 'edit'])->name('kartu.studi.edit');
    Route::put('/a/kartu-studi/{id}', [KartuStudiController::class, 'update'])->name('kartu.studi.update');
    Route::delete('/a/kartu-studi/{id}', [KartuStudiController::class, 'destroy'])->name('kartu.studi.destroy');

    Route::get('/a/mapel', [MapelController::class, 'index'])->name('mapel.index');
    Route::get('/a/mapel/data', [MapelController::class, 'getMapelData'])->name('mapel.data');
    Route::get('/a/mapel/create', [MapelController::class, 'create'])->name('mapel.create');
    Route::post('/a/mapel/store', [MapelController::class, 'store'])->name('mapel.store');
    Route::get('/a/mapel/{id}/edit', [MapelController::class, 'edit'])->name('mapel.edit');
    Route::put('/a/mapel/{id}', [MapelController::class, 'update'])->name('mapel.update');
    Route::delete('/a/mapel/{id}', [MapelController::class, 'destroy'])->name('mapel.destroy');

    Route::get('/a/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/a/jadwal/search-guru', [JadwalController::class, 'searchGuru'])->name('search.guru');
    Route::get('/a/jadwal/search-kelas', [JadwalController::class, 'searchKelas'])->name('search.kelas');
    Route::get('/a/jadwal/search-mapel', [JadwalController::class, 'searchMapel'])->name('search.mapel');
    Route::get('/a/jadwal/data', [JadwalController::class, 'getJadwalData'])->name('jadwal.data');
    Route::get('/a/jadwal/create', [JadwalController::class, 'create'])->name('jadwal.create');
    Route::post('/a/jadwal/store', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::get('/a/jadwal/{id}/edit', [JadwalController::class, 'edit'])->name('jadwal.edit');
    Route::put('/a/jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/a/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

    Route::get('/a/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/a/pengumuman/data', [PengumumanController::class, 'getPengumumanData'])->name('pengumuman.data');
    Route::get('/a/pengumuman/search-kategori', [PengumumanController::class, 'searchKategori'])->name('search.kategori');
    Route::get('/a/pengumuman/create', [PengumumanController::class, 'create'])->name('pengumuman.create');
    Route::post('/a/pengumuman/store', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::get('/a/pengumuman/{id}/detail', [PengumumanController::class, 'show'])->name('pengumuman.detail');
    Route::get('/a/pengumuman/{id}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit');
    Route::put('/a/pengumuman/{id}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/a/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

    Route::get('/a/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/a/kategori/data', [KategoriController::class, 'getKategoriData'])->name('kategori.data');
    Route::get('/a/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/a/kategori/store', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/a/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/a/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/a/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
});

Route::group(['middleware' => 'role.orangtua'], function () {
    Route::get('/s/dashboard', [DashboardController::class, 'dashboardSiswa'])->name('siswa.dashboard');
    Route::get('/s/profile', [SiswaController::class, 'profile'])->name('siswa.profile');
    Route::put('/s/profile', [SiswaController::class, 'update'])->name('siswa.update.profile');

    Route::get('/s/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/s/feedback/data', [FeedbackController::class, 'getFeedbackData'])->name('feedback.data');
    Route::get('/s/feedback/create/{aktivitas_id}', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/s/feedback/store', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/s/feedback/{id}/detail', [FeedbackController::class, 'show'])->name('feedback.detail');
    Route::get('/s/feedback/{id}/edit', [FeedbackController::class, 'edit'])->name('feedback.edit');
    Route::put('/s/feedback/{id}', [FeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/s/feedback/{id}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');
});

Route::group(['middleware' => 'role.guru'], function () {
    Route::get('/g/dashboard', [DashboardController::class, 'dashboardGuru'])->name('guru.dashboard');
    Route::get('/g/profile', [GuruController::class, 'profile'])->name('guru.profile');
    Route::put('/g/profile', [GuruController::class, 'updateProfile'])->name('guru.update.profile');

    Route::get('/g/aktivitas', [AktivitasController::class, 'index'])->name('aktivitas.index');
    Route::get('/g/aktivitas/data', [AktivitasController::class, 'getAktivitasData'])->name('aktivitas.data');
    Route::get('/g/aktivitas/search-siswa', [AktivitasController::class, 'searchSiswa'])->name('aktivitas.search.siswa');
    Route::get('/g/aktivitas/create', [AktivitasController::class, 'create'])->name('aktivitas.create');
    Route::post('/g/aktivitas/store', [AktivitasController::class, 'store'])->name('aktivitas.store');
    Route::get('/g/aktivitas/{id}/detail', [AktivitasController::class, 'show'])->name('aktivitas.detail');
    Route::get('/g/aktivitas/{id}/edit', [AktivitasController::class, 'edit'])->name('aktivitas.edit');
    Route::put('/g/aktivitas/{id}', [AktivitasController::class, 'update'])->name('aktivitas.update');
    Route::delete('/g/aktivitas/{id}', [AktivitasController::class, 'destroy'])->name('aktivitas.destroy');

    Route::get('/g/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::get('/g/presensi/data', [PresensiController::class, 'getPresensiData'])->name('presensi.data');
    Route::get('/g/presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
    Route::get('/g/presensi/create/data', [PresensiController::class, 'createPresensiData'])->name('presensi.create.data');
    Route::post('/g/presensi/store', [PresensiController::class, 'store'])->name('presensi.store');
    Route::get('/g/presensi/{id}/detail', [PresensiController::class, 'show'])->name('presensi.detail');
    Route::get('/g/presensi/{id}/edit', [PresensiController::class, 'edit'])->name('presensi.edit');
    Route::put('/g/presensi/{id}', [PresensiController::class, 'update'])->name('presensi.update');
    Route::delete('/g/presensi/{id}', [PresensiController::class, 'destroy'])->name('presensi.destroy');

    Route::get('/g/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('/g/nilai/data', [NilaiController::class, 'getNilaiData'])->name('nilai.data');
    Route::get('/g/nilai/create/{id}', [NilaiController::class, 'create'])->name('nilai.create');
    Route::post('/g/nilai/store', [NilaiController::class, 'store'])->name('nilai.store');
    Route::get('/g/nilai/{id}/detail', [NilaiController::class, 'show'])->name('nilai.detail');
    Route::get('/g/nilai/{id}/edit', [NilaiController::class, 'edit'])->name('nilai.edit');
    Route::put('/g/nilai/{id}', [NilaiController::class, 'update'])->name('nilai.update');
    Route::delete('/g/nilai/{id}', [NilaiController::class, 'destroy'])->name('nilai.destroy');
});

Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
