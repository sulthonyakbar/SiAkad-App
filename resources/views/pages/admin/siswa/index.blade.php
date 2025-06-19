@extends('layouts.app')

@section('title', 'Data Siswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Data Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('siswa.index') }}">Data Siswa</a></div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mr-auto mb-md-0">
                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#importSection">
                            <i class="fas fa-file-import"></i> Import
                        </button>

                        <div class="collapse mt-3" id="importSection">
                            <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row align-items-end">
                                    <div class="col-md-8">
                                        <div class="form-group mb-0">
                                            <label for="file">File Excel/CSV</label>
                                            <input type="file" name="file" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-success btn-block mt-4">
                                            <i class="fas fa-upload"></i> Upload
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body px-4">
                    <div class="table-responsive">
                        <table class="table-striped table-md table" id="siswaTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama siswa</th>
                                    <th>Kelas</th>
                                    <th>Nama Ayah</th>
                                    <th>Nama Ibu</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
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

    <script>
        $(document).ready(function() {
            $('#siswaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('siswa.data') }}",
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
                        data: 'kelas',
                        name: 'kelas',
                    },
                    {
                        data: 'nama_ayah',
                        name: 'nama_ayah',
                    },
                    {
                        data: 'nama_ibu',
                        name: 'nama_ibu',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            if (data == 'Aktif') {
                                return '<div class="badge badge-success">Aktif</div>';
                            } else if (data == 'Nonaktif') {
                                return '<div class="badge badge-danger">Nonaktif</div>';
                            } else if (data == 'Lulus') {
                                return '<div class="badge badge-primary">Lulus</div>';
                            }
                            return data;
                        }
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
