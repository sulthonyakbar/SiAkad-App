@extends('layouts.app')

@section('title', 'Data Nilai Siswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Data Nilai Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('siswa.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('nilai.index') }}">Data Nilai Siswa</a></div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mr-auto mb-md-0">
                        {{-- <a class="btn btn-primary" href="{{ route('aktivitas.create') }}" role="button"><i
                                class="fa-solid fa-plus"></i></a> --}}
                    </div>
                </div>
                <div class="card-body px-4">
                    <div class="table-responsive">
                        <table class="table-striped table-md table" id="nilaiSiswaTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Semester</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Nilai UH</th>
                                    <th>Nilai UTS</th>
                                    <th>Nilai UAS</th>
                                    <th>Nilai Akhir</th>
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
            $('#nilaiSiswaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('siswa.nilai.data') }}",
                columns: [{
                        data: null,
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'semester',
                        name: 'semester',
                    },
                    {
                        data: 'nama_mapel',
                        name: 'nama_mapel',
                    },
                    {
                        data: 'nilai_uh',
                        name: 'nilai_uh',
                    },
                    {
                        data: 'nilai_uts',
                        name: 'nilai_uts',
                    },
                    {
                        data: 'nilai_uas',
                        name: 'nilai_uas',
                    },
                    {
                        data: 'nilai_akhir',
                        name: 'nilai_akhir',
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
