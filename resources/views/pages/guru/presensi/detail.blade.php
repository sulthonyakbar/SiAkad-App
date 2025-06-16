@extends('layouts.app')

@section('title', 'Detail Presensi Siswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Detail Presensi Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('guru.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('presensi.index') }}">Data Presensi Siswa</a></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('presensi.index') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <h5>
                    Kelas:
                    <span class="text-primary">{{ $kartuStudi->first()->kelas->nama_kelas ?? '-' }}</span>
                    | Ruang
                    <span class="text-primary">{{ $kartuStudi->first()->kelas->ruang ?? '-' }}</span>
                </h5>
                <div class="row">
                    <div class="col-md-12">

                        <input type="hidden" name="kelas_id" value="{{ $kartuStudi->first()->kelas_id ?? '' }}" required>

                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Status Presensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kartuStudi as $data)
                                    <tr>
                                        <td>{{ $data->siswa->NISN }}</td>
                                        <td>{{ $data->siswa->nama_siswa }}</td>
                                        <td>{{ ucfirst($data->presensi->status ?? '-') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
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
