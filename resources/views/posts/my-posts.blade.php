@extends('layouts.app')

@section('title', 'My Profile - ANAKT Blog')

@section('content')
<div class="row">
    <!-- Profile Section -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="py-3">
                    <div class="profile-avatar mb-3">
                        <i class="fas fa-user-circle fa-5x text-white"></i>
                    </div>
                    <h4 class="text-white mb-1">{{ auth()->user()->name }}</h4>
                    <small class="text-light">
                        <i class="fas fa-eye me-1"></i>Garden Observer
                    </small>
                </div>
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-4">
                        <h5 class="text-primary mb-0">{{ $posts->total() }}</h5>
                        <small class="text-muted">Total Posts</small>
                    </div>
                    <div class="col-4">
                        <h5 class="text-success mb-0">{{ $posts->where('status', 'approved')->count() }}</h5>
                        <small class="text-muted">Approved</small>
                    </div>
                    <div class="col-4">
                        <h5 class="text-warning mb-0">{{ $posts->where('status', 'pending')->count() }}</h5>
                        <small class="text-muted">Pending</small>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Observation
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>Return to Garden
                    </a>
                </div>
            </div>
        </div>

        <!-- Status Filter -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filter by Status</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('posts.my') }}" 
                       class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                        <i class="fas fa-list me-1"></i>All Posts
                    </a>
                    <a href="{{ route('posts.my', ['status' => 'approved']) }}" 
                       class="btn {{ request('status') == 'approved' ? 'btn-success' : 'btn-outline-success' }} btn-sm">
                        <i class="fas fa-check-circle me-1"></i>Approved
                    </a>
                    <a href="{{ route('posts.my', ['status' => 'pending']) }}" 
                       class="btn {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }} btn-sm">
                        <i class="fas fa-clock me-1"></i>Pending
                    </a>
                    <a href="{{ route('posts.my', ['status' => 'rejected']) }}" 
                       class="btn {{ request('status') == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }} btn-sm">
                        <i class="fas fa-times-circle me-1"></i>Rejected
                    </a>
                </div>
            </div>
        </div>

        <!-- Observer Info -->
        <div class="card" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border: none;">
            <div class="card-body text-center">
                <i class="fas fa-telescope fa-3x text-warning mb-3"></i>
                <h6 class="text-white mb-2">Observer Since</h6>
                <p class="text-light mb-0">{{ auth()->user()->created_at->format('F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Posts Section -->
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>
                <i class="fas fa-scroll me-2"></i>My Observations
                @if(request('status'))
                    <small class="text-muted">- {{ ucfirst(request('status')) }}</small>
                @endif
            </h3>
            <span class="badge bg-secondary">{{ $posts->total() }} total</span>
        </div>

        @if($posts->count() > 0)
            <div class="row">
                @foreach($posts as $post)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            @if($post->image)
                                <img src="{{ asset('images/posts/' . $post->image) }}" 
                                     class="blog-image" 
                                     alt="{{ $post->title }}">
                            @else
                                <div class="blog-image bg-secondary d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0">{{ Str::limit($post->title, 40) }}</h6>
                                    <span class="badge badge-{{ $post->status }} ms-2">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </div>
                                
                                <p class="card-text flex-grow-1 small">{{ Str::limit($post->content, 80) }}</p>
                                
                                <div class="author-info mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>{{ $post->created_at->format('M d, Y') }}
                                        @if($post->status === 'approved' && $post->approved_at)
                                            <br><i class="fas fa-check-circle me-1 text-success"></i>Published: {{ $post->approved_at->format('M d, Y') }}
                                        @endif
                                    </small>
                                </div>
                                
                                <div class="d-flex gap-1">
                                    <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" 
                                          class="flex-fill"
                                          onsubmit="return confirm('Are you sure you want to delete this observation?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->appends(request()->query())->links() }}
            </div>
        @else
            <div class="card text-center py-5">
                <div class="card-body">
                    @if(request('status'))
                        <i class="fas fa-filter fa-5x text-muted mb-3"></i>
                        <h4>No {{ request('status') }} observations found</h4>
                        <p class="text-muted mb-4">You don't have any {{ request('status') }} posts yet.</p>
                        <a href="{{ route('posts.my') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>View All Posts
                        </a>
                    @else
                        <i class="fas fa-telescope fa-5x text-muted mb-3"></i>
                        <h4>Your observation journal is empty</h4>
                        <p class="text-muted mb-4">Start documenting your thoughts about the Garden's inhabitants!</p>
                        <a href="{{ route('posts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Your First Observation
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Status Legend -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3">
                    <i class="fas fa-info-circle me-2"></i>Observation Status Guide
                </h6>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <span class="badge badge-pending me-2">Pending</span>
                        <small class="text-muted">Under garden review</small>
                    </div>
                    <div class="col-md-3 mb-2">
                        <span class="badge badge-approved me-2">Approved</span>
                        <small class="text-muted">Published in the archive</small>
                    </div>
                    <div class="col-md-3 mb-2">
                        <span class="badge badge-rejected me-2">Rejected</span>
                        <small class="text-muted">Not suitable for archive</small>
                    </div>
                    <div class="col-md-3 mb-2">
                        <small class="text-muted">
                            <i class="fas fa-lightbulb me-1"></i>
                            Tip: Edit rejected posts to resubmit
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-avatar {
    position: relative;
}

.badge-pending {
    background-color: #ffc107;
    color: #000;
}

.badge-approved {
    background-color: #198754;
    color: #fff;
}

.badge-rejected {
    background-color: #dc3545;
    color: #fff;
}

.blog-image {
    height: 150px;
    object-fit: cover;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}
</style>
@endsection