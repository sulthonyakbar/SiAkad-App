@extends('layouts.app')

@section('title', 'Edit Data Kelas')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit Data Kelas</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('kelas.index') }}">Data Kelas</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('kelas.index') }}" role="button"><i class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('kelas.update', $kelas->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $kelas->id }}" required>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_kelas">Nama Kelas<span class="text-danger">*</span></label>
                                <input id="nama_kelas" type="text" class="form-control" name="nama_kelas"
                                    value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="ruang">Ruang<span class="text-danger">*</span></label>
                                <input id="ruang" type="text" class="form-control" name="ruang"
                                    value="{{ old('ruang', $kelas->ruang) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="guru_id">Wali Kelas <span class="text-danger">*</span></label>
                                <select id="guru_id" class="form-control" name="guru_id">
                                    @if ($kelas->guru)
                                        <option value="{{ $kelas->guru->id }}" selected>
                                            {{ $kelas->guru->NIP }} - {{ $kelas->guru->nama_guru }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Edit Data Kelas
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
                placeholder: 'Pilih Wali Kelas',
                ajax: {
                    url: '{{ route('search.guru') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
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
