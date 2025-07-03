@extends('layouts.app')

@section('title', 'Data Kartu Studi')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Data Kartu Studi</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('kartu.studi.index') }}">Data Kartu Studi</a></div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mr-auto mb-md-0">
                        {{-- <a class="btn btn-primary" href="{{ route('kartu.studi.create') }}" role="button"><i
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
                        <table class="table-striped table-md table" id="ksTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Semester</th>
                                    <th>Kelas</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                {{-- <div class="card-footer text-right">
                    <nav class="d-inline-block">
                        <ul class="pagination mb-0">
                            <!-- Tombol Sebelumnya -->
                            <li class="page-item {{ $kelas->currentPage() <= 1 ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $kelas->previousPageUrl() ?? '#' }}" tabindex="-1"><i
                                        class="fas fa-chevron-left"></i></a>
                            </li>

                            <!-- Halaman-halaman -->
                            @for ($i = 1; $i <= $kelas->lastPage(); $i++)
                                <li class="page-item {{ $i === $kelas->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="?page={{ $i }}">{{ $i }} <span
                                            class="sr-only">(current)</span></a>
                                </li>
                            @endfor

                            <!-- Tombol Berikutnya -->
                            <li class="page-item {{ $kelas->currentPage() >= $kelas->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $kelas->nextPageUrl() ?? '#' }}"><i
                                        class="fas fa-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                </div> --}}
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
            $('#ksTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('kartu.studi.data') }}",
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
                        data: 'kelas',
                        name: 'kelas',
                    },
                    {
                        data: 'jumlah_siswa',
                        name: 'jumlah_siswa',
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
            $('#ksTable').DataTable().ajax.reload();
        });

        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
