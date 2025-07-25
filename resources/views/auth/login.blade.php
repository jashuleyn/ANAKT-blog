@extends('layouts.app')

@section('title', 'Login - ANAKT Blog')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/ANAKT-logo.png') }}" alt="ANAKT" class="logo mb-3" style="height: 60px;">
                    <h3>Welcome Back!</h3>
                    <p class="text-muted">Sign in to your account</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                               placeholder="Enter your email address">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="current-password"
                               placeholder="Enter your password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                    </div>
                </form>

                <div class="text-center">
                    <p class="text-muted mb-0">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-primary text-decoration-none">
                            Create one here
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Demo Accounts Info -->
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-info-circle me-2 text-info"></i>Demo Accounts
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded mb-2">
                            <strong>Admin Account:</strong><br>
                            <small class="text-muted">Email:</small> admin@anakt.com<br>
                            <small class="text-muted">Password:</small> admin123
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded mb-2">
                            <strong>User Account:</strong><br>
                            <small class="text-muted">Email:</small> user@anakt.com<br>
                            <small class="text-muted">Password:</small> user123
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection