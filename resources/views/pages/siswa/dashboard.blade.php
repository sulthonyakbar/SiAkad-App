@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Presensi Hari Ini</h4>
                        </div>
                        <div class="card-body">
                            @php
                                $badgeColor = match ($statusPresensi) {
                                    'Hadir' => 'success',
                                    'Sakit' => 'primary',
                                    'Izin' => 'warning',
                                    'Alpa' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp

                            <h4><span class="badge badge-{{ $badgeColor }}">{{ $statusPresensi }}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Kelas</h4>
                        </div>
                        <div class="card-body">
                            {{ $kelas ?? 'Belum Ditentukan' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Ruang</h4>
                        </div>
                        <div class="card-body">
                            {{ $ruang ?? 'Belum Ditentukan' }}
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-sack-dollar"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Pinjaman</h4>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

        <h2 class="section-title">Jadwal Minggu Ini</h2>
        <div class="card">
            <div class="card-body">
                <div id="siswaCalendar"></div>
            </div>
        </div>

        <h2 class="section-title">Pengumuman</h2>
        <div class="row">
            @forelse ($pengumuman as $item)
                <div class="col-12 col-md-3 col-lg-3">
                    <article class="article article-style-c">
                        <div class="article-header">
                            <div class="article-image" data-background="{{ asset($item->gambar) }}">
                            </div>
                        </div>
                        <div class="article-details">
                            <div class="article-category">
                                <a href="#">{{ $item->kategori->nama_kategori ?? '-' }}</a>
                                <div class="bullet"></div>
                                <a href="#">{{ $item->created_at->diffForHumans() }}</a>
                            </div>
                            <div class="article-title">
                                <h2><a href="#">{{ $item->judul }}</a></h2>
                            </div>
                            <p class="text-wrap">
                                {{ Str::limit(preg_replace('/\s+/', ' ', strip_tags($item->isi)), 100, '...') }}
                            </p>
                            <div class="article-user">
                                <img alt="image" src="{{ asset($item->guru->foto) }}">
                                <div class="article-user-details">
                                    <div class="user-detail-name">
                                        <a href="#">{{ $item->guru->nama_guru ?? 'Admin' }}</a>
                                    </div>
                                    <div class="text-job">Admin</div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted">Belum ada pengumuman.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('siswaCalendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                firstDay: 1,
                slotLabelFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false,
                },
                height: 500,
                slotMinTime: "06:00:00",
                slotMaxTime: "18:00:00",
                locale: 'id',
                nowIndicator: true,
                slotEventOverlap: false,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek'
                },
                events: @json($events),
            });

            calendar.render();
        });
    </script>
@endpush
