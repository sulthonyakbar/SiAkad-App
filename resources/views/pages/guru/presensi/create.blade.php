@extends('layouts.app')

@section('title', 'Tambah Data Presensi Siswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Tambah Data Presensi Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('guru.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('presensi.index') }}">Data Presensi Siswa</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('presensi.index') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <form action="{{ route('presensi.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="kelas_id" value="{{ $kelas->id ?? '' }}">

                    <div class="form-group">
                        <label for="tanggal">Tanggal Presensi</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" style="width: 250px;"
                            value="{{ now()->format('Y-m-d') }}">
                    </div>

                    <div class="table-responsive">
                        <table class="table-striped table-md table" id="addPresensiTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                Tambah Data Presensi Siswa
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/components-table.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#addPresensiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('presensi.create.data') }}",
                columns: [{
                        data: null,
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'NISN',
                        name: 'NISN',
                    },
                    {
                        data: 'nama_siswa',
                        name: 'nama_siswa',
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    } // Tidak bisa dicari
                ],
                columnDefs: [{
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + 1; // Menampilkan nomor urut
                    }
                }],
            });
        });
    </script>
@endpush
