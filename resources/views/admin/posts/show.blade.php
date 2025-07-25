@extends('layouts.app')

@section('title', 'Review Post - ANAKT Blog')

@push('styles')
<style>
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

    .anakt-card {
        background: var(--anakt-card);
        border: 1px solid var(--anakt-border);
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }

    .cosmic-bg {
        background: radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 40% 80%, rgba(6, 182, 212, 0.1) 0%, transparent 50%);
    }

    .post-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 12px 12px 0 0;
        border-bottom: 1px solid var(--anakt-border);
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        display: inline-flex;
        align-items: center;
    }

    .status-approved { 
        background: rgba(16, 185, 129, 0.2); 
        color: #10b981; 
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .status-pending { 
        background: rgba(245, 158, 11, 0.2); 
        color: #f59e0b; 
        border: 1px solid rgba(245, 158, 11, 0.3);
        animation: pulse 2s infinite;
    }
    .status-rejected { 
        background: rgba(239, 68, 68, 0.2); 
        color: #ef4444; 
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .action-button {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        border: 1px solid transparent;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .action-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .action-button.primary {
        background: var(--anakt-primary);
        border-color: var(--anakt-primary);
        color: white;
    }
    .action-button.primary:hover {
        background: #5855eb;
    }

    .action-button.success {
        background: var(--anakt-success);
        border-color: var(--anakt-success);
        color: white;
    }
    .action-button.success:hover {
        background: #059669;
    }

    .action-button.warning {
        background: var(--anakt-warning);
        border-color: var(--anakt-warning);
        color: white;
    }
    .action-button.warning:hover {
        background: #d97706;
    }

    .action-button.danger {
        background: var(--anakt-danger);
        border-color: var(--anakt-danger);
        color: white;
    }
    .action-button.danger:hover {
        background: #dc2626;
    }

    .action-button.secondary {
        background: transparent;
        border-color: var(--anakt-border);
        color: #cbd5e1;
    }
    .action-button.secondary:hover {
        background: rgba(71, 85, 105, 0.3);
        border-color: var(--anakt-primary);
        color: var(--anakt-primary);
    }

    .alert {
        padding: 1rem 1.25rem;
        border-radius: 8px;
        border: 1px solid;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }

    .alert.success {
        background: rgba(16, 185, 129, 0.1);
        border-color: rgba(16, 185, 129, 0.2);
        color: #10b981;
    }

    .alert.warning {
        background: rgba(245, 158, 11, 0.1);
        border-color: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
    }

    .alert.danger {
        background: rgba(239, 68, 68, 0.1);
        border-color: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .author-card {
        background: var(--anakt-darker);
        border: 1px solid var(--anakt-border);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .author-avatar {
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
    }

    .author-avatar.admin {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .author-avatar.user {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    .role-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
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

    .content-viewer {
        background: var(--anakt-darker);
        border: 1px solid var(--anakt-border);
        border-radius: 8px;
        padding: 1.5rem;
        color: #e2e8f0;
        line-height: 1.7;
        font-size: 0.95rem;
        max-height: 400px;
        overflow-y: auto;
    }

    .meta-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        color: #cbd5e1;
        font-size: 0.875rem;
    }

    .meta-item i {
        margin-right: 0.5rem;
        color: var(--anakt-primary);
        width: 16px;
    }

    .section-title {
        color: #e2e8f0;
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 0.5rem;
        color: var(--anakt-primary);
    }

    .divider {
        height: 1px;
        background: var(--anakt-border);
        margin: 1.5rem 0;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        color: #cbd5e1;
        text-decoration: none;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
        transition: color 0.2s ease;
    }

    .back-button:hover {
        color: var(--anakt-primary);
    }

    .back-button i {
        margin-right: 0.5rem;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    /* Custom Modal Styles */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(4px);
        z-index: 50;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: var(--anakt-card);
        border: 1px solid var(--anakt-border);
        border-radius: 12px;
        padding: 1.5rem;
        max-width: 450px;
        width: 90%;
    }
</style>
@endpush

@section('content')
<div class="cosmic-bg min-h-screen p-6">
    <!-- Back Navigation -->
    <a href="{{ route('admin.posts.index') }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
        Back to Posts Management
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Post Content -->
        <div class="lg:col-span-2">
            <div class="anakt-card overflow-hidden">
                @if($post->image)
                    <img src="{{ asset('images/posts/' . $post->image) }}" 
                         class="post-image"
                         alt="{{ $post->title }}">
                @endif
                
                <div class="p-6">
                    <!-- Status and Actions Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        <span class="status-badge status-{{ $post->status }} mb-3 sm:mb-0">
                            @if($post->isPending())
                                <i class="fas fa-clock mr-2"></i>
                            @elseif($post->isApproved())
                                <i class="fas fa-check mr-2"></i>
                            @else
                                <i class="fas fa-times mr-2"></i>
                            @endif
                            {{ ucfirst($post->status) }}
                        </span>
                    </div>
                    
                    <!-- Post Title -->
                    <h1 class="text-3xl font-bold text-white mb-6 leading-tight">{{ $post->title }}</h1>
                    
                    <!-- Meta Information -->
                    <div class="meta-info">
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span><strong>Author:</strong> {{ $post->user->name }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-envelope"></i>
                            <span><strong>Email:</strong> {{ $post->user->email }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span><strong>Created:</strong> {{ $post->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        @if($post->approved_at)
                            <div class="meta-item">
                                <i class="fas fa-check-circle text-green-400"></i>
                                <span><strong>Approved:</strong> {{ $post->approved_at->format('M d, Y h:i A') }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="divider"></div>
                    
                    <!-- Post Content -->
                    <div>
                        <h2 class="section-title">
                            <i class="fas fa-file-alt"></i>
                            Post Content
                        </h2>
                        <div class="content-viewer">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Admin Actions -->
            <div class="anakt-card">
                <div class="p-6">
                    <h2 class="section-title">
                        <i class="fas fa-cogs"></i>
                        Admin Actions
                    </h2>
                    
                    @if($post->isPending())
                        <div class="alert warning">
                            <i class="fas fa-clock mr-3"></i>
                            <span>This post is awaiting your approval.</span>
                        </div>
                        
                        <div class="space-y-3">
                            <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-button success w-full">
                                    <i class="fas fa-check mr-2"></i>Approve Post
                                </button>
                            </form>
                            
                            <button onclick="showRejectModal()" class="action-button warning w-full">
                                <i class="fas fa-times mr-2"></i>Reject Post
                            </button>
                        </div>
                    @elseif($post->isApproved())
                        <div class="alert success">
                            <i class="fas fa-check-circle mr-3"></i>
                            <span>This post is approved and live.</span>
                        </div>
                        
                        <div class="space-y-3">
                            <button onclick="showRejectModal()" class="action-button warning w-full">
                                <i class="fas fa-times mr-2"></i>Reject Post
                            </button>
                        </div>
                    @elseif($post->isRejected())
                        <div class="alert danger">
                            <i class="fas fa-times-circle mr-3"></i>
                            <span>This post has been rejected.</span>
        </div>
                        
                        <div class="space-y-3">
                            <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-button success w-full">
                                    <i class="fas fa-check mr-2"></i>Approve Post
                                </button>
                            </form>
                        </div>
                    @endif
                    
                    <div class="divider"></div>
                    
                    <div class="space-y-3">
                        <a href="{{ route('posts.show', $post) }}" 
                           class="action-button secondary w-full" target="_blank">
                            <i class="fas fa-external-link-alt mr-2"></i>View as User
                        </a>
                        
                        <button onclick="showDeleteModal()" class="action-button danger w-full">
                            <i class="fas fa-trash mr-2"></i>Delete Post
                        </button>
                    </div>
                </div>
            </div>

            <!-- Author Information -->
            <div class="author-card">
                <h3 class="section-title">
                    <i class="fas fa-user"></i>
                    Author Information
                </h3>
                
                <div class="flex items-start space-x-4">
                    <div class="author-avatar {{ $post->user->role }}">
                        {{ substr($post->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-white font-semibold mb-1">{{ $post->user->name }}</h4>
                        <p class="text-slate-400 text-sm mb-2">{{ $post->user->email }}</p>
                        <span class="role-badge role-{{ $post->user->role }}">
                            @if($post->user->isAdmin())
                                <i class="fas fa-crown mr-1"></i>
                            @else
                                <i class="fas fa-user mr-1"></i>
                            @endif
                            {{ ucfirst($post->user->role) }}
                        </span>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-slate-700">
                    <div class="text-sm text-slate-400 space-y-2">
                        <div class="flex justify-between">
                            <span>Member since:</span>
                            <span class="text-white">{{ $post->user->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total posts:</span>
                            <span class="text-white">{{ $post->user->posts()->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Email verified:</span>
                            <span class="text-white">
                                @if($post->user->email_verified_at)
                                    <i class="fas fa-check text-green-400"></i> Yes
                                @else
                                    <i class="fas fa-times text-red-400"></i> No
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('admin.users.show', $post->user) }}" 
                       class="action-button primary w-full">
                        <i class="fas fa-eye mr-2"></i>View User Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Post Modal -->
<div id="rejectModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-amber-400 mb-2 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>Reject Post
            </h3>
            <p class="text-slate-400 text-sm">Are you sure you want to reject "{{ $post->title }}"?</p>
            @if($post->isApproved())
                <p class="text-amber-400 text-sm mt-2 font-medium">This will remove the post from public view.</p>
            @endif
        </div>
        <div class="flex space-x-3">
            <button onclick="hideRejectModal()" 
                    class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors">
                Cancel
            </button>
            <form action="{{ route('admin.posts.reject', $post) }}" method="POST" class="flex-1">
                @csrf
                @method('PATCH')
                <button type="submit" 
                        class="w-full px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-lg transition-colors">
                    Reject Post
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Delete Post Modal -->
<div id="deleteModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-red-400 mb-2 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>Delete Post
            </h3>
            <p class="text-slate-400 text-sm">Are you sure you want to permanently delete "{{ $post->title }}"?</p>
            <p class="text-red-400 text-sm mt-2 font-medium">This action cannot be undone.</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="hideDeleteModal()" 
                    class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors">
                Cancel
            </button>
            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg transition-colors">
                    Delete Forever
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Modal functions
    function showRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    function showDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function hideDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            hideRejectModal();
            hideDeleteModal();
        }
    });

    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideRejectModal();
            hideDeleteModal();
        }
    });

    // Add loading states to forms
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const button = this.querySelector('button[type="submit"]');
                if (button) {
                    button.disabled = true;
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                    
                    // Re-enable after 5 seconds as fallback
                    setTimeout(() => {
                        button.disabled = false;
                        button.innerHTML = originalText;
                    }, 5000);
                }
            });
        });

        // Add smooth scrolling for content viewer
        const contentViewer = document.querySelector('.content-viewer');
        if (contentViewer && contentViewer.scrollHeight > contentViewer.clientHeight) {
            contentViewer.style.borderBottom = '2px solid var(--anakt-primary)';
        }
    });
</script>
@endpush