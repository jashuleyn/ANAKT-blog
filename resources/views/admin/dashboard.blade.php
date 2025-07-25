@extends('layouts.admin')

@section('title', 'Admin Dashboard - ANAKT Blog')

@section('content')
<style>
    /* ANAKT Theme Variables */
    :root {
        --anakt-primary: #6366f1;
        --anakt-secondary: #8b5cf6;
        --anakt-accent: #06b6d4;
        --anakt-warning: #f59e0b;
        --anakt-success: #10b981;
        --anakt-danger: #ef4444;
        --anakt-dark: #0f172a;
        --anakt-darker: #020617;
        --anakt-card: #1e293b;
        --anakt-border: #334155;
    }

    body {
        background: var(--anakt-dark);
        color: #e2e8f0;
    }

    .anakt-gradient {
        background: linear-gradient(135deg, var(--anakt-primary) 0%, var(--anakt-secondary) 50%, var(--anakt-accent) 100%);
    }

    .anakt-card {
        background: var(--anakt-card);
        border: 1px solid var(--anakt-border);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .anakt-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.1);
    }

    .stat-card {
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, currentColor, transparent);
        opacity: 0.6;
    }

    .cosmic-bg {
        background: radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 40% 80%, rgba(6, 182, 212, 0.1) 0%, transparent 50%);
        min-height: 100vh;
    }

    .glow-effect {
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
    }

    .pending-pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    .data-table {
        background: var(--anakt-card);
        border-radius: 8px;
        overflow: hidden;
        width: 100%;
    }

    .data-table th {
        background: var(--anakt-darker);
        color: #e2e8f0;
        font-weight: 600;
        padding: 1rem;
        border-bottom: 1px solid var(--anakt-border);
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--anakt-border);
        color: #cbd5e1;
    }

    .data-table tbody tr:hover {
        background: rgba(99, 102, 241, 0.05);
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .badge-approved { background: rgba(16, 185, 129, 0.2); color: #10b981; }
    .badge-pending { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .badge-rejected { background: rgba(239, 68, 68, 0.2); color: #ef4444; }

    .action-btn {
        padding: 0.5rem;
        border-radius: 6px;
        border: 1px solid var(--anakt-border);
        background: transparent;
        color: #cbd5e1;
        transition: all 0.2s ease;
        margin: 0 2px;
    }

    .action-btn:hover {
        background: var(--anakt-primary);
        border-color: var(--anakt-primary);
        color: white;
    }

    .quick-action-card {
        border-left: 3px solid var(--anakt-accent);
    }

    .btn-anakt {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid;
    }

    .btn-primary {
        background: rgba(99, 102, 241, 0.2);
        border-color: rgba(99, 102, 241, 0.3);
        color: #6366f1;
    }

    .btn-primary:hover {
        background: #6366f1;
        color: white;
        text-decoration: none;
    }

    .btn-warning {
        background: rgba(245, 158, 11, 0.2);
        border-color: rgba(245, 158, 11, 0.3);
        color: #f59e0b;
    }

    .btn-warning:hover {
        background: #f59e0b;
        color: white;
        text-decoration: none;
    }

    .btn-info {
        background: rgba(6, 182, 212, 0.2);
        border-color: rgba(6, 182, 212, 0.3);
        color: #06b6d4;
    }

    .btn-info:hover {
        background: #06b6d4;
        color: white;
        text-decoration: none;
    }

    .btn-outline-primary {
        background: transparent;
        border-color: #6366f1;
        color: #6366f1;
    }

    .btn-outline-primary:hover {
        background: #6366f1;
        color: white;
        text-decoration: none;
    }

    .btn-outline-success {
        background: transparent;
        border-color: #10b981;
        color: #10b981;
    }

    .btn-outline-success:hover {
        background: #10b981;
        color: white;
        text-decoration: none;
    }

    .btn-outline-warning {
        background: transparent;
        border-color: #f59e0b;
        color: #f59e0b;
    }

    .btn-outline-warning:hover {
        background: #f59e0b;
        color: white;
        text-decoration: none;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin: -0.75rem;
    }

    .col-md-3 {
        flex: 0 0 25%;
        max-width: 25%;
        padding: 0.75rem;
    }

    .col-lg-8 {
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
        padding: 0.75rem;
    }

    .col-lg-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
        padding: 0.75rem;
    }

    @media (max-width: 768px) {
        .col-md-3, .col-lg-8, .col-lg-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .container {
            padding: 1rem;
        }
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .align-items-center {
        align-items: center;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mb-5 {
        margin-bottom: 3rem;
    }

    .me-2 {
        margin-right: 0.5rem;
    }

    .ms-2 {
        margin-left: 0.5rem;
    }

    .text-center {
        text-align: center;
    }

    .py-4 {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }

    .pb-3 {
        padding-bottom: 1rem;
    }

    .border-bottom {
        border-bottom: 1px solid var(--anakt-border);
    }

    .text-muted {
        color: #64748b;
    }

    .text-primary {
        color: var(--anakt-primary);
    }

    .bg-transparent {
        background: transparent;
    }

    .border-bottom-0 {
        border-bottom: none;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table-hover tbody tr:hover {
        background: rgba(99, 102, 241, 0.05);
    }

    .btn-group {
        display: flex;
        gap: 0.5rem;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .flex-grow-1 {
        flex-grow: 1;
    }

    .card {
        background: var(--anakt-card);
        border: 1px solid var(--anakt-border);
        border-radius: 12px;
        overflow: hidden;
    }

    .card-header {
        background: var(--anakt-darker);
        border-bottom: 1px solid var(--anakt-border);
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: #e2e8f0;
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-title {
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    h5 {
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    h6 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .fa-2x {
        font-size: 2em;
    }

    .fa-3x {
        font-size: 3em;
    }

    .opacity-50 {
        opacity: 0.5;
    }

    .text-white {
        color: white;
    }

    .bg-primary {
        background: linear-gradient(135deg, #3b82f6, #6366f1);
    }

    .bg-success {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .bg-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .bg-info {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }
</style>

<div class="cosmic-bg">
    <div class="container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;" class="anakt-gradient">
                    <i class="fas fa-satellite text-white"></i>
                </div>
                <div>
                    <h2 style="margin: 0; color: white; font-size: 1.875rem; font-weight: 700;">Admin Dashboard</h2>
                    <p style="margin: 0; color: #94a3b8; font-size: 0.875rem;">Mission Control Center</p>
                </div>
            </div>
            
            <div class="btn-group">
                <a href="{{ route('admin.posts.pending') }}" 
                   class="btn-anakt btn-warning {{ $stats['pending_posts'] > 0 ? 'pending-pulse glow-effect' : '' }}">
                    <i class="fas fa-clock me-2"></i>
                    Pending Posts
                    @if($stats['pending_posts'] > 0)
                        <span style="margin-left: 0.5rem; padding: 0.125rem 0.5rem; background: #f59e0b; color: #92400e; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">
                            {{ $stats['pending_posts'] }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.posts.index') }}" class="btn-anakt btn-primary">
                    <i class="fas fa-newspaper me-2"></i>All Posts
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn-anakt btn-info">
                    <i class="fas fa-users me-2"></i>Users
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-5">
            <div class="col-md-3">
                <div class="card stat-card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title" style="margin-bottom: 0; color: rgba(255,255,255,0.8); font-size: 0.875rem;">Total Posts</h6>
                                <h2 style="margin-bottom: 0; color: white; font-size: 2rem; font-weight: 700;">{{ $stats['total_posts'] }}</h2>
                            </div>
                            <i class="fas fa-newspaper fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title" style="margin-bottom: 0; color: rgba(255,255,255,0.8); font-size: 0.875rem;">Approved Posts</h6>
                                <h2 style="margin-bottom: 0; color: white; font-size: 2rem; font-weight: 700;">{{ $stats['approved_posts'] }}</h2>
                            </div>
                            <i class="fas fa-check-circle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title" style="margin-bottom: 0; color: rgba(255,255,255,0.8); font-size: 0.875rem;">Pending Posts</h6>
                                <h2 style="margin-bottom: 0; color: white; font-size: 2rem; font-weight: 700;">{{ $stats['pending_posts'] }}</h2>
                            </div>
                            <i class="fas fa-clock fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card text-white bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title" style="margin-bottom: 0; color: rgba(255,255,255,0.8); font-size: 0.875rem;">Total Users</h6>
                                <h2 style="margin-bottom: 0; color: white; font-size: 2rem; font-weight: 700;">{{ $stats['total_users'] }}</h2>
                            </div>
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Posts -->
            <div class="col-lg-8 mb-4">
                <div class="card anakt-card">
                    <div class="card-header bg-transparent border-bottom-0">
                        <h5 style="margin-bottom: 0; color: white; display: flex; align-items: center;">
                            <i class="fas fa-clock me-2 text-primary"></i>
                            Recent Posts
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($recentPosts->count() > 0)
                            <div class="table-responsive">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentPosts as $post)
                                            <tr>
                                                <td style="color: white; font-weight: 500;">{{ Str::limit($post->title, 30) }}</td>
                                                <td>{{ $post->user->name }}</td>
                                                <td>
                                                    <span class="status-badge badge-{{ $post->status }}">
                                                        {{ ucfirst($post->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $post->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <div style="display: flex; gap: 0.25rem;">
                                                        <a href="{{ route('admin.posts.show', $post) }}" 
                                                           class="action-btn" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if($post->isPending())
                                                            <form action="{{ route('admin.posts.approve', $post) }}" 
                                                                  method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="action-btn" style="color: #10b981;" title="Approve">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-newspaper fa-3x text-muted" style="margin-bottom: 1rem;"></i>
                                <p class="text-muted" style="font-size: 1.125rem;">No posts yet</p>
                                <p style="color: #64748b; font-size: 0.875rem;">Posts will appear here once users start creating content</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pending Posts for Quick Actions -->
            <div class="col-lg-4 mb-4">
                <div class="card anakt-card quick-action-card">
                    <div class="card-header bg-transparent border-bottom-0">
                        <h5 style="margin-bottom: 0; color: white; display: flex; align-items: center;">
                            <i class="fas fa-exclamation-triangle me-2" style="color: #f59e0b;"></i>
                            Pending Approval
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($pendingPosts->count() > 0)
                            <div style="display: flex; flex-direction: column; gap: 1rem;">
                                @foreach($pendingPosts as $post)
                                    <div style="border: 1px solid var(--anakt-border); border-radius: 8px; padding: 1rem; transition: border-color 0.2s ease;">
                                        <div style="margin-bottom: 0.75rem;">
                                            <h6 style="font-weight: 500; color: white; font-size: 0.875rem; line-height: 1.25; margin-bottom: 0.25rem;">
                                                {{ Str::limit($post->title, 25) }}
                                            </h6>
                                            <p style="color: #94a3b8; font-size: 0.75rem; margin: 0.25rem 0;">
                                                by {{ $post->user->name }}
                                            </p>
                                            <p style="color: #64748b; font-size: 0.75rem; margin: 0;">
                                                {{ $post->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <a href="{{ route('admin.posts.show', $post) }}" 
                                               style="flex: 1; padding: 0.5rem 0.75rem; background: #475569; color: white; text-decoration: none; font-size: 0.75rem; border-radius: 4px; text-align: center; transition: background-color 0.2s ease;"
                                               onmouseover="this.style.background='#374151'" 
                                               onmouseout="this.style.background='#475569'">
                                                <i class="fas fa-eye" style="margin-right: 0.25rem;"></i>Review
                                            </a>
                                            <form action="{{ route('admin.posts.approve', $post) }}" 
                                                  method="POST" style="flex: 1;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        style="width: 100%; padding: 0.5rem 0.75rem; background: #059669; color: white; border: none; font-size: 0.75rem; border-radius: 4px; transition: background-color 0.2s ease; cursor: pointer;"
                                                        onmouseover="this.style.background='#047857'" 
                                                        onmouseout="this.style.background='#059669'">
                                                    <i class="fas fa-check" style="margin-right: 0.25rem;"></i>Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.posts.reject', $post) }}" 
                                                  method="POST" style="flex: 1;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        style="width: 100%; padding: 0.5rem 0.75rem; background: #dc2626; color: white; border: none; font-size: 0.75rem; border-radius: 4px; transition: background-color 0.2s ease; cursor: pointer;"
                                                        onmouseover="this.style.background='#b91c1c'" 
                                                        onmouseout="this.style.background='#dc2626'">
                                                    <i class="fas fa-times" style="margin-right: 0.25rem;"></i>Reject
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div style="margin-top: 1.5rem; text-align: center;">
                                <a href="{{ route('admin.posts.pending') }}" 
                                   class="btn-anakt btn-warning">
                                    View All Pending Posts
                                    <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div style="width: 4rem; height: 4rem; background: rgba(16, 185, 129, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                    <i class="fas fa-check-circle fa-3x" style="color: #10b981;"></i>
                                </div>
                                <p style="color: white; font-weight: 500; margin-bottom: 0.25rem;">All caught up!</p>
                                <p style="color: #94a3b8; font-size: 0.875rem; margin: 0;">No posts pending approval</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Add some interactive effects
    document.addEventListener('DOMContentLoaded', function() {
        // Add click effects to stat cards
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-2px)';
                }, 150);
            });
        });

        // Auto-refresh pending count every 30 seconds (optional)
        // setInterval(function() {
        //     // Add AJAX call here if needed
        // }, 30000);
    });
</script>
@endsection