<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ANAKT Blog Admin')</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Admin Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            line-height: 1.6;
        }

        /* Compact Navbar */
        .navbar {
            background: #1e293b;
            border-bottom: 1px solid #334155;
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .navbar-brand:hover {
            color: #6366f1;
            text-decoration: none;
        }

        .navbar-brand i {
            margin-right: 0.5rem;
            color: #6366f1;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 1rem;
            list-style: none;
        }

        .nav-link {
            color: #cbd5e1;
            text-decoration: none;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
            text-decoration: none;
        }

        .nav-link.active {
            color: white;
            background: #6366f1;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 0.5rem 0;
            min-width: 200px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            z-index: 1001;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            display: block;
            padding: 0.5rem 1rem;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            text-decoration: none;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border: 1px solid;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            cursor: pointer;
            background: transparent;
        }

        .btn-primary {
            border-color: #6366f1;
            color: #6366f1;
        }

        .btn-primary:hover {
            background: #6366f1;
            color: white;
            text-decoration: none;
        }

        .btn-outline-light {
            border-color: #64748b;
            color: #cbd5e1;
        }

        .btn-outline-light:hover {
            background: #64748b;
            color: white;
            text-decoration: none;
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 120px); /* Adjust based on navbar + footer height */
        }

        /* Compact Footer */
        .footer {
            background: #1e293b;
            border-top: 1px solid #334155;
            padding: 1rem 0;
            text-align: center;
            font-size: 0.875rem;
            color: #94a3b8;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                gap: 1rem;
                padding: 0 1rem;
            }

            .navbar-nav {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .dropdown-menu {
                position: static;
                display: block;
                box-shadow: none;
                border: none;
                background: transparent;
                padding: 0;
            }

            .dropdown-item {
                text-align: center;
            }
        }

        /* Utility Classes */
        .d-flex {
            display: flex;
        }

        .align-items-center {
            align-items: center;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .me-2 {
            margin-right: 0.5rem;
        }

        .text-white {
            color: white;
        }

        .text-muted {
            color: #94a3b8;
        }

        /* Success/Error Messages */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Compact Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="navbar-brand">
                <i class="fas fa-satellite"></i>
                ANAKT
            </a>
            
            <ul class="navbar-nav">
                <li><a href="{{ route('home') }}" class="nav-link">Home</a></li>
                <li><a href="{{ route('about') }}" class="nav-link">About</a></li>
                <li><a href="{{ route('blogs') }}" class="nav-link">Blogs</a></li>
                
                @auth
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">Admin Panel</a></li>
                    @endif
                    
                    <li class="dropdown">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user me-2"></i>{{ auth()->user()->name }}
                        </a>
                        <div class="dropdown-menu">
                            @if(!auth()->user()->isAdmin())
                                <a href="{{ route('profile') }}" class="dropdown-item">
                                    <i class="fas fa-user me-2"></i>Profile
                                </a>
                                <a href="{{ route('posts.create') }}" class="dropdown-item">
                                    <i class="fas fa-plus me-2"></i>Create Post
                                </a>
                                <div style="height: 1px; background: #334155; margin: 0.5rem 0;"></div>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item" style="width: 100%; text-align: left; border: none; background: none;">
                                    <i class="fas fa-sign-out-alt me-2"></i>Log Out
                                </button>
                            </form>
                        </div>
                    </li>
                @else
                    <li><a href="{{ route('login') }}" class="btn btn-outline-light">Log In</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn-primary">Register</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div style="max-width: 1200px; margin: 1rem auto; padding: 0 1rem;">
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div style="max-width: 1200px; margin: 1rem auto; padding: 0 1rem;">
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div style="max-width: 1200px; margin: 1rem auto; padding: 0 1rem;">
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul style="list-style: none; margin: 0; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Compact Footer -->
    <footer class="footer">
        <div class="footer-container">
            <p>&copy; {{ date('Y') }} ANAKT. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>