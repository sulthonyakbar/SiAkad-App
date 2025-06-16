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
                    <span class="text-primary">{{ $presensi->first()->kelas->nama_kelas ?? '-' }}</span>
                    | Ruang
                    <span class="text-primary">{{ $presensi->first()->kelas->ruang ?? '-' }}</span>
                    | Tanggal Presensi:
                    <span class="text-primary">
                        {{ \Carbon\Carbon::parse($presensi->first()->tanggal)->translatedFormat('d F Y') }}
                    </span>
                </h5>
                @foreach ($presensi as $data)
                    <div class="mt-4">
                        <table class="table table-bordered mt-2">
                            <thead>
                                <tr>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->detailPresensi as $detail)
                                    <tr>
                                        <td>{{ $detail->siswa->NISN ?? '-' }}</td>
                                        <td>{{ $detail->siswa->nama_siswa ?? '-' }}</td>
                                        <td>{{ ucfirst($detail->status) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
    </section>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/components-table.js') }}"></script>
@endpush
