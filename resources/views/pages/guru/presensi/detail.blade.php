@extends('layouts.app')

@section('title', 'Detail Data Aktivitas Harian Siswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Detail Data Aktivitas Harian Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/guru/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="/guru/aktivitas">Data Aktivitas Harian Siswa</a></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <a class="btn btn-primary" href="/guru/aktivitas" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <h5>Siswa: <span class="text-primary">{{ $aktivitas->siswa->nama_siswa }} </span> - NISN {{ $aktivitas->siswa->NISN }}</h5>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table mt-3">
                            <tbody>
                                <tr>
                                    <td>Kegiatan</td>
                                    <td>:</td>
                                    <td>{{ $aktivitas->kegiatan }}</td>
                                </tr>
                                <tr>
                                    <td>Kendala</td>
                                    <td>:</td>
                                    <td>{{ $aktivitas->kendala }}</td>
                                </tr>
                                <tr>
                                    <td>Deskripsi</td>
                                    <td>:</td>
                                    <td>{{ $aktivitas->deskripsi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Foto</td>
                                    <td>:</td>
                                    <td>
                                        @if ($aktivitas->foto)
                                            <a href="{{ asset($aktivitas->foto) }}" data-lightbox="image-foto">
                                                <img src="{{ asset($aktivitas->foto) }}" alt="Foto Aktivitas Siswa"
                                                    style="width: 150px; height: auto;">
                                            </a>
                                        @else
                                            <p>Tidak ada foto Aktivitas Siswa</p>
                                        @endif
                                    </td>                                </tr>
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
