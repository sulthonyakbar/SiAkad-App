@extends('layouts.app')

@section('title', 'Profile Guru')

@push('style')
    <!-- CSS Libraries -->
    <link href="{{ asset('dist/css/lightbox.css') }}" rel="stylesheet">
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Profile Guru</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('guru.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('guru.profile') }}">Profile Guru</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('guru.dashboard') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('guru.update.profile') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ old('nama_guru', Auth::user()->id) }}" required>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_guru">Nama Guru<span class="text-danger">*</span></label>
                                <input id="nama_guru" type="text" class="form-control" name="nama_guru"
                                    value="{{ old('nama_guru', $guru->nama_guru) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="jabatan">Jabatan<span class="text-danger">*</span></label>
                                <input id="jabatan" type="text" class="form-control" name="jabatan"
                                    value="{{ old('jabatan', $guru->jabatan) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select id="jenis_kelamin" type="text" class="form-control select2" name="jenis_kelamin"
                                    required>
                                    <option value="Laki-Laki"
                                        {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="Perempuan"
                                        {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                                <input id="tempat_lahir" type="text" class="form-control" name="tempat_lahir"
                                    value="{{ old('tempat_lahir', $guru->tempat_lahir) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input id="tanggal_lahir" type="date" class="form-control" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', $guru->tanggal_lahir) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="pendidikan">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                <select id="pendidikan" type="text" class="form-control select2" name="pendidikan"
                                    required>
                                    <option value="Diploma 3"
                                        {{ old('pendidikan', $guru->pendidikan) == 'Diploma 3' ? 'selected' : '' }}>
                                        Diploma 3</option>
                                    <option value="Strata 1"
                                        {{ old('pendidikan', $guru->pendidikan) == 'Strata 1' ? 'selected' : '' }}>
                                        Strata 1</option>
                                    <option value="Strata 2"
                                        {{ old('pendidikan', $guru->pendidikan) == 'Strata 2' ? 'selected' : '' }}>
                                        Strata 2</option>
                                    <option value="Strata 3"
                                        {{ old('pendidikan', $guru->pendidikan) == 'Strata 3' ? 'selected' : '' }}>
                                        Strata 3</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="mulai_bekerja">Mulai Bekerja <span class="text-danger">*</span></label>
                                <input id="mulai_bekerja" type="date" class="form-control" name="mulai_bekerja"
                                    value="{{ old('mulai_bekerja', $guru->mulai_bekerja) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="NIP">NIP<span class="text-danger">*</span></label>
                                <input id="NIP" type="text" class="form-control" name="NIP"
                                    value="{{ old('NIP', $guru->NIP) }}" required>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="pangkat">Pangkat / Golongan<span class="text-danger">*</span></label>
                                <input id="pangkat" type="text" class="form-control" name="pangkat"
                                    value="{{ old('pangkat', $guru->pangkat) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="NUPTK">NUPTK<span class="text-danger">*</span></label>
                                <input id="NUPTK" type="text" class="form-control" name="NUPTK"
                                    value="{{ old('NUPTK', $guru->NUPTK) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="sertifikasi">Sertifikasi </label>
                                <input id="sertifikasi" type="text" class="form-control" name="sertifikasi"
                                    value="{{ old('sertifikasi', $guru->sertifikasi) }}">
                            </div>

                            <div class="form-group">
                                <label for="no_telp">No Telp <span class="text-danger">*</span></label>
                                <input id="no_telp" type="text" class="form-control" name="no_telp"
                                    value="{{ old('no_telp', $guru->no_telp) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                <textarea id="alamat" class="form-control" name="alamat" required>{{ old('alamat', $guru->alamat) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="foto">Foto <span class="text-danger">* </span></label>
                                @if ($guru->foto)
                                    <a href="{{ asset($guru->foto) }}" data-lightbox="image-foto">
                                        <img src="{{ asset($guru->foto) }}" alt="Foto Guru" class="img-thumbnail"
                                            style="max-height: 200px;">
                                    </a>
                                @endif
                                <div class="custom-file mt-3">
                                    <input type="file" name="foto" class="custom-file-input" id="foto">
                                    <label class="custom-file-label">Choose File</label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Edit Profile Guru
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
    <script src="{{ asset('dist/js/lightbox.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const togglePasswordKonfirmasi = document.querySelector('#togglePasswordKonfirmasi');
            const password = document.querySelector('#password');
            const password_konfirmasi = document.querySelector('#password_confirmation');

            togglePassword.addEventListener('click', function(e) {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                this.querySelector('i').classList.toggle('fa-eye-slash');
                this.querySelector('i').classList.toggle('fa-eye');
            });

            togglePasswordKonfirmasi.addEventListener('click', function(e) {
                const type = password_konfirmasi.getAttribute('type') === 'password' ? 'text' : 'password';
                password_konfirmasi.setAttribute('type', type);

                this.querySelector('i').classList.toggle('fa-eye-slash');
                this.querySelector('i').classList.toggle('fa-eye');
            });
        });
    </script>
@endpush
