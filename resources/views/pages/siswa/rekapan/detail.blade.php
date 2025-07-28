@extends('layouts.app')

@section('title', 'Detail Rekapan Siswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Detail Rekapan Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('siswa.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('siswa.rekapan.index') }}">Data Rekapan Siswa</a></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('siswa.rekapan.index') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">

                        <h6 class="mt-4">Nilai Akademik</h6>
                        <table class="table table-striped mt-2">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>UH</th>
                                    <th>UTS</th>
                                    <th>UAS</th>
                                    <th>Nilai Akhir</th>
                                    <th class="text-left">Bobot (UH | UTS | UAS)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($nilaiItems as $item)
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
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Nilai belum ditambahkan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <h6 class="mt-4">Rekapan Presensi</h6>
                        <table class="table table-bordered mt-2">
                            <thead>
                                <tr>
                                    <th>Hadir</th>
                                    <th>Izin</th>
                                    <th>Sakit</th>
                                    <th>Alpa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($rekapPresensi)
                                    <tr>
                                        <td>{{ $rekapPresensi->Hadir }}</td>
                                        <td>{{ $rekapPresensi->Izin }}</td>
                                        <td>{{ $rekapPresensi->Sakit }}</td>
                                        <td>{{ $rekapPresensi->Alpa }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">Data presensi belum tersedia</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <h6 class="mt-4">Pesan / Keterangan dari Wali Kelas</h6>
                        <div class="form-group">
                            <textarea class="form-control" style="height: 100px;" readonly>{{ !empty($rekapan) && !empty($rekapan->keterangan) ? $rekapan->keterangan : 'Belum ada pesan / keterangan dari wali kelas.' }}</textarea>
                        </div>

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
