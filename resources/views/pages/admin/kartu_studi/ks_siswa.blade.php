@extends('layouts.app')

@section('title', 'Data Kartu Studi Siswa - ' . $siswa->nama_siswa)

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Data Kartu Studi Siswa - {{ $siswa->nama_siswa . ' - ' . $siswa->NISN }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="/admin/kartu-studi">Data Kartu Studi</a></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <a class="btn btn-primary" href="/admin/kartu-studi" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                @foreach ($kartuStudi as $item)
                    <div class="mb-5">
                        <h5 class="text-primary">Kelas: {{ $item->kelas->nama_kelas ?? '-' }} | Ruang:
                            {{ $item->kelas->ruang ?? '-' }}</h5>

                        <h6 class="mt-4">Daftar Mata Pelajaran & Nilai:</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Mata Pelajaran</th>
                                    <th>UH</th>
                                    <th>UTS</th>
                                    <th>UAS</th>
                                    <th>Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($item->kelas->jadwalPelajaran as $jadwal)
                                    <tr>
                                        <td>{{ $jadwal->mapel->nama_mapel ?? '-' }}</td>
                                        <td>{{ $item->nilai->nilai_uh ?? '-' }}</td>
                                        <td>{{ $item->nilai->nilai_uts ?? '-' }}</td>
                                        <td>{{ $item->nilai->nilai_uas ?? '-' }}</td>
                                        <td>{{ $item->nilai->nilai_akhir ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada jadwal/mapel</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endforeach
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
