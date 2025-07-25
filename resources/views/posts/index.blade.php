@extends('layouts.app')

@section('title', 'ANAKT Blog - Home')

@section('content')
<div class="row">
    <!-- Latest Blogs Section -->
    <div class="col-lg-8">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Latest Blogs</h3>
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
                                
                                <div class="author-info mb-3">
                                    <small>
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
                <nav aria-label="Blog pagination">
                    @if ($posts->hasPages())
                        <ul class="pagination pagination-lg">
                            {{-- Previous Page Link --}}
                            @if ($posts->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="fas fa-chevron-left"></i> Previous
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $posts->previousPageUrl() }}" rel="prev">
                                        <i class="fas fa-chevron-left"></i> Previous
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($posts->getUrlRange(max(1, $posts->currentPage() - 2), min($posts->lastPage(), $posts->currentPage() + 2)) as $page => $url)
                                @if ($page == $posts->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($posts->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $posts->nextPageUrl() }}" rel="next">
                                        Next <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        Next <i class="fas fa-chevron-right"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    @endif
                </nav>
            </div>
        @else
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-newspaper fa-5x text-muted mb-3"></i>
                    <h3>No blog posts yet</h3>
                    <p class="text-muted">Be the first to share your thoughts!</p>
                    @auth
                        @if(!auth()->user()->isAdmin())
                            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Your First Post
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Join Us to Start Writing
                        </a>
                    @endauth
                </div>
            </div>
        @endif
    </div>

    <!-- Most Popular Section -->
    <div class="col-lg-4">
        <div class="section-header">
            <h3 class="mb-0">Most Popular</h3>
        </div>

        @if($popularPosts->count() > 0)
            @foreach($popularPosts as $post)
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-4">
                            @if($post->image)
                                <img src="{{ asset('images/posts/' . $post->image) }}" 
                                     class="img-fluid rounded-start h-100" 
                                     style="object-fit: cover;"
                                     alt="{{ $post->title }}">
                            @else
                                <div class="bg-secondary h-100 rounded-start d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-8">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-2">{{ Str::limit($post->title, 50) }}</h6>
                                <div class="author-info">
                                    <small>{{ $post->created_at->format('M d, Y') }}</small>
                                </div>
                                <a href="{{ route('posts.show', $post) }}" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="card">
                <div class="card-body text-center py-4">
                    <i class="fas fa-star fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No popular posts yet</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection