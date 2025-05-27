@extends('layouts.app')

@section('title', 'Edit Data Aktivitas Harian Siswa')

@push('style')
    <!-- CSS Libraries -->
    <link href="{{ asset('dist/css/lightbox.css') }}" rel="stylesheet">
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit Data Aktivitas Harian Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('guru.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('aktivitas.index') }}">Data Aktivitas Siswa</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('aktivitas.index') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('aktivitas.update', $aktivitas->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $aktivitas->id }}" required>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="siswa_id">Pilih Siswa <span class="text-danger">*</span></label>
                                <select id="siswa_id" class="form-control" name="siswa_id">
                                    @if ($aktivitas->siswa)
                                        <option value="{{ $aktivitas->siswa->id }}" selected>
                                            {{ $aktivitas->siswa->NISN }} - {{ $aktivitas->siswa->nama_siswa }}
                                        </option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="kegiatan">Kegiatan<span class="text-danger">*</span></label>
                                <select id="kegiatan" class="form-control select2" name="kegiatan" required>
                                    <option value="Belajar Membaca"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Belajar Membaca' ? 'selected' : '' }}>
                                        Belajar Membaca</option>
                                    <option value="Belajar Menulis"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Belajar Menulis' ? 'selected' : '' }}>
                                        Belajar Menulis</option>
                                    <option value="Mengerjakan Soal Matematika"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Mengerjakan Soal Matematika' ? 'selected' : '' }}>
                                        Mengerjakan Soal Matematika</option>
                                    <option value="Mengenal Huruf dan Angka"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Mengenal Huruf dan Angka' ? 'selected' : '' }}>
                                        Mengenal Huruf dan Angka</option>
                                    <option value="Mengenal Warna dan Bentuk"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Mengenal Warna dan Bentuk' ? 'selected' : '' }}>
                                        Mengenal Warna dan Bentuk</option>
                                    <option value="Menyusun Puzzle"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Menyusun Puzzle' ? 'selected' : '' }}>
                                        Menyusun Puzzle</option>
                                    <option value="Melatih Konsentrasi"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Melatih Konsentrasi' ? 'selected' : '' }}>
                                        Melatih Konsentrasi</option>
                                    <option value="Bermain Edukatif"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Bermain Edukatif' ? 'selected' : '' }}>
                                        Bermain Edukatif</option>
                                    <option value="Membaca Buku Cerita"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Membaca Buku Cerita' ? 'selected' : '' }}>
                                        Membaca Buku Cerita</option>
                                    <option value="Menari dan Bernyanyi"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Menari dan Bernyanyi' ? 'selected' : '' }}>
                                        Menari dan Bernyanyi</option>
                                    <option value="Menggambar dan Mewarnai"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Menggambar dan Mewarnai' ? 'selected' : '' }}>
                                        Menggambar dan Mewarnai</option>
                                    <option value="Olahraga Ringan"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Olahraga Ringan' ? 'selected' : '' }}>
                                        Olahraga Ringan</option>
                                    <option value="Latihan Motorik Halus"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Latihan Motorik Halus' ? 'selected' : '' }}>
                                        Latihan Motorik Halus</option>
                                    <option value="Latihan Motorik Kasar"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Latihan Motorik Kasar' ? 'selected' : '' }}>
                                        Latihan Motorik Kasar</option>
                                    <option value="Belajar Bersosialisasi"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Belajar Bersosialisasi' ? 'selected' : '' }}>
                                        Belajar Bersosialisasi</option>
                                    <option value="Melatih Kemandirian"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Melatih Kemandirian' ? 'selected' : '' }}>
                                        Melatih Kemandirian</option>
                                    <option value="Makan Bersama"
                                        {{ old('kegiatan', $aktivitas->kegiatan) == 'Makan Bersama' ? 'selected' : '' }}>
                                        Makan Bersama</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="kendala">Kendala<span class="text-danger">*</span></label>
                                <select id="kendala" class="form-control select2" name="kendala" required>
                                    <option value="">Pilih Kendala</option>
                                    <option value="Kurang Fokus"
                                        {{ old('kendala', $aktivitas->kendala) == 'Kurang Fokus' ? 'selected' : '' }}>
                                        Kurang Fokus</option>
                                    <option value="Sulit Menghafal"
                                        {{ old('kendala', $aktivitas->kendala) == 'Sulit Menghafal' ? 'selected' : '' }}>
                                        Sulit Menghafal</option>
                                    <option value="Kesulitan Menulis"
                                        {{ old('kendala', $aktivitas->kendala) == 'Kesulitan Menulis' ? 'selected' : '' }}>
                                        Kesulitan Menulis</option>
                                    <option value="Sulit Mengenali Huruf dan Angka"
                                        {{ old('kendala', $aktivitas->kendala) == 'Sulit Mengenali Huruf dan Angka' ? 'selected' : '' }}>
                                        Sulit Mengenali Huruf dan Angka</option>
                                    <option value="Kurang Percaya Diri"
                                        {{ old('kendala', $aktivitas->kendala) == 'Kurang Percaya Diri' ? 'selected' : '' }}>
                                        Kurang Percaya Diri</option>
                                    <option value="Kesulitan Bersosialisasi"
                                        {{ old('kendala', $aktivitas->kendala) == 'Kesulitan Bersosialisasi' ? 'selected' : '' }}>
                                        Kesulitan Bersosialisasi</option>
                                    <option value="Mudah Lelah atau Mengantuk"
                                        {{ old('kendala', $aktivitas->kendala) == 'Mudah Lelah atau Mengantuk' ? 'selected' : '' }}>
                                        Mudah Lelah atau Mengantuk</option>
                                    <option value="Kurang Koordinasi Motorik"
                                        {{ old('kendala', $aktivitas->kendala) == 'Kurang Koordinasi Motorik' ? 'selected' : '' }}>
                                        Kurang Koordinasi Motorik</option>
                                    <option value="Kesulitan Mengikuti Arahan"
                                        {{ old('kendala', $aktivitas->kendala) == 'Kesulitan Mengikuti Arahan' ? 'selected' : '' }}>
                                        Kesulitan Mengikuti Arahan</option>
                                    <option value="Sulit Berkomunikasi dengan Teman"
                                        {{ old('kendala', $aktivitas->kendala) == 'Sulit Berkomunikasi dengan Teman' ? 'selected' : '' }}>
                                        Sulit Berkomunikasi dengan Teman</option>
                                    <option value="Kurang Minat dalam Kegiatan"
                                        {{ old('kendala', $aktivitas->kendala) == 'Kurang Minat dalam Kegiatan' ? 'selected' : '' }}>
                                        Kurang Minat dalam Kegiatan</option>
                                    <option value="Mudah Terdistraksi"
                                        {{ old('kendala', $aktivitas->kendala) == 'Mudah Terdistraksi' ? 'selected' : '' }}>
                                        Mudah Terdistraksi</option>
                                    <option value="Kesulitan Mengontrol Emosi"
                                        {{ old('kendala', $aktivitas->kendala) == 'Kesulitan Mengontrol Emosi' ? 'selected' : '' }}>
                                        Kesulitan Mengontrol Emosi</option>
                                    <option value="Tidak Ada Kendala"
                                        {{ old('kendala', $aktivitas->kendala) == 'Tidak Ada Kendala' ? 'selected' : '' }}>
                                        Tidak Ada Kendala</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea id="deskripsi" class="form-control" name="deskripsi">{{ old('deskripsi', $aktivitas->deskripsi) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="foto">Foto</label>
                                @if ($aktivitas->foto)
                                    <a href="{{ asset($aktivitas->foto) }}" data-lightbox="image-foto">
                                        <img src="{{ asset($aktivitas->foto) }}" alt="Foto Aktivitas Siswa"
                                            class="img-thumbnail" style="max-height: 200px;">
                                    </a>
                                @endif
                                <input id="foto" type="file" class="form-control mt-3" name="foto">
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Edit Data Aktivitas Harian Siswa
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#siswa_id').select2({
                placeholder: 'Pilih Siswa',
                ajax: {
                    url: '{{ route('aktivitas.search.siswa') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.NIP + ' - ' + item.nama_guru
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endpush
