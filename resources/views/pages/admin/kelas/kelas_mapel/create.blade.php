@extends('layouts.app')

@section('title', 'Tambah Data Kelas Mata Pelajaran')

@push('style')
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Data Kelas Mata Pelajaran</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('kelas.index') }}">Data Kelas</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('kelas.index') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('kelas.mapel.store', $kelas->id) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="mapel_id">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select id="mapel_id" class="form-control" name="mapel_id[]" multiple>
                            @foreach ($kelas->mataPelajarans as $mapel)
                                <option value="{{ $mapel->id }}" selected>
                                    {{ $mapel->nama_mapel }} - {{ $mapel->deskripsi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Tambah Data Mata Pelajaran
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
                    url: '{{ route('kelas.search.mapel') }}',
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
                                    text: item.nama_mapel + ' - ' + (item.deskripsi ?? '')
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
