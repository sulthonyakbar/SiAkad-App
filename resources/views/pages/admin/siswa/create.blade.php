@extends('layouts.app')

@section('title', 'Tambah Data Siswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Data Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('siswa.index') }}">Data Siswa</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('siswa.index') }}" role="button"><i class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('siswa.store') }}" enctype="multipart/form-data">
                    @csrf

                    <h5 class="text-primary">Informasi Siswa</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_siswa">Nama Siswa<span class="text-danger">*</span></label>
                                <input id="nama_siswa" type="text" class="form-control" name="nama_siswa" required>
                            </div>
                            <div class="form-group">
                                <label for="nomor_induk">Nomor Induk Asal<span class="text-danger">*</span></label>
                                <input id="nomor_induk" type="text" class="form-control" name="nomor_induk" required>
                            </div>
                            <div class="form-group">
                                <label for="NISN">NISN<span class="text-danger">*</span></label>
                                <input id="NISN" type="text" class="form-control" name="NISN" required>
                            </div>
                            <div class="form-group">
                                <label for="NIK">NIK<span class="text-danger">*</span></label>
                                <input id="NIK" type="text" class="form-control" name="NIK" required>
                            </div>
                            <div class="form-group">
                                <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                                <input id="tempat_lahir" type="text" class="form-control" name="tempat_lahir" required>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input id="tanggal_lahir" type="date" class="form-control" name="tanggal_lahir" required>
                            </div>

                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select id="jenis_kelamin" type="text" class="form-control select2" name="jenis_kelamin"
                                    required>
                                    <option value="-">Pilih Jenis Kelamin</option>
                                    <option value="Laki-Laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="no_telp_siswa">No Telp <span class="text-danger">*</span></label>
                                <input id="no_telp_siswa" type="text" class="form-control" name="no_telp_siswa" required>
                            </div>

                            <div class="form-group">
                                <label for="alamat_siswa">Alamat <span class="text-danger">*</span></label>
                                <textarea id="alamat_siswa" class="form-control" name="alamat_siswa" required></textarea>
                            </div>

                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control" name="email" required>
                            </div>

                            <div class="form-group">
                                <label for="foto">Foto </span></label>
                                <input id="foto" type="file" class="form-control" name="foto">
                            </div>

                            <div class="form-group">
                                <label for="tamatan">Tamatan dari <span class="text-danger">*</span></label>
                                <input id="tamatan" type="text" class="form-control" name="tamatan" required>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_lulus">Tanggal Lulus <span class="text-danger">*</span></label>
                                <input id="tanggal_lulus" type="date" class="form-control" name="tanggal_lulus"
                                    required>
                            </div>

                            <div class="form-group">
                                <label for="STTB">NO. STTB <span class="text-danger">*</span></label>
                                <input id="STTB" type="text" class="form-control" name="STTB" required>
                            </div>

                            <div class="form-group">
                                <label for="lama_belajar">Lama Belajar <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input id="lama_belajar" type="text" class="form-control" name="lama_belajar"
                                        required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Tahun</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="pindahan">Pindahan dari </label>
                                <input id="pindahan" type="text" class="form-control" name="pindahan">
                            </div>

                            <div class="form-group">
                                <label for="alasan">Alasan </label>
                                <textarea id="alasan" class="form-control" name="alasan"></textarea>
                            </div>

                            {{-- <div class="form-group">
                                <label for="kelas_id">Kelas <span class="text-danger">*</span></label>
                                <select id="kelas_id" class="form-control" name="kelas_id"></select>
                            </div> --}}
                        </div>
                    </div>

                    <h5 class="text-primary mt-4">Informasi Orang Tua</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_ayah">Nama Ayah <span class="text-danger">*</span></label>
                                <input id="nama_ayah" type="text" class="form-control" name="nama_ayah" required>
                            </div>
                            <div class="form-group">
                                <label for="pendidikan_ayah">Pendidikan Terakhir Ayah <span
                                        class="text-danger">*</span></label>
                                <select id="pendidikan_ayah" type="text" class="form-control select2" name="pendidikan_ayah"
                                    required>
                                    <option value="-">Pilih Pendidikan Terakhir</option>
                                    <option value="Sekolah Dasar">Sekolah Dasar</option>
                                    <option value="Sekolah Menengan Pertama atau sederajat">Sekolah Menengan Pertama atau
                                        sederajat</option>
                                    <option value="Sekolah Menengah Atas atau Kejuruan atau sederajat">Sekolah Menengah
                                        Atas atau Kejuruan atau sederajat</option>
                                    <option value="Diploma 1">Diploma 1</option>
                                    <option value="Diploma 2">Diploma 2</option>
                                    <option value="Diploma 3">Diploma 3</option>
                                    <option value="Strata 1">Strata 1</option>
                                    <option value="Strata 2">Strata 2</option>
                                    <option value="Strata 3">Strata 3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pekerjaan_ayah">Pekerjaan Ayah <span class="text-danger">*</span></label>
                                <input id="pekerjaan_ayah" type="text" class="form-control" name="pekerjaan_ayah"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="penghasilan_ayah">Penghasilan Ayah <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input id="penghasilan_ayah" type="text" class="form-control"
                                        name="penghasilan_ayah" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">/ Bulan</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="alamat_ortu">Alamat Orang Tua <span class="text-danger">*</span></label>
                                <textarea id="alamat_ortu" class="form-control" name="alamat_ortu" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_ibu">Nama Ibu <span class="text-danger">*</span></label>
                                <input id="nama_ibu" type="text" class="form-control" name="nama_ibu" required>
                            </div>
                            <div class="form-group">
                                <label for="pendidikan_ibu">Pendidikan Ibu <span class="text-danger">*</span></label>
                                <select id="pendidikan_ibu" type="text" class="form-control select2" name="pendidikan_ibu"
                                    required>
                                    <option value="-">Pilih Pendidikan Terakhir</option>
                                    <option value="Sekolah Dasar">Sekolah Dasar</option>
                                    <option value="Sekolah Menengan Pertama atau sederajat">Sekolah Menengan Pertama atau
                                        sederajat</option>
                                    <option value="Sekolah Menengah Atas atau Kejuruan atau sederajat">Sekolah Menengah
                                        Atas atau Kejuruan atau sederajat</option>
                                    <option value="Diploma 3">Diploma 1</option>
                                    <option value="Diploma 3">Diploma 2</option>
                                    <option value="Diploma 3">Diploma 3</option>
                                    <option value="Strata 1">Strata 1</option>
                                    <option value="Strata 2">Strata 2</option>
                                    <option value="Strata 3">Strata 3</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pekerjaan_ibu">Pekerjaan Ibu <span class="text-danger">*</span></label>
                                <input id="pekerjaan_ibu" type="text" class="form-control" name="pekerjaan_ibu"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="penghasilan_ibu">Penghasilan Ibu <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input id="penghasilan_ibu" type="text" class="form-control"
                                        name="penghasilan_ibu" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">/ Bulan</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="no_telp_ortu">No. Telp Orang Tua <span class="text-danger">*</span></label>
                                <input id="no_telp_ortu" type="text" class="form-control" name="no_telp_ortu"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Tambah Data Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/components-table.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
            });
        });
    </script>
@endpush
