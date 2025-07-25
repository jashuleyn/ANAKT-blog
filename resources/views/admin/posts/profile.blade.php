@extends('layouts.app')

@section('title', 'My Profile - ANAKT Blog')

@section('content')
<div class="container">
    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center p-4">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width: 100px; height: 100px;">
                        <i class="fas fa-user fa-3x text-white"></i>
                    </div>
                    
                    <h4>{{ auth()->user()->name }}</h4>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <h5 class="text-primary mb-0">{{ auth()->user()->posts()->count() }}</h5>
                            <small class="text-muted">Total Posts</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-success mb-0">{{ auth()->user()->posts()->approved()->count() }}</h5>
                            <small class="text-muted">Published</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-warning mb-0">{{ auth()->user()->posts()->pending()->count() }}</h5>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                    
                    <div class="border-top pt-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            Member since {{ auth()->user()->created_at->format('F Y') }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('posts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Write New Post
                        </a>
                        <a href="#my-posts" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>View All My Posts
                        </a>
                        <a href="{{ route('blogs') }}" class="btn btn-outline-info">
                            <i class="fas fa-globe me-2"></i>Browse All Posts
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts Section -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 id="my-posts"><i class="fas fa-newspaper me-2"></i>My Posts</h3>
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>New Post
                </a>
            </div>

            <!-- Filter Tabs -->
            <ul class="nav nav-pills mb-4">
                <li class="nav-item">
                    <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('profile') }}">
                        All ({{ auth()->user()->posts()->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'approved' ? 'active' : '' }}" 
                       href="{{ route('profile', ['status' => 'approved']) }}">
                        Published ({{ auth()->user()->posts()->approved()->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'pending' ? 'active' : '' }}" 
                       href="{{ route('profile', ['status' => 'pending']) }}">
                        Pending ({{ auth()->user()->posts()->pending()->count() }})
                    </a>
                </li>
            </ul>

            @if($posts->count() > 0)
                @foreach($posts as $post)
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0">{{ $post->title }}</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge badge-{{ $post->status }} fs-6">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" 
                                                type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('posts.destroy', $post) }}" method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to delete this post?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="fas fa-trash me-2"></i>Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="card-text text-muted">{{ Str::limit($post->content, 200) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    Created {{ $post->created_at->format('M d, Y') }}
                                    @if($post->status === 'approved' && $post->approved_at)
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-check-circle me-1 text-success"></i>
                                        Published {{ $post->approved_at->format('M d, Y') }}
                                    @endif
                                </small>
                                
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $posts->appends(request()->query())->links() }}
                </div>
            @else
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-newspaper fa-5x text-muted mb-3"></i>
                        @if(request('status'))
                            <h3>No {{ request('status') }} posts</h3>
                            <p class="text-muted">You don't have any {{ request('status') }} posts yet.</p>
                        @else
                            <h3>No posts yet</h3>
                            <p class="text-muted">You haven't created any posts yet. Share your thoughts about Alien Stage!</p>
                        @endif
                        <a href="{{ route('posts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Your First Post
                        </a>
                    </div>
                </div>
            @endif

            <!-- Status Legend -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="fas fa-info-circle me-2"></i>Post Status Guide
                    </h6>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <span class="badge badge-pending me-2">Pending</span>
                            <small class="text-muted">Awaiting admin approval</small>
                        </div>
                        <div class="col-md-4 mb-2">
                            <span class="badge badge-approved me-2">Approved</span>
                            <small class="text-muted">Live and visible to all users</small>
                        </div>
                        <div class="col-md-4 mb-2">
                            <span class="badge badge-rejected me-2">Rejected</span>
                            <small class="text-muted">Not approved for publication</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection