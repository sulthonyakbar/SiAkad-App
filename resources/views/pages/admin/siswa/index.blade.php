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
                            {{-- <?php $no = ($siswa->currentPage() - 1) * $siswa->perPage() + 1; ?>
                            @foreach ($siswa as $s)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $s->NISN }}</td>
                                    <td>{{ $s->nama_siswa }}</td>
                                    <td>{{ $s->kelas->nama_kelas ?? '-' }}</td>
                                    <td>{{ $s->orang_tuas->nama_ayah ?? '-' }}</td>
                                    <td>{{ $s->orang_tuas->nama_ibu ?? '-' }}</td>
                                    <td class="row">
                                        <a href="{{ route('siswa.detail', $s->id) }}" class="btn btn-info btn-action mr-1"
                                            data-toggle="tooltip" title="Detail">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('siswa.edit', $s->id) }}" class="btn btn-success btn-action mr-1"
                                            data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <form id="delete-form-{{ $s->id }}"
                                            action="{{ route('siswa.destroy', $s->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="confirmDelete(event, 'delete-form-{{ $s->id }}')"><i
                                                    class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php $no++; ?>
                            @endforeach --}}
                        </table>
                    </div>
                </div>

                {{-- <div class="card-footer text-right">
                    <nav class="d-inline-block">
                        <ul class="pagination mb-0">
                            <!-- Tombol Sebelumnya -->
                            <li class="page-item {{ $siswa->currentPage() <= 1 ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $siswa->previousPageUrl() ?? '#' }}" tabindex="-1"><i
                                        class="fas fa-chevron-left"></i></a>
                            </li>

                            <!-- Halaman-halaman -->
                            @for ($i = 1; $i <= $siswa->lastPage(); $i++)
                                <li class="page-item {{ $i === $siswa->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="?page={{ $i }}">{{ $i }} <span
                                            class="sr-only">(current)</span></a>
                                </li>
                            @endfor

                            <!-- Tombol Berikutnya -->
                            <li class="page-item {{ $siswa->currentPage() >= $siswa->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $siswa->nextPageUrl() ?? '#' }}"><i
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
                                return '<div class="badge badge-primary">Nonaktif</div>';
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
