@extends('layouts.app')

@section('title', 'Pending Posts - ANAKT Blog')

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

    .cosmic-bg {
        background: radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 40% 80%, rgba(6, 182, 212, 0.1) 0%, transparent 50%);
        min-height: 100vh;
    }

    .pending-glow {
        box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
        animation: pulse 2s infinite;
    }

    .anakt-card {
        background: var(--anakt-card);
        border: 1px solid var(--anakt-border);
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }

    .pending-table {
        background: var(--anakt-card);
        border-radius: 12px;
        overflow: hidden;
        width: 100%;
    }

    .pending-table th {
        background: var(--anakt-darker);
        color: #e2e8f0;
        font-weight: 600;
        padding: 1rem;
        border-bottom: 1px solid var(--anakt-border);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .pending-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--anakt-border);
        color: #cbd5e1;
        vertical-align: top;
    }

    .pending-table tbody tr {
        transition: all 0.2s ease;
        border-left: 3px solid var(--anakt-warning);
    }

    .pending-table tbody tr:hover {
        background: rgba(245, 158, 11, 0.05);
        transform: scale(1.002);
    }

    .post-image {
        width: 60px;
        height: 45px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid var(--anakt-warning);
    }

    .post-placeholder {
        width: 60px;
        height: 45px;
        border-radius: 8px;
        background: var(--anakt-darker);
        border: 2px solid var(--anakt-warning);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pending-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
        display: inline-flex;
        align-items: center;
        animation: pulse 2s infinite;
    }

    .action-button {
        padding: 0.5rem;
        border-radius: 6px;
        border: 1px solid transparent;
        background: rgba(71, 85, 105, 0.5);
        color: #cbd5e1;
        transition: all 0.2s ease;
        margin: 0 2px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        cursor: pointer;
    }

    .action-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .action-button.view { 
        border-color: var(--anakt-primary); 
        background: rgba(99, 102, 241, 0.1); 
    }
    .action-button.view:hover { 
        background: var(--anakt-primary); 
        color: white; 
    }

    .action-button.approve { 
        border-color: var(--anakt-success); 
        background: rgba(16, 185, 129, 0.1); 
    }
    .action-button.approve:hover { 
        background: var(--anakt-success); 
        color: white; 
    }

    .action-button.reject { 
        border-color: var(--anakt-danger); 
        background: rgba(239, 68, 68, 0.1); 
    }
    .action-button.reject:hover { 
        background: var(--anakt-danger); 
        color: white; 
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #64748b;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
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
        cursor: pointer;
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

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
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

    .me-2 {
        margin-right: 0.5rem;
    }

    .text-center {
        text-align: center;
    }

    .py-5 {
        padding-top: 3rem;
        padding-bottom: 3rem;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .btn-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
        
        .btn-group {
            width: 100%;
        }
        
        .btn-anakt {
            flex: 1;
            justify-content: center;
        }
    }
</style>

<div class="cosmic-bg">
    <div class="container">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: linear-gradient(135deg, #f59e0b, #d97706); display: flex; align-items: center; justify-content: center;" class="pending-glow">
                    <i class="fas fa-clock text-white"></i>
                </div>
                <div>
                    <h1 style="margin: 0; color: white; font-size: 1.875rem; font-weight: 700;">Pending Posts</h1>
                    <p style="margin: 0; color: #94a3b8; font-size: 0.875rem;">Posts awaiting approval</p>
                </div>
            </div>
            
            <div class="btn-group">
                <a href="{{ route('admin.dashboard') }}" class="btn-anakt btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Dashboard
                </a>
                <a href="{{ route('admin.posts.index') }}" class="btn-anakt btn-info">
                    <i class="fas fa-list me-2"></i>All Posts
                </a>
            </div>
        </div>

        @if($posts->count() > 0)
            <!-- Pending Posts Table -->
            <div class="anakt-card">
                <div class="table-responsive">
                    <table class="pending-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Image</th>
                                <th>Title & Content</th>
                                <th style="width: 150px;">Author</th>
                                <th style="width: 120px;">Submitted</th>
                                <th style="width: 200px; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                                <tr>
                                    <td>
                                        @if($post->image)
                                            <img src="{{ asset('images/posts/' . $post->image) }}" 
                                                 class="post-image"
                                                 alt="{{ $post->title }}"
                                                 loading="lazy">
                                        @else
                                            <div class="post-placeholder">
                                                <i class="fas fa-image" style="color: #f59e0b;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="max-width: 400px;">
                                            <h3 style="font-weight: 600; color: white; font-size: 0.875rem; margin-bottom: 0.5rem; line-height: 1.25;">
                                                {{ Str::limit($post->title, 50) }}
                                            </h3>
                                            <p style="color: #94a3b8; font-size: 0.75rem; line-height: 1.4; margin-bottom: 0.5rem;">
                                                {{ Str::limit(strip_tags($post->content), 100) }}
                                            </p>
                                            <span class="pending-badge">
                                                <i class="fas fa-clock" style="margin-right: 0.25rem;"></i>
                                                Pending Review
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                <span style="color: white; font-size: 0.75rem; font-weight: 700;">
                                                    {{ substr($post->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p style="color: white; font-size: 0.875rem; font-weight: 500; margin: 0;">{{ $post->user->name }}</p>
                                                <p style="color: #94a3b8; font-size: 0.75rem; margin: 0;">{{ $post->user->posts()->count() }} posts</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size: 0.875rem;">
                                            <p style="color: white; font-weight: 500; margin-bottom: 0.25rem;">{{ $post->created_at->format('M d, Y') }}</p>
                                            <p style="color: #94a3b8; font-size: 0.75rem; margin-bottom: 0.25rem;">{{ $post->created_at->format('h:i A') }}</p>
                                            <p style="color: #64748b; font-size: 0.75rem; margin: 0;">{{ $post->created_at->diffForHumans() }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; justify-content: center; gap: 0.25rem;">
                                            <a href="{{ route('admin.posts.show', $post) }}" 
                                               class="action-button view" 
                                               title="Review Post">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <form action="{{ route('admin.posts.approve', $post) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="action-button approve" 
                                                        title="Approve Post"
                                                        onclick="return confirm('Approve this post?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('admin.posts.reject', $post) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="action-button reject" 
                                                        title="Reject Post"
                                                        onclick="return confirm('Reject this post?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
                <div style="margin-top: 2rem; display: flex; justify-content: center;">
                    <div style="background: #475569; border-radius: 8px; padding: 1rem;">
                        {{ $posts->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="anakt-card">
                <div class="empty-state">
                    <div style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                        <i class="fas fa-check-circle" style="font-size: 2.5rem; color: #10b981;"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: white; margin-bottom: 0.5rem;">All caught up!</h3>
                    <p style="color: #94a3b8; margin-bottom: 0.5rem;">No posts are currently pending approval.</p>
                    <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 1.5rem;">New submissions will appear here for review.</p>
                    <div>
                        <a href="{{ route('admin.posts.index') }}" class="btn-anakt btn-info">
                            <i class="fas fa-list me-2"></i>
                            View All Posts
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    // Add loading states to forms
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form[method="POST"]');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const button = this.querySelector('button[type="submit"]');
                if (button) {
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                }
            });
        });

        // Add hover effects to rows
        const rows = document.querySelectorAll('.pending-table tbody tr');
        rows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.borderLeftWidth = '5px';
            });
            row.addEventListener('mouseleave', function() {
                this.style.borderLeftWidth = '3px';
            });
        });
    });
</script>
@endsection