@extends('layouts.app')

@section('title', 'Edit Data Feedback Aktivitas Harian Siswa')

@push('style')
    <!-- CSS Libraries -->
    <link href="{{ asset('dist/css/lightbox.css') }}" rel="stylesheet">
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit Data Feedback Aktivitas Harian Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('guru.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('feedback.index') }}">Data Feedback Aktivitas Harian Siswa</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('feedback.index') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('feedback.update', $aktivitas->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $aktivitas->id }}" required>

                     <div class="row">
                        <div class="col-md-6">

                            <input type="hidden" name="aktivitas_id" value="{{ $aktivitas->id }}">

                            <div class="form-group">
                                <label for="kegiatan">Kegiatan</label>
                                <input id="kegiatan" type="text" class="form-control" name="kegiatan"
                                    value="{{ old('kegiatan', $aktivitas->kegiatan ?? '') }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="kendala">Kendala</label>
                                <textarea id="kendala" class="form-control" name="kendala" disabled>{{ old('kendala', $aktivitas->kendala ?? '') }}</textarea>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea id="deskripsi" class="form-control" name="deskripsi" disabled>{{ old('deskripsi', $aktivitas->deskripsi ?? '') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="foto">Foto</label>
                                @if ($aktivitas->foto)
                                    <a href="{{ asset($aktivitas->foto) }}" data-lightbox="image-foto">
                                        <img src="{{ asset($aktivitas->foto) }}" alt="Foto Aktivitas Siswa"
                                            class="img-thumbnail" style="max-height: 200px;">
                                    </a>
                                @endif
                            </div>

                        </div>

                    </div>

                    <h5 class="text-primary mb-3">Berikan Feedback</h5>

                    <div class="form-group">
                        <label for="pesan">Pesan Feedback </label>
                        <textarea id="pesan" class="form-control" style="height: 150px;" name="pesan">{{ old('pesan', $aktivitas->feedback->pesan ?? '') }} </textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Edit Data Feedback Aktivitas Harian Siswa
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
