@extends('layouts.app')

@section('title', 'Edit Data Nilai Siswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit Data Nilai Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('guru.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('nilai.index') }}">Data Nilai Siswa</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('nilai.index') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">

                <form action="{{ route('nilai.update', $kartuStudi->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Mata Pelajaran</th>
                                <th>UH</th>
                                <th>UTS</th>
                                <th>UAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mapels as $mapel)
                                <tr>
                                    <td>{{ $mapel->nama_mapel }}</td>
                                    <td>
                                         <input type="number" name="nilai[{{ $mapel->id }}][uh]"
                                                class="form-control" min="0" max="100"
                                                value="{{ old('nilai.'.$mapel->id.'.uh', $nilaiSiswa[$mapel->id]->nilai_uh ?? '') }}"
                                                required>
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $mapel->id }}][uts]"
                                            class="form-control" min="0" max="100"
                                            value="{{ old('nilai.'.$mapel->id.'.uts', $nilaiSiswa[$mapel->id]->nilai_uts ?? '') }}"
                                            required>
                                    </td>
                                    <td>
                                        <input type="number" name="nilai[{{ $mapel->id }}][uas]"
                                            class="form-control" min="0" max="100"
                                            value="{{ old('nilai.'.$mapel->id.'.uas', $nilaiSiswa[$mapel->id]->nilai_uas ?? '') }}"
                                            required>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Edit Data Nilai Siswa
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
@endpush
