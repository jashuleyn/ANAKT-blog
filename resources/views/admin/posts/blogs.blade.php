@extends('layouts.app')

@section('title', 'All Blogs - ANAKT Blog')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-newspaper me-2"></i>All Blog Posts</h2>
                <small class="text-muted">{{ $posts->total() }} posts found</small>
            </div>

            <!-- Search & Filter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('blogs') }}" method="GET" class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search posts by title or content...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="sort" class="form-select">
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest First</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Title A-Z</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i>Filter
                                </button>
                                <a href="{{ route('blogs') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Posts Grid -->
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
                                    <div class="blog-image bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $post->title }}</h5>
                                    <p class="card-text flex-grow-1">{{ Str::limit($post->content, 150) }}</p>
                                    
                                    <div class="author-info mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>By {{ $post->user->name }}
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-calendar me-1"></i>{{ $post->created_at->format('M d, Y') }}
                                        </small>
                                    </div>
                                    
                                    <a href="{{ route('posts.show', $post) }}" class="btn btn-primary">
                                        Read More <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
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
                        <i class="fas fa-search fa-5x text-muted mb-3"></i>
                        <h3>No posts found</h3>
                        @if(request('search'))
                            <p class="text-muted">No posts match your search for "{{ request('search') }}"</p>
                            <a href="{{ route('blogs') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>View All Posts
                            </a>
                        @else
                            <p class="text-muted">There are no blog posts yet.</p>
                            @auth
                                @if(!auth()->user()->isAdmin())
                                    <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create First Post
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Community Stats</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ $totalPosts }}</h4>
                                <small class="text-muted">Total Posts</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-0">{{ $totalUsers }}</h4>
                            <small class="text-muted">Active Writers</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Authors -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>Active Writers</h6>
                </div>
                <div class="card-body">
                    @foreach($recentAuthors as $author)
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $author->name }}</div>
                                <small class="text-muted">{{ $author->posts_count }} posts</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Call to Action -->
            @auth
                @if(!auth()->user()->isAdmin())
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-pen-alt fa-3x text-primary mb-3"></i>
                            <h5>Share Your Thoughts!</h5>
                            <p class="text-muted">Got theories about Alien Stage? Want to analyze characters? Share your insights with the community!</p>
                            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Write a Post
                            </a>
                        </div>
                    </div>
                @endif
            @else
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                        <h5>Join the Community!</h5>
                        <p class="text-muted">Sign up to write your own posts and join the discussion about Alien Stage!</p>
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Join Now
                        </a>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</div>
@endsection