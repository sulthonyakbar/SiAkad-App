@extends('layouts.app')

@section('title', 'Detail Data Pegawai')

@push('style')
    <!-- CSS Libraries -->
    <link href="{{ asset('dist/css/lightbox.css') }}" rel="stylesheet">
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Detail Data Pegawai</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('pegawai.guru.index') }}">Data Guru</a></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ url()->previous() }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>
            <div class="card-body">
                <h5 class="text-primary">Informasi Guru</h5>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table mt-3">
                            <tbody>
                                <tr>
                                    <td>NIP</td>
                                    <td>:</td>
                                    <td>{{ $guru->NIP }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Guru</td>
                                    <td>:</td>
                                    <td>{{ $guru->nama_guru }}</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>:</td>
                                    <td>{{ $guru->jabatan }}</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td>
                                        @if ($guru->status == 'Aktif')
                                            <span class="badge badge-success">Aktif</span>
                                        @elseif ($siswa->status == 'Nonaktif')
                                            <span class="badge badge-warning">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>NUPTK</td>
                                    <td>:</td>
                                    <td>{{ $guru->NUPTK }}</td>
                                </tr>
                                <tr>
                                    <td>Pangkat / Golongan</td>
                                    <td>:</td>
                                    <td>{{ $guru->pangkat }}</td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>:</td>
                                    <td>{{ $guru->jenis_kelamin }}</td>
                                </tr>
                                <tr>
                                    <td>Tempat Lahir</td>
                                    <td>:</td>
                                    <td>{{ $guru->tempat_lahir }}</td>
                                </tr>

                                <tr>
                                    <td>Tanggal Lahir</td>
                                    <td>:</td>
                                    <td>{{ date('d F Y', strtotime($guru->tanggal_lahir)) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table mt-3">
                            <tbody>
                                <tr>
                                    <td>Pendidikan Terakhir</td>
                                    <td>:</td>
                                    <td>{{ $guru->pendidikan }}</td>
                                </tr>
                                <tr>
                                    <td>Mulai Bekerja</td>
                                    <td>:</td>
                                    <td>{{ date('d F Y', strtotime($guru->mulai_bekerja)) }}</td>
                                </tr>
                                <tr>
                                    <td>Sertifikasi</td>
                                    <td>:</td>
                                    <td>{{ $guru->sertifikasi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>No Telp</td>
                                    <td>:</td>
                                    <td>{{ $guru->no_telp }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td>{{ $guru->alamat }}</td>
                                </tr>
                                <tr>
                                    <td>Foto Pegawai</td>
                                    <td>:</td>
                                    <td>
                                        @if ($guru->foto)
                                            <a href="{{ asset($guru->foto) }}" data-lightbox="image-foto">
                                                <img src="{{ asset($guru->foto) }}" alt="Foto Guru"
                                                    style="width: 150px; height: auto;">
                                            </a>
                                        @else
                                            <p>Tidak ada foto Pegawai</p>
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
