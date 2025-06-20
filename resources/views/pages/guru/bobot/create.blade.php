@extends('layouts.app')

@section('title', 'Tambah Bobot Penilaian')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Data Bobot Penilaian</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('guru.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('bobot.index') }}">Data Bobot Penilaian</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('bobot.index') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('bobot.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mapel_id">Pilih Mata Pelajaran <span class="text-danger">*</span></label>
                                <select id="mapel_id" class="form-control" name="mapel_id"></select>
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Bobot UH (%) <span class="text-danger">*</span></label>
                                        <input type="number" name="bobot_uh" class="form-control" required autofocus>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Bobot UTS (%) <span class="text-danger">*</span></label>
                                        <input type="number" name="bobot_uts" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Bobot UAS (%) <span class="text-danger">*</span></label>
                                        <input type="number" name="bobot_uas" class="form-control" required>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Tambah Bobot Penilaian
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
            $('#mapel_id').select2({
                placeholder: 'Pilih Mata Pelajaran',
                ajax: {
                    url: '{{ route('bobot.search.mapel') }}',
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
