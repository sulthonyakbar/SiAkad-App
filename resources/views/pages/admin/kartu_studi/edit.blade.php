@extends('layouts.app')

@section('title', 'Edit Data Penentuan Kelas')

@push('style')
<!-- CSS Libraries -->
<link href="{{ asset('dist/css/lightbox.css') }}" rel="stylesheet">
@endpush

@section('main')
<section class="section">
    <div class="section-header">
        <h1>Edit Data Penentuan Kelas</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('kartu.studi.index') }}">Data Kartu Studi</a></div>
        </div>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <a class="btn btn-primary" href="{{ route('kartu.studi.index') }}" role="button"><i
                    class="fa-solid fa-chevron-left"></i></a>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('kartu.studi.update', $kelas->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $kelas->id }}" required>

                <h5>Kelas: <span class="text-primary">{{ $kelas->nama_kelas }} </span> - Ruang {{ $kelas->ruang }}</h5>

                <div class="form-group">
                    <label for="siswa_id">Pilih Siswa</label>
                    <select name="siswa_id[]" id="siswa_id" class="form-control select2" multiple>
                        @foreach ($siswaTersedia as $s)
                        <option value="{{ $s->id }}"
                            {{ $kelas->kartuStudi->pluck('siswa_id')->contains($s->id) ? 'selected' : '' }}>
                            {{ $s->NISN . ' - ' . $s->nama_siswa }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Edit Data Penentuan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<!-- JS Libraies -->
<script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>

<!-- Page Specific JS File -->
<script src="{{ asset('js/page/components-table.js') }}"></script>
<script src="{{ asset('dist/js/lightbox.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        let selectedData = {!! json_encode($kelas->kartuStudi->pluck('siswa_id')) !!};

        $('#siswa_id').select2({
            placeholder: 'Pilih Siswa',
            allowClear: true,
            ajax: {
                url: '{{ route('ks.search.kelas') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    let results = $.map(data, function(item) {
                        return {
                            id: item.id,
                            text: item.NISN + ' - ' + item.nama_siswa
                        };
                    });

                    // Hanya tambahkan siswa yang belum ada di dropdown
                    selectedData.forEach(id => {
                        if (!$('#siswa_id option[value="' + id + '"]').length) {
                            let existing = $('#siswa_id option[value="' + id + '"]').text();
                            if (existing) {
                                results.unshift({
                                    id: id,
                                    text: existing
                                });
                            }
                        }
                    });

                    return {
                        results: results
                    };
                },
                cache: true
            }
        });

        // Pastikan tidak menambahkan opsi duplikat saat inisialisasi
        selectedData.forEach(id => {
            if (!$('#siswa_id option[value="' + id + '"]').length) {
                let existing = $('#siswa_id option[value="' + id + '"]').text();
                if (existing) {
                    let newOption = new Option(existing, id, true, true);
                    $('#siswa_id').append(newOption).trigger('change');
                }
            }
        });
    });
</script>
@endpush