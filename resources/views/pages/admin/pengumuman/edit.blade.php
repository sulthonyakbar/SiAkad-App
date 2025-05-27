@extends('layouts.app')

@section('title', 'Edit Data Pengumuman')

@push('style')
    <!-- CSS Libraries -->
    <link href="{{ asset('dist/css/lightbox.css') }}" rel="stylesheet">
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit Data Pengumuman</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('pengumuman.index') }}">Data Pengumuman</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('pengumuman.index') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('pengumuman.update', $pengumuman->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $pengumuman->id }}" required>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="judul">Judul<span class="text-danger">*</span></label>
                                <input id="judul" type="text" class="form-control" name="judul"
                                    value="{{ old('judul', $pengumuman->judul) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="isi">Isi <span class="text-danger">*</span></label>
                                <textarea id="isi" class="form-control" name="isi" required>{{ old('isi', $pengumuman->isi) }}</textarea>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="foto">Gambar <span class="text-danger">*</span></label>
                                @if ($pengumuman->gambar)
                                    <a href="{{ asset($pengumuman->gambar) }}" data-lightbox="image-foto">
                                        <img src="{{ asset($pengumuman->gambar) }}" alt="Foto Pengumuman"
                                            class="img-thumbnail" style="max-height: 200px;">
                                    </a>
                                @endif
                                <input type="file" class="form-control mt-3" id="gambar" name="gambar">
                            </div>

                            <div class="form-group">
                                <label for="kategori_id">Kategori <span class="text-danger">*</span></label>
                                <select id="kategori_id" class="form-control" name="kategori_id">
                                    @if ($pengumuman->kategori)
                                        <option value="{{ $pengumuman->kategori->id }}" selected>
                                            {{ $pengumuman->kategori->nama_kategori }}
                                        </option>
                                    @endif
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Edit Data Pengumuman
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
            $('#kategori_id').select2({
                placeholder: 'Pilih Kategori',
                ajax: {
                    url: '{{ route('search.kategori') }}',
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
                                    text: item.nama_kategori
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>

    <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#isi'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList']
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
