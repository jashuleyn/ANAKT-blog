@extends('layouts.app')

@section('title', 'All Blogs - ANAKT Blog')

@section('content')
<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-newspaper me-2"></i>All Blog Posts</h2>
            
            <!-- Search and Sort -->
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search posts...">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
                    <select name="sort" class="form-select ms-2" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                    </select>
                </form>
            </div>
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
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text flex-grow-1">{{ Str::limit($post->content, 150) }}</p>
                                
                                <div class="author-info mb-2">
                                    <small>
                                        <i class="fas fa-user me-1"></i>By {{ $post->user->name }}
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-calendar me-1"></i>{{ $post->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                                
                                <!-- Post Stats -->
                                <div class="post-stats mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-thumbs-up me-1"></i>{{ $post->likes_count ?? 0 }}
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-comment me-1"></i>{{ $post->comments_count ?? 0 }}
                                        @if(($post->likes_count ?? 0) > 0 || ($post->comments_count ?? 0) > 0)
                                            <span class="mx-2">•</span>
                                            <span class="badge bg-primary">{{ ($post->likes_count ?? 0) + (($post->comments_count ?? 0) * 0.5) }} popularity</span>
                                        @endif
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
                    @if(request('search'))
                        <h3>No posts found for "{{ request('search') }}"</h3>
                        <p class="text-muted">Try adjusting your search terms or browse all posts.</p>
                        <a href="{{ route('blogs') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>View All Posts
                        </a>
                    @else
                        <h3>No blog posts yet</h3>
                        <p class="text-muted">Be the first observer to document the Garden!</p>
                        @auth
                            @if(!auth()->user()->isAdmin())
                                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Create Your First Observation
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Join the Observers
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Blog Stats -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Garden Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary">{{ $totalPosts }}</h4>
                        <small class="text-muted">Documented Observations</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $totalUsers }}</h4>
                        <small class="text-muted">Active Observers</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Authors -->
        @if($recentAuthors->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Most Active Observers</h5>
                </div>
                <div class="card-body">
                    @foreach($recentAuthors as $author)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $author->name }}</strong>
                                <small class="text-muted d-block">Garden Observer</small>
                            </div>
                            <span class="badge bg-primary">{{ $author->posts_count }} observations</span>
                        </div>
                        @if(!$loop->last)
                            <hr class="my-2">
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0 text-white"><i class="fas fa-rocket me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                @auth
                    @if(!auth()->user()->isAdmin())
                        <a href="{{ route('posts.create') }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-pen me-2"></i>Document New Observation
                        </a>
                        <a href="{{ route('posts.my') }}" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-user-edit me-2"></i>My Observations
                        </a>
                    @endif
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-home me-2"></i>Return to Garden
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-user-plus me-2"></i>Become an Observer
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-sign-in-alt me-2"></i>Enter Garden
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-home me-2"></i>Return to Garden
                    </a>
                @endauth
            </div>
        </div>

        <!-- Alien Stage Quote -->
        <div class="card mt-4" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border: none;">
            <div class="card-body text-center">
                <i class="fas fa-quote-left fa-2x text-warning mb-3"></i>
                <blockquote class="blockquote text-light mb-3">
                    <p class="mb-0">"In this garden, every voice tells a story worth remembering."</p>
                </blockquote>
                <small class="text-light">— An Observer's Reflection</small>
            </div>
        </div>
    </div>
</div>

<style>
.blog-image {
    height: 200px;
    width: 100%;
    object-fit: cover;
}

.post-stats .badge {
    font-size: 0.7rem;
}
</style>
@endsection