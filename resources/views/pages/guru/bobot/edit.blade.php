@extends('layouts.app')

@section('title', 'Edit Bobot Penilaian')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit Data Bobot Penilaian</h1>
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
                <form method="POST" action="{{ route('bobot.update', $bobot->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mata Pelajaran</label>
                                <input type="text" class="form-control" value="{{ $bobot->mapel->nama_mapel ?? '-' }}"
                                    disabled>
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Bobot UH (%) <span class="text-danger">*</span></label>
                                        <input type="number" name="bobot_uh" class="form-control"
                                            value="{{ old('bobot_uh', $bobot->bobot_uh) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Bobot UTS (%) <span class="text-danger">*</span></label>
                                        <input type="number" name="bobot_uts" class="form-control"
                                            value="{{ old('bobot_uts', $bobot->bobot_uts) }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Bobot UAS (%) <span class="text-danger">*</span></label>
                                        <input type="number" name="bobot_uas" class="form-control"
                                            value="{{ old('bobot_uas', $bobot->bobot_uas) }}">
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Edit Bobot Penilaian
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
@endpush
