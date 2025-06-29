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
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('admin.nilai.index') }}">Data Nilai Siswa</a></div>
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
                        <table class="table-striped table-md table" id="nilaiAdminTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Semester</th>
                                    <th>Nama Siswa</th>
                                    <th>NISN</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Nilai UH</th>
                                    <th>Nilai UTS</th>
                                    <th>Nilai UAS</th>
                                    <th>Nilai Akhir</th>
                                    {{-- <th>Action</th> --}}
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
            $('#nilaiAdminTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.nilai.data') }}",
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
                        data: 'angkatan',
                        name: 'angkatan',
                    },
                    {
                        data: 'semester',
                        name: 'semester',
                    },
                    {
                        data: 'nama_siswa',
                        name: 'nama_siswa',
                    },
                    {
                        data: 'NISN',
                        name: 'NISN',
                    },
                    {
                        data: 'mapel',
                        name: 'mapel',
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
                    // {
                    //     data: 'aksi',
                    //     name: 'aksi',
                    //     orderable: false,
                    //     searchable: false
                    // } // Tidak bisa dicari
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
            $('#nilaiAdminTable').DataTable().ajax.reload();
        });

        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
