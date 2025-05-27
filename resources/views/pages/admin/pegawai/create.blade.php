@extends('layouts.app')

@section('title', 'Tambah Data Pegawai')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Data Pegawai</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('pegawai.guru.index') }}">Data Guru</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ url()->previous() }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('pegawai.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Role <span class="text-danger">*</span></label>
                                <select class="form-control select2" id="role" name="role" required>
                                    <option value="guru" selected>Guru</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="nama_guru">Nama Guru<span class="text-danger">*</span></label>
                                <input id="nama_guru" type="text" class="form-control" name="nama_guru" required>
                            </div>

                            <div class="form-group">
                                <label for="jabatan">Jabatan<span class="text-danger">*</span></label>
                                <input id="jabatan" type="text" class="form-control" name="jabatan" required>
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
                                <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                                <input id="tempat_lahir" type="text" class="form-control" name="tempat_lahir" required>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input id="tanggal_lahir" type="date" class="form-control" name="tanggal_lahir" required>
                            </div>

                            <div class="form-group">
                                <label for="pendidikan">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                <select id="pendidikan" type="text" class="form-control select2" name="pendidikan"
                                    required>
                                    <option value="-">Pilih Pendidikan Terakhir</option>
                                    <option value="Diploma 3">Diploma 3</option>
                                    <option value="Strata 1">Strata 1</option>
                                    <option value="Strata 2">Strata 2</option>
                                    <option value="Strata 3">Strata 3</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="mulai_bekerja">Mulai Bekerja <span class="text-danger">*</span></label>
                                <input id="mulai_bekerja" type="date" class="form-control" name="mulai_bekerja" required>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="NIP">NIP<span class="text-danger">*</span></label>
                                <input id="NIP" type="text" class="form-control" name="NIP" required>
                            </div>

                            <div class="form-group">
                                <label for="pangkat">Pangkat / Golongan<span class="text-danger">*</span></label>
                                <input id="pangkat" type="text" class="form-control" name="pangkat" required>
                            </div>

                            <div class="form-group">
                                <label for="NUPTK">NUPTK<span class="text-danger">*</span></label>
                                <input id="NUPTK" type="text" class="form-control" name="NUPTK" required>
                            </div>

                            <div class="form-group">
                                <label for="sertifikasi">Sertifikasi </label>
                                <input id="sertifikasi" type="text" class="form-control" name="sertifikasi">
                            </div>

                            <div class="form-group">
                                <label for="no_telp">No Telp <span class="text-danger">*</span></label>
                                <input id="no_telp" type="text" class="form-control" name="no_telp" required>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                <textarea id="alamat" class="form-control" name="alamat" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control" name="email" required>
                            </div>

                            <div class="form-group">
                                <label for="foto">Foto </label>
                                <input id="foto" type="file" class="form-control" name="foto">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Tambah Data Pegawai
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
            $('.select2').select2({});
        });
    </script>
@endpush
