@extends('layouts.app')

@section('title', 'Edit Data Akun User')

@push('style')
    <!-- CSS Libraries -->
    <link href="{{ asset('dist/css/lightbox.css') }}" rel="stylesheet">
@endpush

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Edit Data Akun User</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('siswa.akun.index') }}">Data Akun User</a></div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <a class="btn btn-primary" href="{{ url()->previous() }}" role="button"><i
                        class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="card-body">
                <form action="{{ $updateRoute }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                    class="form-control @error('username') is-invalid @enderror">
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                            <i class="fas fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" id="confirmPassword" name="password_confirmation" class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="togglePassword2" style="cursor: pointer;">
                                            <i class="fas fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            Edit Data Akun
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
            $('.select2').select2({});
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const togglePassword2 = document.querySelector('#togglePassword2');
            const password = document.querySelector('#password');
            const confirmPassword = document.querySelector('#confirmPassword');

            togglePassword.addEventListener('click', function(e) {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                this.querySelector('i').classList.toggle('fa-eye-slash');
                this.querySelector('i').classList.toggle('fa-eye');
            });

            togglePassword2.addEventListener('click', function(e) {
                const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPassword.setAttribute('type', type);

                this.querySelector('i').classList.toggle('fa-eye-slash');
                this.querySelector('i').classList.toggle('fa-eye');
            });
        });
    </script>
@endpush
