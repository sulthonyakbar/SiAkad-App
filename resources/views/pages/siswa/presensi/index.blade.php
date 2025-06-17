@extends('layouts.app')

@section('title', 'Data Presensi Siswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Data Presensi Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('siswa.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('presensi.index') }}">Data Presensi Siswa</a></div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mr-auto mb-md-0">
                        {{-- <a class="btn btn-primary" href="{{ route('aktivitas.create') }}" role="button"><i
                                class="fa-solid fa-plus"></i></a> --}}
                    </div>
                    <div class="form-inline ml-auto">
                        <select class="form-control mr-2" name="angkatan_id" id="angkatanFilter">
                            <option value="">Semua Tahun Ajaran</option>
                            @foreach ($angkatans as $angkatan)
                                <option value="{{ $angkatan->id }}">{{ $angkatan->tahun_ajaran }}</option>
                            @endforeach
                        </select>

                        <select class="form-control" name="semester_id" id="semesterFilter">
                            <option value="">Semua Semester</option>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->semester }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body px-4">
                    <div class="table-responsive">
                        <table class="table-striped table-md table" id="presensiSiswaTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Hari</th>
                                    <th>Status</th>
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
            $('#presensiSiswaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('siswa.presensi.data') }}",
                data: function(d) {
                    d.semester_id = $('#semesterFilter').val();
                    d.angkatan_id = $('#angkatanFilter').val();
                },
                columns: [{
                        data: null,
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                    },
                    {
                        data: 'hari',
                        name: 'hari',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            if (data === 'Hadir') {
                                return '<span class="badge badge-success">Hadir</span>';
                            } else if (data === 'Sakit') {
                                return '<span class="badge badge-info">Sakit</span>';
                            } else if (data === 'Izin') {
                                return '<span class="badge badge-warning">Izin</span>';
                            } else {
                                return '<span class="badge badge-danger">Alpa</span>';
                            }
                        }
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

        $('#semesterFilter, #angkatanFilter').change(function() {
            $('#presensiSiswaTable').DataTable().ajax.reload();
        });
    </script>
@endpush
