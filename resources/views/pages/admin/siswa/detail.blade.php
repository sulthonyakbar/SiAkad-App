@extends('layouts.app')

@section('title', 'Detail Data Siswa')

@push('style')
    <!-- CSS Libraries -->
    <link href="{{ asset('dist/css/lightbox.css') }}" rel="stylesheet">
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Detail Data Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('siswa.index') }}">Data Siswa</a></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('siswa.index') }}" role="button"><i class="fa-solid fa-chevron-left"></i></a>
            </div>
            <div class="card-body">
                <h5 class="text-primary">Informasi Siswa</h5>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table mt-3">
                            <tbody>
                                <tr>
                                    <td>Nama Siswa</td>
                                    <td>:</td>
                                    <td>{{ $siswa->nama_siswa }}</td>
                                </tr>
                                <tr>
                                    <td>Nomor Induk Asal</td>
                                    <td>:</td>
                                    <td>{{ $siswa->nomor_induk }}</td>
                                </tr>
                                <tr>
                                    <td>NISN</td>
                                    <td>:</td>
                                    <td>{{ $siswa->NISN }}</td>
                                </tr>
                                <tr>
                                    <td>NIK</td>
                                    <td>:</td>
                                    <td>{{ $siswa->NIK }}</td>
                                </tr>
                                <tr>
                                    <td>Tempat Tanggal Lahir</td>
                                    <td>:</td>
                                    <td>{{ $siswa->tempat_lahir }}, {{ date('d F Y', strtotime($siswa->tanggal_lahir)) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>:</td>
                                    <td>{{ $siswa->jenis_kelamin }}</td>
                                </tr>
                                <tr>
                                    <td>No Telp</td>
                                    <td>:</td>
                                    <td>{{ $siswa->no_telp_siswa }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td>{{ $siswa->alamat_siswa }}</td>
                                </tr>
                                <tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>:</td>
                                        <td>
                                            @if ($siswa->status == 'Aktif')
                                                <span class="badge badge-success">Aktif</span>
                                            @elseif ($siswa->status == 'Nonaktif')
                                                <span class="badge badge-warning">Nonaktif</span>
                                            @elseif ($siswa->status == 'Lulus')
                                                <span class="badge badge-primary">Lulus</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table mt-3">
                            <tbody>
                                <tr>
                                    <td>Tamatan dari</td>
                                    <td>:</td>
                                    <td>{{ $siswa->tamatan }}</td>
                                </tr>

                                <tr>
                                    <td>Tanggal Lulus</td>
                                    <td>:</td>
                                    <td>{{ date('d F Y', strtotime($siswa->tanggal_lulus)) }}</td>
                                </tr>
                                <tr>
                                    <td>No. STTB</td>
                                    <td>:</td>
                                    <td>{{ $siswa->STTB }}</td>
                                </tr>
                                <tr>
                                    <td>Lama Belajar</td>
                                    <td>:</td>
                                    <td>{{ $siswa->lama_belajar }} Tahun</td>
                                </tr>
                                <tr>
                                    <td>Pindahan dari</td>
                                    <td>:</td>
                                    <td>{{ $siswa->pindahan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Alasan</td>
                                    <td>:</td>
                                    <td>{{ $siswa->alasan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Foto Siswa</td>
                                    <td>:</td>
                                    <td>
                                        @if ($siswa->foto)
                                            <a href="{{ asset($siswa->foto) }}" data-lightbox="image-foto">
                                                <img src="{{ asset($siswa->foto) }}" alt="Foto Siswa"
                                                    style="width: 150px; height: auto;">
                                            </a>
                                        @else
                                            <p>Tidak ada foto Siswa</p>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <h5 class="text-primary">Informasi Orang Tua</h5>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table mt-3">
                            <tbody>
                                <tr>
                                    <td>Nama Ayah</td>
                                    <td>:</td>
                                    <td>{{ $siswa->orang_tuas->nama_ayah }}</td>
                                </tr>
                                <tr>
                                    <td>Pendidikan Ayah</td>
                                    <td>:</td>
                                    <td>{{ $siswa->orang_tuas->pendidikan_ayah }}</td>
                                </tr>
                                <tr>
                                    <td>Pekerjaan Ayah</td>
                                    <td>:</td>
                                    <td>{{ $siswa->orang_tuas->pekerjaan_ayah }}</td>
                                </tr>
                                <tr>
                                    <td>Penghasilan Ayah</td>
                                    <td>:</td>
                                    <td>Rp {{ number_format($siswa->orang_tuas->penghasilan_ayah) }} </td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td>{{ $siswa->orang_tuas->alamat_ortu }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table mt-3">
                            <tbody>
                                <tr>
                                    <td>Nama Ibu</td>
                                    <td>:</td>
                                    <td>{{ $siswa->orang_tuas->nama_ibu }}</td>
                                </tr>
                                <tr>
                                    <td>Pendidikan Ibu</td>
                                    <td>:</td>
                                    <td>{{ $siswa->orang_tuas->pendidikan_ibu }}</td>
                                </tr>
                                <tr>
                                    <td>Pekerjaan Ibu</td>
                                    <td>:</td>
                                    <td>{{ $siswa->orang_tuas->pekerjaan_ibu }}</td>
                                </tr>
                                <tr>
                                    <td>Penghasilan Ibu</td>
                                    <td>:</td>
                                    <td>Rp {{ number_format($siswa->orang_tuas->penghasilan_ibu) }} </td>
                                </tr>
                                <tr>
                                    <td>No. Telp</td>
                                    <td>:</td>
                                    <td>{{ $siswa->orang_tuas->no_telp_ortu }}</td>
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
