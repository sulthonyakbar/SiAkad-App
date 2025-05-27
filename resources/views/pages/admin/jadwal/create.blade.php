@extends('layouts.app')

@section('title', 'Tambah Data Jadwal Pelajaran')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Data Jadwal Pelajaran</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('jadwal.index') }}">Data Jadwal Pelajaran</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('jadwal.index') }}" role="button"><i class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('jadwal.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kelas_id">Kelas<span class="text-danger">*</span></label>
                                <select id="kelas_id" class="form-control" name="kelas_id"></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mapel_id">Mata Pelajaran<span class="text-danger">*</span></label>
                                <select id="mapel_id" class="form-control" name="mapel_id"></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="guru_id">Pengajar<span class="text-danger">*</span></label>
                                <select id="guru_id" class="form-control" name="guru_id"></select>
                            </div>
                        </div>
                    </div>

                    <div id="jadwal-repeater">
                        <div class="jadwal-item border p-3 mb-3">
                            <div class="row justify-content-between align-items-center">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hari">Hari <span class="text-danger">*</span></label>
                                        <select id="hari" name="jadwals[0][hari]" class="form-control" required>
                                            <option value="">Pilih Hari</option>
                                            <option value="Senin">Senin</option>
                                            <option value="Selasa">Selasa</option>
                                            <option value="Rabu">Rabu</option>
                                            <option value="Kamis">Kamis</option>
                                            <option value="Jumat">Jumat</option>
                                            <option value="Sabtu">Sabtu</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="jam_mulai">Jam Mulai <span class="text-danger">*</span></label>
                                        <input type="time" name="jadwals[0][jam_mulai]" name="jam_mulai"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="jam_selesai">Jam Selesai <span class="text-danger">*</span></label>
                                        <input type="time" name="jadwals[0][jam_selesai]" name="jam_selesai"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-jadwal"><i
                                            class="fas fa-times"></i></button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="button" id="add-jadwal" class="btn btn-secondary mb-3"><i
                            class="fas fa-plus"></i></button>
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Tambah Data Jadwal Pelajaran
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
            $('#guru_id').select2({
                placeholder: 'Pilih Pengajar',
                ajax: {
                    url: '{{ route('search.guru') }}',
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

    <script>
        $(document).ready(function() {
            $('#kelas_id').select2({
                placeholder: 'Pilih Kelas',
                ajax: {
                    url: '{{ route('search.kelas') }}',
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
                                    text: item.nama_kelas + ' - Ruang ' + item.ruang
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#mapel_id').select2({
                placeholder: 'Pilih Mata Pelajaran',
                ajax: {
                    url: '{{ route('search.mapel') }}',
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
                                    text: item.nama_mapel + ' - ' + (item.deskripsi ?? ' ')
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
{{--
    <script>
        $(document).ready(function() {
            $('#hari').select2({
                placeholder: 'Pilih Hari',
            });
        });
    </script> --}}

    <script>
        let index = 1;

        $('#add-jadwal').click(function() {
            let clone = $('.jadwal-item').first().clone();
            clone.find('input, select').each(function() {
                const name = $(this).attr('name');
                const newName = name.replace(/\[\d+\]/, `[${index}]`);
                $(this).attr('name', newName).val('');
            });
            $('#jadwal-repeater').append(clone);
            index++;
        });

        $(document).on('click', '.remove-jadwal', function() {
            if ($('.jadwal-item').length > 1) {
                $(this).closest('.jadwal-item').remove();
            }
        });
    </script>
@endpush
