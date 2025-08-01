@extends('layouts.app')

@section('title', 'Data Kelas')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Data Kelas</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('kelas.index') }}">Data Kelas</a></div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mr-auto mb-md-0">
                        {{-- <a class="btn btn-primary" href="{{ route('kelas.create') }}" role="button"><i
                                class="fa-solid fa-plus"></i></a> --}}
                    </div>

                    <div class="form-inline ml-auto">
                        <select class="form-control select2" name="angkatan_id" id="angkatanFilter">
                            <option value="">Semua Tahun Ajaran</option>
                            @foreach ($angkatans as $angkatan)
                                <option value="{{ $angkatan->id }}">{{ $angkatan->tahun_ajaran }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="card-body px-4">
                    <div class="table-responsive">
                        <table class="table-striped table-md table" id="kelasTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kelas</th>
                                    <th>Ruang</th>
                                    <th>Wali Kelas</th>
                                    <th>Mata Pelajaran</th>
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
            $('#kelasTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('kelas.data') }}",
                    data: function(d) {
                        d.angkatan_id = $('#angkatanFilter').val();
                    }
                },
                columns: [{
                        data: null,
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_kelas',
                        name: 'nama_kelas',
                    },
                    {
                        data: 'ruang',
                        name: 'ruang',
                    },
                    {
                        data: 'guru',
                        name: 'guru',
                    },
                    {
                        data: 'aksi_mapel',
                        name: 'aksi_mapel',
                        orderable: false,
                        searchable: false
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

        $('#angkatanFilter').change(function() {
            $('#kelasTable').DataTable().ajax.reload();
        });

        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
