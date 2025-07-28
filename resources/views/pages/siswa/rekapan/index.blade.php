@extends('layouts.app')

@section('title', 'Data Rekapan Siswa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Data Rekapan Siswa</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('siswa.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('siswa.rekapan.index') }}">Data Rekapan Siswa</a></div>
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
                        <select class="form-control select2" name="semester_id" id="semesterFilter">
                            <option value="">Semua Semester</option>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}">
                                    {{ $semester->angkatan->tahun_ajaran ?? '-' }} - {{ $semester->nama_semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="card-body px-4">
                    <div class="table-responsive">
                        <table class="table-striped table-md table" id="rekapanSiswaTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Semester</th>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#rekapanSiswaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('siswa.rekapan.data') }}",
                    data: function(d) {
                        d.semester_id = $('#semesterFilter').val();
                    }
                },
                columns: [{
                        data: null,
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tahun_ajaran',
                        name: 'tahun_ajaran',
                    },
                    {
                        data: 'semester',
                        name: 'semester',
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

        $('#semesterFilter').change(function() {
            $('#rekapanSiswaTable').DataTable().ajax.reload();
        });

        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
