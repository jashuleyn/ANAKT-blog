@extends('layouts.app')

@section('title', 'Manage Posts - ANAKT Blog')

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

    .anakt-card {
        background: var(--anakt-card);
        border: 1px solid var(--anakt-border);
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }

    .posts-table {
        background: var(--anakt-card);
        border-radius: 12px;
        overflow: hidden;
        width: 100%;
    }

    .posts-table th {
        background: var(--anakt-darker);
        color: #e2e8f0;
        font-weight: 600;
        padding: 1rem;
        border-bottom: 1px solid var(--anakt-border);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .posts-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--anakt-border);
        color: #cbd5e1;
        vertical-align: top;
    }

    .posts-table tbody tr {
        transition: all 0.2s ease;
    }

    .posts-table tbody tr:hover {
        background: rgba(99, 102, 241, 0.05);
        transform: scale(1.002);
    }

    .post-image {
        width: 60px;
        height: 45px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid var(--anakt-border);
    }

    .post-placeholder {
        width: 60px;
        height: 45px;
        border-radius: 8px;
        background: var(--anakt-darker);
        border: 2px solid var(--anakt-border);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        display: inline-flex;
        align-items: center;
    }

    .badge-approved { 
        background: rgba(16, 185, 129, 0.2); 
        color: #10b981; 
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .badge-pending { 
        background: rgba(245, 158, 11, 0.2); 
        color: #f59e0b; 
        border: 1px solid rgba(245, 158, 11, 0.3);
        animation: pulse 2s infinite;
    }
    .badge-rejected { 
        background: rgba(239, 68, 68, 0.2); 
        color: #ef4444; 
        border: 1px solid rgba(239, 68, 68, 0.3);
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

    .action-button.view { border-color: var(--anakt-primary); background: rgba(99, 102, 241, 0.1); }
    .action-button.view:hover { background: var(--anakt-primary); color: white; }

    .action-button.approve { border-color: var(--anakt-success); background: rgba(16, 185, 129, 0.1); }
    .action-button.approve:hover { background: var(--anakt-success); color: white; }

    .action-button.reject { border-color: var(--anakt-warning); background: rgba(245, 158, 11, 0.1); }
    .action-button.reject:hover { background: var(--anakt-warning); color: white; }

    .action-button.delete { border-color: var(--anakt-danger); background: rgba(239, 68, 68, 0.1); }
    .action-button.delete:hover { background: var(--anakt-danger); color: white; }

    .filter-tabs {
        display: flex;
        background: var(--anakt-darker);
        border-radius: 10px;
        padding: 4px;
        margin-bottom: 1.5rem;
        border: 1px solid var(--anakt-border);
    }

    .filter-tab {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        color: #cbd5e1;
        transition: all 0.2s ease;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        background: transparent;
    }

    .filter-tab.active {
        background: var(--anakt-primary);
        color: white;
    }

    .filter-tab:hover:not(.active) {
        background: rgba(99, 102, 241, 0.1);
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

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
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
                <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-newspaper text-white"></i>
                </div>
                <div>
                    <h1 style="margin: 0; color: white; font-size: 1.875rem; font-weight: 700;">All Blog Posts</h1>
                    <p style="margin: 0; color: #94a3b8; font-size: 0.875rem;">Manage and moderate content</p>
                </div>
            </div>
            
            <div class="btn-group">
                <a href="{{ route('admin.dashboard') }}" class="btn-anakt btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Dashboard
                </a>
                <a href="{{ route('admin.posts.pending') }}" class="btn-anakt btn-warning">
                    <i class="fas fa-clock me-2"></i>Pending Posts
                </a>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="filter-tab active" onclick="filterPosts('all')">
                <i class="fas fa-list me-2"></i>All Posts
            </button>
            <button class="filter-tab" onclick="filterPosts('approved')">
                <i class="fas fa-check-circle me-2"></i>Approved
            </button>
            <button class="filter-tab" onclick="filterPosts('pending')">
                <i class="fas fa-clock me-2"></i>Pending
            </button>
            <button class="filter-tab" onclick="filterPosts('rejected')">
                <i class="fas fa-times-circle me-2"></i>Rejected
            </button>
        </div>

        @if($posts->count() > 0)
            <!-- Posts Table -->
            <div class="anakt-card">
                <div class="table-responsive">
                    <table class="posts-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Image</th>
                                <th>Title & Content</th>
                                <th style="width: 150px;">Author</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 120px;">Created</th>
                                <th style="width: 200px; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="postsTable">
                            @foreach($posts as $post)
                                <tr data-status="{{ $post->status }}" class="post-row">
                                    <td>
                                        @if($post->image)
                                            <img src="{{ asset('images/posts/' . $post->image) }}" 
                                                 class="post-image"
                                                 alt="{{ $post->title }}"
                                                 loading="lazy">
                                        @else
                                            <div class="post-placeholder">
                                                <i class="fas fa-image text-slate-500"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="max-width: 400px;">
                                            <h3 style="font-weight: 600; color: white; font-size: 0.875rem; margin-bottom: 0.5rem; line-height: 1.25;">
                                                {{ Str::limit($post->title, 45) }}
                                            </h3>
                                            <p style="color: #94a3b8; font-size: 0.75rem; line-height: 1.4;">
                                                {{ Str::limit(strip_tags($post->content), 80) }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                <span style="color: white; font-size: 0.75rem; font-weight: 700;">
                                                    {{ substr($post->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p style="color: white; font-size: 0.875rem; font-weight: 500; margin: 0;">{{ $post->user->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge badge-{{ $post->status }}">
                                            @if($post->isPending())
                                                <i class="fas fa-clock" style="margin-right: 0.25rem;"></i>
                                            @elseif($post->isApproved())
                                                <i class="fas fa-check" style="margin-right: 0.25rem;"></i>
                                            @else
                                                <i class="fas fa-times" style="margin-right: 0.25rem;"></i>
                                            @endif
                                            {{ ucfirst($post->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="font-size: 0.875rem;">
                                            <p style="color: white; font-weight: 500; margin-bottom: 0.25rem;">{{ $post->created_at->format('M d, Y') }}</p>
                                            <p style="color: #94a3b8; font-size: 0.75rem; margin: 0;">{{ $post->created_at->format('h:i A') }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; justify-content: center; gap: 0.25rem;">
                                            <a href="{{ route('admin.posts.show', $post) }}" 
                                               class="action-button view" 
                                               title="View Post">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($post->isPending())
                                                <form action="{{ route('admin.posts.approve', $post) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="action-button approve" 
                                                            title="Approve Post">
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
                                                            onclick="return confirm('Are you sure you want to reject this post?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @elseif($post->isApproved())
                                                <form action="{{ route('admin.posts.reject', $post) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="action-button reject" 
                                                            title="Reject Post"
                                                            onclick="return confirm('Are you sure you want to reject this approved post?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @elseif($post->isRejected())
                                                <form action="{{ route('admin.posts.approve', $post) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="action-button approve" 
                                                            title="Approve Post">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('admin.posts.destroy', $post) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="action-button delete" 
                                                        title="Delete Post"
                                                        onclick="return confirm('Are you sure you want to delete this post permanently? This action cannot be undone.')">
                                                    <i class="fas fa-trash"></i>
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
                    <i class="fas fa-newspaper"></i>
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: white; margin-bottom: 0.5rem;">No posts found</h3>
                    <p style="color: #94a3b8; margin-bottom: 0.5rem;">There are no blog posts to manage yet.</p>
                    <p style="color: #64748b; font-size: 0.875rem;">Posts will appear here once users start creating content.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    // Filter posts functionality
    function filterPosts(status) {
        const rows = document.querySelectorAll('.post-row');
        const tabs = document.querySelectorAll('.filter-tab');
        
        // Update active tab
        tabs.forEach(tab => tab.classList.remove('active'));
        event.target.classList.add('active');
        
        // Filter rows
        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

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
    });
</script>
@endsection