@extends('layouts.app')

@section('title', 'Detail Data Pengumuman')

@push('style')
    <!-- CSS Libraries -->
    <link href="{{ asset('dist/css/lightbox.css') }}" rel="stylesheet">
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Detail Data Pengumuman</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('pengumuman.index') }}">Data Pengumuman</a></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('pengumuman.index') }}" role="button"><i class="fa-solid fa-chevron-left"></i></a>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <table class="table mt-3">
                            <tbody>
                                <tr>
                                    <td>Judul</td>
                                    <td>:</td>
                                    <td>{{ $pengumuman->judul }}</td>
                                </tr>
                                <tr>
                                    <td>Isi</td>
                                    <td>:</td>
                                    <td>{{!! $pengumuman->isi !!}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table mt-3">
                            <tbody>

                                <tr>
                                    <td>Penulis</td>
                                    <td>:</td>
                                    <td>{{ $pengumuman->guru->nama_guru }}</td>
                                </tr>

                                <tr>
                                    <td>Tanggal Dibuat</td>
                                    <td>:</td>
                                    <td>{{ date('d F Y', strtotime($pengumuman->created_at)) }}</td>
                                </tr>

                                <tr>
                                    <td>Kategori</td>
                                    <td>:</td>
                                    <td>{{ $pengumuman->kategori->nama_kategori }}</td>
                                </tr>

                                <tr>
                                    <td>Gambar</td>
                                    <td>:</td>
                                    <td>
                                        @if ($pengumuman->gambar)
                                            <a href="{{ asset($pengumuman->gambar) }}" data-lightbox="image-foto">
                                                <img src="{{ asset($pengumuman->gambar) }}" alt="Foto Pengumuman"
                                                    style="width: 150px; height: auto;">
                                            </a>
                                        @else
                                            <p>Tidak ada foto Pengumuman</p>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
    <script src="{{ asset('dist/js/lightbox.js') }}"></script>

@endpush
