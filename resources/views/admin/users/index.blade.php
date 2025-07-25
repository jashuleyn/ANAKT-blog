@extends('layouts.app')

@section('title', 'Manage Users - ANAKT Blog')

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

    .users-table {
        background: var(--anakt-card);
        border-radius: 12px;
        overflow: hidden;
        width: 100%;
    }

    .users-table th {
        background: var(--anakt-darker);
        color: #e2e8f0;
        font-weight: 600;
        padding: 1rem;
        border-bottom: 1px solid var(--anakt-border);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .users-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--anakt-border);
        color: #cbd5e1;
        vertical-align: middle;
    }

    .users-table tbody tr {
        transition: all 0.2s ease;
    }

    .users-table tbody tr:hover {
        background: rgba(99, 102, 241, 0.05);
        transform: scale(1.002);
    }

    .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        font-size: 1.125rem;
        border: 2px solid var(--anakt-border);
        position: relative;
    }

    .user-avatar.admin {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
    }

    .user-avatar.user {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    .role-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        display: inline-flex;
        align-items: center;
    }

    .role-admin {
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .role-user {
        background: rgba(6, 182, 212, 0.2);
        color: #06b6d4;
        border: 1px solid rgba(6, 182, 212, 0.3);
    }

    .verification-status {
        display: flex;
        align-items: center;
        font-size: 0.875rem;
    }

    .verification-status.verified {
        color: var(--anakt-success);
    }

    .verification-status.unverified {
        color: var(--anakt-warning);
    }

    .posts-count {
        background: var(--anakt-darker);
        color: #e2e8f0;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        text-align: center;
        min-width: 60px;
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
    }

    .action-button.view {
        border-color: var(--anakt-primary);
        background: rgba(99, 102, 241, 0.1);
    }
    .action-button.view:hover {
        background: var(--anakt-primary);
        color: white;
    }

    .action-button.delete {
        border-color: var(--anakt-danger);
        background: rgba(239, 68, 68, 0.1);
    }
    .action-button.delete:hover {
        background: var(--anakt-danger);
        color: white;
    }

    .action-button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .stat-card {
        background: var(--anakt-card);
        border: 1px solid var(--anakt-border);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
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
    }

    .stat-card.users {
        color: var(--anakt-accent);
    }

    .stat-card.verified {
        color: var(--anakt-success);
    }

    .stat-card.posts {
        color: var(--anakt-primary);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
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

    .mb-8 {
        margin-bottom: 2rem;
    }

    .me-2 {
        margin-right: 0.5rem;
    }

    .text-center {
        text-align: center;
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
        
        .stats-grid {
            grid-template-columns: 1fr;
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
                <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: linear-gradient(135deg, #06b6d4, #0891b2); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users text-white"></i>
                </div>
                <div>
                    <h1 style="margin: 0; color: white; font-size: 1.875rem; font-weight: 700;">Manage Users</h1>
                    <p style="margin: 0; color: #94a3b8; font-size: 0.875rem;">Monitor and manage community members</p>
                </div>
            </div>
            
            <div class="btn-group">
                <a href="{{ route('admin.dashboard') }}" class="btn-anakt btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Dashboard
                </a>
            </div>
        </div>

        @if($users->count() > 0)
            <!-- Users Table -->
            <div class="anakt-card mb-8">
                <div class="table-responsive">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th style="text-align: left;">User</th>
                                <th style="text-align: left;">Contact & Verification</th>
                                <th style="text-align: center; width: 100px;">Posts</th>
                                <th style="text-align: left; width: 150px;">Joined</th>
                                <th style="text-align: center; width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <div class="user-avatar {{ $user->role }}" title="{{ ucfirst($user->role) }}">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h3 style="font-weight: 600; color: white; margin: 0 0 0.25rem 0;">{{ $user->name }}</h3>
                                                <span class="role-badge role-{{ $user->role }}">
                                                    @if($user->isAdmin())
                                                        <i class="fas fa-crown" style="margin-right: 0.25rem;"></i>
                                                    @else
                                                        <i class="fas fa-user" style="margin-right: 0.25rem;"></i>
                                                    @endif
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <p style="color: white; font-weight: 500; margin: 0 0 0.25rem 0;">{{ $user->email }}</p>
                                            <div class="verification-status {{ $user->email_verified_at ? 'verified' : 'unverified' }}">
                                                @if($user->email_verified_at)
                                                    <i class="fas fa-check-circle" style="margin-right: 0.25rem;"></i>
                                                    <span>Email Verified</span>
                                                @else
                                                    <i class="fas fa-exclamation-triangle" style="margin-right: 0.25rem;"></i>
                                                    <span>Unverified Email</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; justify-content: center;">
                                            <div class="posts-count">
                                                <div style="font-size: 1.125rem; font-weight: 700;">{{ $user->posts_count }}</div>
                                                <div style="font-size: 0.75rem; color: #94a3b8;">posts</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <p style="color: white; font-weight: 500; margin: 0 0 0.25rem 0;">{{ $user->created_at->format('M d, Y') }}</p>
                                            <p style="color: #94a3b8; font-size: 0.875rem; margin: 0;">{{ $user->created_at->diffForHumans() }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; justify-content: center; gap: 0.25rem;">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="action-button view" 
                                               title="View Profile">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if(!$user->isAdmin())
                                                <form action="{{ route('admin.users.destroy', $user) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="action-button delete" 
                                                            title="Delete User"
                                                            onclick="return confirm('Are you sure you want to delete this user and all their posts ({{ $user->posts_count }})? This action cannot be undone.')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="action-button" disabled title="Cannot delete admin">
                                                    <i class="fas fa-shield-alt"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
                <div style="display: flex; justify-content: center; margin-bottom: 2rem;">
                    <div style="background: #475569; border-radius: 8px; padding: 1rem;">
                        {{ $users->links() }}
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="anakt-card mb-8">
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h3 style="font-size: 1.25rem; font-weight: 600; color: white; margin-bottom: 0.5rem;">No users found</h3>
                    <p style="color: #94a3b8; margin-bottom: 0.5rem;">No regular users have joined the community yet.</p>
                    <p style="color: #64748b; font-size: 0.875rem;">User profiles will appear here once people start registering.</p>
                </div>
            </div>
        @endif

        <!-- User Statistics -->
        <div class="stats-grid">
            <div class="stat-card users">
                <div class="stat-icon" style="background: rgba(6, 182, 212, 0.2);">
                    <i class="fas fa-users" style="color: #06b6d4;"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 0.25rem;">{{ $users->total() }}</h3>
                <p style="color: #94a3b8; font-weight: 500; margin-bottom: 0.25rem;">Total Users</p>
                <p style="color: #64748b; font-size: 0.875rem; margin: 0;">Registered community members</p>
            </div>

            <div class="stat-card verified">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.2);">
                    <i class="fas fa-user-check" style="color: #10b981;"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 0.25rem;">{{ $users->where('email_verified_at', '!=', null)->count() }}</h3>
                <p style="color: #94a3b8; font-weight: 500; margin-bottom: 0.25rem;">Verified Users</p>
                <p style="color: #64748b; font-size: 0.875rem; margin: 0;">Email verified accounts</p>
            </div>

            <div class="stat-card posts">
                <div class="stat-icon" style="background: rgba(99, 102, 241, 0.2);">
                    <i class="fas fa-newspaper" style="color: #6366f1;"></i>
                </div>
                <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 0.25rem;">{{ $users->sum('posts_count') }}</h3>
                <p style="color: #94a3b8; font-weight: 500; margin-bottom: 0.25rem;">Total Posts</p>
                <p style="color: #64748b; font-size: 0.875rem; margin: 0;">Created by all users</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Add loading state to delete forms
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

        // Add hover effects to user avatars
        const avatars = document.querySelectorAll('.user-avatar');
        avatars.forEach(avatar => {
            avatar.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1)';
            });
            avatar.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });

        // Add click animation to stat cards
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });
    });
</script>
@endsection