@extends('layouts.app')

@section('title', 'User Profile - ANAKT Blog')

@section('content')
<div class="row">
    <!-- User Profile -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-user fa-2x text-white"></i>
                </div>
                
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                
                <span class="badge bg-{{ $user->isAdmin() ? 'warning' : 'info' }} fs-6 mb-3">
                    {{ ucfirst($user->role) }}
                </span>
                
                <hr>
                
                <div class="row text-center">
                    <div class="col-6">
                        <h5>{{ $user->posts()->count() }}</h5>
                        <small class="text-muted">Total Posts</small>
                    </div>
                    <div class="col-6">
                        <h5>{{ $user->posts()->approved()->count() }}</h5>
                        <small class="text-muted">Approved</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-start">
                    <p class="mb-2">
                        <i class="fas fa-calendar me-2"></i>
                        <strong>Joined:</strong> {{ $user->created_at->format('F d, Y') }}
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Member for:</strong> {{ $user->created_at->diffForHumans() }}
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-{{ $user->email_verified_at ? 'check-circle text-success' : 'times-circle text-warning' }} me-2"></i>
                        <strong>Email:</strong> {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                    </p>
                </div>
                
                @if(!$user->isAdmin())
                    <hr>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this user and all their posts? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Delete User
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        <div class="mt-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </div>

    <!-- User's Posts -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent border-bottom-0">
                <h5 class="mb-0">
                    <i class="fas fa-newspaper me-2"></i>{{ $user->name }}'s Posts
                </h5>
            </div>
            <div class="card-body">
                @if($posts->count() > 0)
                    @foreach($posts as $post)
                        <div class="d-flex mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0 me-3">
                                @if($post->image)
                                    <img src="{{ asset('images/posts/' . $post->image) }}" 
                                         class="rounded" style="width: 80px; height: 60px; object-fit: cover;"
                                         alt="{{ $post->title }}">
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 60px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">{{ $post->title }}</h6>
                                    <span class="badge badge-{{ $post->status }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </div>
                                
                                <p class="text-muted mb-2">{{ Str::limit($post->content, 100) }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>{{ $post->created_at->format('M d, Y') }}
                                    </small>
                                    
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.posts.show', $post) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($post->isPending())
                                            <form action="{{ route('admin.posts.approve', $post) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-success">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('admin.posts.reject', $post) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-warning">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                        <h5>No Posts Yet</h5>
                        <p class="text-muted">This user hasn't created any posts yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection