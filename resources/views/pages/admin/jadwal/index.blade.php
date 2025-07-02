@extends('layouts.app')

@section('title', 'Data Jadwal Pelajaran')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Data Jadwal Pelajaran</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="/admin/jadwal">Data Jadwal Pelajaran</a></div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mr-auto mb-md-0">
                        {{-- <a class="btn btn-primary" href="{{ route('jadwal.create') }}" role="button"><i
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
                        <table class="table-striped table-md table" id="jadwalTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Hari</th>
                                    <th>Jam Pelajaran</th>
                                    <th>Pengajar</th>
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
            $('#jadwalTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('jadwal.data') }}",
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
                        data: 'kelas',
                        name: 'kelas',
                    },
                    {
                        data: 'mapel',
                        name: 'mapel',
                    },
                    {
                        data: 'hari',
                        name: 'hari',
                    },
                    {
                        data: 'jam',
                        name: 'jam',
                    },
                    {
                        data: 'guru',
                        name: 'guru',
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
            $('#jadwalTable').DataTable().ajax.reload();
        });

        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
