@extends('layouts.app')

@section('title', 'Data Alumni Siswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Data Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('alumni.index') }}">Data Alumni Siswa</a></div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mr-auto mb-md-0">
                        {{-- <a class="btn btn-primary" href="{{ route('siswa.create') }}" role="button"><i
                                class="fa-solid fa-plus"></i></a> --}}
                    </div>
                    {{-- <form action="{{ route('guru.index') }}" method="GET" class="form-inline ml-auto">
                        <div class="input-group" style="width: 320px;">
                            <input type="text" name="search" class="form-control rounded" placeholder="Search"
                                value="{{ $search ?? '' }}" style="height: 42px;">
                            <div class="input-group-append">
                                <button class="btn btn-primary rounded" type="submit"><i
                                        class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form> --}}
                </div>
                <div class="card-body px-4">
                    <div class="table-responsive">
                        <table class="table-striped table-md table" id="alumniTable">
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
            $('#alumniTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('alumni.data') }}",
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
