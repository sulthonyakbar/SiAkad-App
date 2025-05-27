@extends('layouts.auth')

@section('title', 'Login')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
@endpush

@section('main')

    <div class="card card-primary col-lg-6 shadow-lg rounded">

        <div class="card-header d-flex flex-column justify-content-center align-items-center">
            <h4 style="color: #4A628A;">Login</h4>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('login.post') }}" class="needs-validation" novalidate="">
                @csrf
                <div class="form-group">
                    <label for="login">Username atau Email</label>
                    <input id="login" type="text" class="form-control" name="login" tabindex="1" required
                        autofocus>
                    <div class="invalid-feedback">
                        Silakan masukkan username atau email Anda
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-block">
                        <label for="password" class="control-label">Password</label>
                    </div>
                    <div class="input-group">
                        <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                <i class="fas fa-eye-slash"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback">
                            Silakan masukkan password Anda
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-block text-white" tabindex="4"
                        style="background-color: #4A628A;">
                        Login
                    </button>
                </div>
            </form>

        </div>
    </div>
    {{-- <div class="text-muted text-center">
        Belum Punya Akun? <a href="/register">Register</a>
    </div> --}}
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');

            togglePassword.addEventListener('click', function(e) {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                this.querySelector('i').classList.toggle('fa-eye-slash');
                this.querySelector('i').classList.toggle('fa-eye');
            });
        });
    </script>
    <!-- Page Specific JS File -->
@endpush
