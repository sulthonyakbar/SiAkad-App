@extends('layouts.app')

@section('title', 'Data Mata Pelajaran')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Data Mata Pelajaran</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('mapel.index') }}">Data Mata Pelajaran</a></div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mr-auto mb-md-0">
                        {{-- <a class="btn btn-primary" href="{{ route('mapel.create') }}" role="button"><i
                                class="fa-solid fa-plus"></i></a> --}}
                    </div>
                    {{-- <form action="{{ route('kelas.index') }}" method="GET" class="form-inline ml-auto">
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
                        <table class="table-striped table-md table" id="mapelTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Deskripsi</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            {{-- <?php $no = ($mapel->currentPage() - 1) * $mapel->perPage() + 1; ?>
                            @foreach ($mapel as $m)
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $m->nama_mapel }}</td>
                                    <td>{{ $m->deskrispi ?? '-' }}</td>
                                    <td>{{ $m->guru->nama_guru }}</td>
                                    <td class="row">
                                        <a href="{{ route('mapel.edit', $m->id) }}" class="btn btn-success btn-action mr-1"
                                            data-toggle="tooltip" title="Edit"><i class="fas fa-pencil-alt"></i></a>
                                        <form id="delete-form-{{ $m->id }}"
                                            action="{{ route('mapel.destroy', $m->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="confirmDelete(event, 'delete-form-{{ $m->id }}')"><i
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
                            <li class="page-item {{ $mapel->currentPage() <= 1 ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $mapel->previousPageUrl() ?? '#' }}" tabindex="-1"><i
                                        class="fas fa-chevron-left"></i></a>
                            </li>

                            <!-- Halaman-halaman -->
                            @for ($i = 1; $i <= $mapel->lastPage(); $i++)
                                <li class="page-item {{ $i === $mapel->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="?page={{ $i }}">{{ $i }} <span
                                            class="sr-only">(current)</span></a>
                                </li>
                            @endfor

                            <!-- Tombol Berikutnya -->
                            <li class="page-item {{ $mapel->currentPage() >= $mapel->lastPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $mapel->nextPageUrl() ?? '#' }}"><i
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
            $('#mapelTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('mapel.data') }}",
                columns: [{
                        data: null,
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_mapel',
                        name: 'nama_mapel',
                    },
                    {
                        data: 'deskripsi',
                        name: 'deskripsi',
                        render: function(data, type, row) {
                            return data ? data : '-';
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
