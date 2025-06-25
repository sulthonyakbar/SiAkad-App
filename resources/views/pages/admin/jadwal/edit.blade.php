@extends('layouts.app')

@section('title', 'Edit Data Jadwal Pelajaran')

@push('style')
    <!-- CSS Libraries -->
    <link href="{{ asset('dist/css/lightbox.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit Data Jadwal Pelajaran</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('jadwal.index') }}">Data Jadwal Pelajaran</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('jadwal.index') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('jadwal.update', $jadwal->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $jadwal->id }}" required>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="kelas_id">Kelas <span class="text-danger">*</span></label>
                                <select id="kelas_id" class="form-control" name="kelas_id">
                                    @if ($jadwal->kelas)
                                        <option value="{{ $jadwal->kelas->id }}" selected>
                                            {{ $jadwal->kelas->nama_kelas }} - Ruang {{ $jadwal->kelas->ruang }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mapel_id">Mata Pelajaran <span class="text-danger">*</span></label>
                                <select id="mapel_id" class="form-control" name="mapel_id">
                                    @if ($jadwal->mapel)
                                        <option value="{{ $jadwal->mapel->id }}" selected>
                                            {{ $jadwal->mapel->nama_mapel }} - {{ $jadwal->mapel->deskripsi }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="guru_id">Pengajar <span class="text-danger">*</span></label>
                                <select id="guru_id" class="form-control" name="guru_id">
                                    @if ($jadwal->gurus)
                                        <option value="{{ $jadwal->gurus->id }}" selected>
                                            {{ $jadwal->gurus->NIP }} - {{ $jadwal->gurus->nama_guru }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hari">Hari <span class="text-danger">*</span></label>
                                <select id="hari" name="hari" class="form-control" required>
                                    <option value="">Pilih Hari</option>
                                    <option value="Senin" {{ $jadwal->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                                    <option value="Selasa" {{ $jadwal->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                    <option value="Rabu" {{ $jadwal->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                    <option value="Kamis" {{ $jadwal->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                    <option value="Jumat" {{ $jadwal->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                    <option value="Sabtu" {{ $jadwal->hari == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="jam_mulai">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="jam_mulai" class="form-control"
                                    value="{{ old('jam_mulai', $jadwal->jam_mulai) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="jam_selesai">Jam Selesai
                                    <span class="text-danger">*</span></label>
                                <input type="time" name="jam_selesai" class="form-control"
                                    value="{{ old('jam_selesai', $jadwal->jam_selesai) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Edit Data Jadwal Pelajaran
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
@endpush
