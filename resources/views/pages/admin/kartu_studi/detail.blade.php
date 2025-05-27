@extends('layouts.app')

@section('title', 'Detail Data Kartu Studi')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Detail Data Kartu Studi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('kartu.studi.index') }}">Data Kartu Studi</a></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ route('kartu.studi.index') }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Kelas: <span class="text-primary">{{ $kelas->nama_kelas }} </span> - Ruang {{ $kelas->ruang }}
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <h6>Tahun Ajaran: <span class="text-primary">{{ $kelas->angkatan->tahun_ajaran ?? '-' }}</span></h6>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-striped table-md table" id="detailKSTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th>Action</th>
                            </tr>
                        </thead>
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

    <script>
        $(document).ready(function() {
            $('#detailKSTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('kartu.studi.detail.data', $kelas->id) }}",
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
