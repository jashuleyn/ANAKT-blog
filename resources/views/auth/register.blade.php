@extends('layouts.app')

@section('title', 'Register - ANAKT Blog')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/ANAKT-logo.png') }}" alt="ANAKT" class="logo mb-3" style="height: 60px;">
                    <h3>Join ANAKT Blog!</h3>
                    <p class="text-muted">Create your account to start writing</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input id="name" type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Minimum 8 characters required</div>
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label">Confirm Password</label>
                        <input id="password-confirm" type="password" 
                               class="form-control" 
                               name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Create Account
                        </button>
                    </div>
                </form>

                <div class="text-center">
                    <p class="text-muted mb-0">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-primary text-decoration-none">
                            Sign in here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection