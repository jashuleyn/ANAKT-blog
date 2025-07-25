<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ANAKT Blog')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6b73ff;
            --secondary-color: #9c88ff;
            --dark-bg: #1a1d29;
            --white-bg: #ffffff;
            --card-bg: #f8f9fa;
            --text-dark: #2d3748;
            --text-light: #e2e8f0;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
        }

        body {
            background-color: var(--white-bg);
            color: var(--text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .navbar {
            background-color: var(--dark-bg) !important;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
        }

        .navbar-brand {
            color: var(--text-light) !important;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: var(--text-light) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(107, 115, 255, 0.3);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .card {
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .card-title {
            color: var(--text-dark);
            font-weight: 600;
        }

        .card-text {
            color: var(--text-muted);
        }

        .section-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 25px;
            padding: 1rem 2rem;
            color: white;
            font-weight: 600;
            margin-bottom: 2rem;
        }

        .footer {
            background-color: var(--dark-bg);
            color: var(--text-light);
            text-align: center;
            padding: 2rem 0;
            border-top: 1px solid var(--border-color);
            margin-top: 4rem;
        }

        .form-control {
            background-color: white;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            border-radius: 10px;
        }

        .form-control:focus {
            background-color: white;
            border-color: var(--primary-color);
            color: var(--text-dark);
            box-shadow: 0 0 0 0.2rem rgba(107, 115, 255, 0.25);
        }

        .form-label {
            color: var(--text-dark);
            font-weight: 500;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .alert-success {
            background-color: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .blog-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .author-info {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .badge {
            border-radius: 15px;
            font-weight: 500;
        }

        .badge-pending {
            background-color: #f59e0b;
        }

        .badge-approved {
            background-color: #10b981;
        }

        .badge-rejected {
            background-color: #ef4444;
        }

        .logo {
            height: 40px;
            margin-right: 10px;
        }

        .dropdown-menu {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
        }

        .dropdown-item {
            color: var(--text-light);
        }

        .dropdown-item:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .table-dark {
            background-color: var(--card-bg);
        }

        .table-dark th,
        .table-dark td {
            border-color: var(--border-color);
        }

        .pagination {
            --bs-pagination-bg: var(--card-bg);
            --bs-pagination-border-color: var(--border-color);
            --bs-pagination-color: var(--text-light);
            --bs-pagination-hover-bg: var(--primary-color);
            --bs-pagination-hover-border-color: var(--primary-color);
            --bs-pagination-active-bg: var(--primary-color);
            --bs-pagination-active-border-color: var(--primary-color);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/ANAKT-logo.png') }}" alt="ANAKT" class="logo">
                ANAKT
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blogs') }}">Blogs</a>
                    </li>
                    @auth
                        @if(!auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('posts.create') }}">Create Post</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile') }}">My Posts</a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light">Log Out</button>
                            </form>
                        </li>
                        <li class="nav-item ms-2">
                            <span class="btn btn-outline-primary">
                                {{ auth()->user()->isAdmin() ? 'Admin' : 'User' }}
                            </span>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 ANAKT. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>