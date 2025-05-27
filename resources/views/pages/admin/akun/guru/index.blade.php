@extends('layouts.app')

@section('title', 'Data Akun Guru')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Data Akun Guru</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('guru.akun.index') }}">Data Akun Guru</a></div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
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
                        <table class="table-striped table-md table" id="akunGuruTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Guru</th>
                                    <th>Email</th>
                                    <th>Username</th>
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
            $('#akunGuruTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('guru.akun.data') }}",
                columns: [{
                        data: null,
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_guru',
                        name: 'nama_guru',
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'username',
                        name: 'username',
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
