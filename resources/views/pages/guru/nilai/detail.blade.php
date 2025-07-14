@extends('layouts.app')

@section('title', 'Detail Nilai Siswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Detail Nilai Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('guru.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('nilai.index') }}">Data Nilai Siswa</a></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('nilai.index') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <h5>
                    NISN:
                    <span class="text-primary">{{ $kartuStudi->first()->siswa->NISN ?? '-' }}</span>
                    | Nama Siswa :
                    <span class="text-primary">{{ $kartuStudi->first()->siswa->nama_siswa ?? '-' }}</span>
                </h5>

                <div class="row">
                    <div class="col-md-12">

                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Nilai UH</th>
                                    <th>Nilai UTS</th>
                                    <th>Nilai UAS</th>
                                    <th>Nilai Akhir</th>
                                    <th class="text-left">Bobot (UH | UTS | UAS)</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nilaiItems as $item)
                                    <tr>
                                        <td>{{ $item->mataPelajaran->nama_mapel }}</td>
                                        <td>{{ $item->nilai_uh }}</td>
                                        <td>{{ $item->nilai_uts }}</td>
                                        <td>{{ $item->nilai_uas }}</td>
                                        <td>{{ $item->nilai_akhir }}</td>
                                        <td>
                                            @if ($item->mataPelajaran && $item->mataPelajaran->bobotPenilaian)
                                                <small>
                                                    {{ $item->mataPelajaran->bobotPenilaian->bobot_uh ?? '0' }}% |
                                                    {{ $item->mataPelajaran->bobotPenilaian->bobot_uts ?? '0' }}% |
                                                    {{ $item->mataPelajaran->bobotPenilaian->bobot_uas ?? '0' }}%
                                                </small>
                                            @else
                                                <span class="text-danger small">Bobot belum diatur</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($nilaiItems->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">Nilai belum ditambahkan</td>
                                    </tr>
                                @endif
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
