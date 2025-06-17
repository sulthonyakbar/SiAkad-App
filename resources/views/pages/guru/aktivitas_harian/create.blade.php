@extends('layouts.app')

@section('title', 'Tambah Data Aktivitas Harian Siswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Data Aktivitas Harian Siswa</h1>
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
                <form method="POST" action="{{ route('aktivitas.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="siswa_id">Pilih Siswa <span class="text-danger">*</span></label>
                                <select id="siswa_id" class="form-control" name="siswa_id" required></select>
                            </div>

                            <div class="form-group">
                                <label for="kegiatan">Kegiatan<span class="text-danger">*</span></label>
                                <select id="kegiatan" class="form-control select2" name="kegiatan" required>
                                    <option value="">Pilih Kegiatan</option>
                                    <option value="Belajar Membaca">Belajar Membaca</option>
                                    <option value="Belajar Menulis">Belajar Menulis</option>
                                    <option value="Mengerjakan Soal Matematika">Mengerjakan Soal Matematika</option>
                                    <option value="Mengenal Huruf dan Angka">Mengenal Huruf dan Angka</option>
                                    <option value="Mengenal Warna dan Bentuk">Mengenal Warna dan Bentuk</option>
                                    <option value="Menyusun Puzzle">Menyusun Puzzle</option>
                                    <option value="Melatih Konsentrasi">Melatih Konsentrasi</option>
                                    <option value="Bermain Edukatif">Bermain Edukatif</option>
                                    <option value="Membaca Buku Cerita">Membaca Buku Cerita</option>
                                    <option value="Menari dan Bernyanyi">Menari dan Bernyanyi</option>
                                    <option value="Menggambar dan Mewarnai">Menggambar dan Mewarnai</option>
                                    <option value="Olahraga Ringan">Olahraga Ringan</option>
                                    <option value="Latihan Motorik Halus">Latihan Motorik Halus</option>
                                    <option value="Latihan Motorik Kasar">Latihan Motorik Kasar</option>
                                    <option value="Belajar Bersosialisasi">Belajar Bersosialisasi</option>
                                    <option value="Melatih Kemandirian">Melatih Kemandirian</option>
                                    <option value="Makan Bersama">Makan Bersama</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="kendala">Kendala<span class="text-danger">*</span></label>
                                <select id="kendala" class="form-control select2" name="kendala" required>
                                    <option value="">Pilih Kendala</option>
                                    <option value="Kurang Fokus">Kurang Fokus</option>
                                    <option value="Sulit Menghafal">Sulit Menghafal</option>
                                    <option value="Kesulitan Menulis">Kesulitan Menulis</option>
                                    <option value="Sulit Mengenali Huruf dan Angka">Sulit Mengenali Huruf dan Angka</option>
                                    <option value="Kurang Percaya Diri">Kurang Percaya Diri</option>
                                    <option value="Kesulitan Bersosialisasi">Kesulitan Bersosialisasi</option>
                                    <option value="Mudah Lelah atau Mengantuk">Mudah Lelah atau Mengantuk</option>
                                    <option value="Kurang Koordinasi Motorik">Kurang Koordinasi Motorik</option>
                                    <option value="Kesulitan Mengikuti Arahan">Kesulitan Mengikuti Arahan</option>
                                    <option value="Sulit Berkomunikasi dengan Teman">Sulit Berkomunikasi dengan Teman
                                    </option>
                                    <option value="Kurang Minat dalam Kegiatan">Kurang Minat dalam Kegiatan</option>
                                    <option value="Mudah Terdistraksi">Mudah Terdistraksi</option>
                                    <option value="Kesulitan Mengontrol Emosi">Kesulitan Mengontrol Emosi</option>
                                    <option value="Tidak Ada Kendala">Tidak Ada Kendala</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea id="deskripsi" class="form-control" name="deskripsi"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="foto">Foto</label>
                                <input id="foto" type="file" class="form-control" name="foto">
                            </div>

                        </div>

                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Tambah Data Aktivitas Harian Siswa
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
                                    text: item.NISN + ' - ' + item.nama_siswa
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
