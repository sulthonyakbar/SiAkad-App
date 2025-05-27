@extends('layouts.app')

@section('title', 'Tambah Data Nilai Siswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Data Nilai Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('guru.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('presensi.index') }}">Data Presensi Siswa</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('presensi.index') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">

                <form action="{{ route('nilai.store') }}" method="POST">
                    @csrf

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Nama Mapel</th>
                                <th>UH</th>
                                <th>UTS</th>
                                <th>UAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kartuStudis as $ks)
                                @foreach ($mapels as $mapel)
                                    <tr>
                                        <td>{{ $ks->siswa->nama }}</td>
                                        <td>{{ $mapel->nama_mapel }}</td>
                                        <td>
                                            <input type="number" name="nilai[{{ $ks->id }}][{{ $mapel->id }}][uh]"
                                                value="{{ old("nilai.{$ks->id}.{$mapel->id}.uh") }}" min="0"
                                                max="100" class="form-control">
                                        </td>
                                        <td>
                                            <input type="number"
                                                name="nilai[{{ $ks->id }}][{{ $mapel->id }}][uts]"
                                                value="{{ old("nilai.{$ks->id}.{$mapel->id}.uts") }}" min="0"
                                                max="100" class="form-control">
                                        </td>
                                        <td>
                                            <input type="number"
                                                name="nilai[{{ $ks->id }}][{{ $mapel->id }}][uas]"
                                                value="{{ old("nilai.{$ks->id}.{$mapel->id}.uas") }}" min="0"
                                                max="100" class="form-control">
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Tambah Data Nilai Siswa
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
