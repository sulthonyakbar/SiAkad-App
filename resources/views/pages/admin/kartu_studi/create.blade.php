@extends('layouts.app')

@section('title', 'Tambah Data Kartu Studi')

@push('style')
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Data Kartu Studi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('kartu.studi.index') }}">Data Kartu Studi</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('kartu.studi.index') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('kartu.studi.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kelas_id">Kelas <span class="text-danger">*</span></label>
                                <select id="kelas_id" class="form-control" name="kelas_id"></select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="siswa_id">Siswa <span class="text-danger">*</span></label>
                                <select id="siswa_id" class="form-control" name="siswa_id[]" multiple></select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Tambah Data Kartu Studi
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
            $('#kelas_id').select2({
                placeholder: 'Pilih Kelas',
                ajax: {
                    url: '{{ route('ks.search.kelas') }}',
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
            $('#siswa_id').select2({
                placeholder: 'Pilih Siswa',
                ajax: {
                    url: '{{ route('ks.search.siswa') }}',
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
