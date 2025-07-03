@extends('layouts.app')

@section('title', $pengumuman->judul)

@push('style')
    <!-- CSS Libraries -->
    <link href="{{ asset('dist/css/lightbox.css') }}" rel="stylesheet">
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pengumuman->judul }}</h1>
            <div class="section-header-breadcrumb">
                {{-- <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('pengumuman.index') }}">Data Pengumuman</a></div> --}}
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ url()->previous() }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>
            <div class="card-body">

                <p class="text-muted mb-3">
                    <i class="fas fa-user"></i> Oleh: <strong>{{ $pengumuman->guru->nama_guru ?? 'Admin' }}</strong> 
                    |
                    <i class="fas fa-calendar-alt"></i> {{ $pengumuman->created_at->translatedFormat('d F Y, H:i') }}
                </p>

                @if ($pengumuman->gambar)
                    <div class="mb-4 text-center">
                        <a href="{{ asset($pengumuman->gambar) }}" data-lightbox="pengumuman-image">
                            <img src="{{ asset($pengumuman->gambar) }}" alt="Gambar Pengumuman"
                                class="img-fluid rounded" style="max-height: 300px;">
                        </a>
                    </div>
                @endif

                <div class="isi-pengumuman">
                    {!! $pengumuman->isi !!}
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
