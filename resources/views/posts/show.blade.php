@extends('layouts.app')

@section('title', $post->title . ' - ANAKT Blog')

@section('content')
<div class="row">
    <!-- Blog Content -->
    <div class="col-lg-8">
        <div class="card">
            @if($post->image)
                <img src="{{ asset('images/posts/' . $post->image) }}" 
                     class="card-img-top" 
                     style="height: 400px; object-fit: cover;"
                     alt="{{ $post->title }}">
            @endif
            
            <div class="card-body">
                <h1 class="card-title mb-3">{{ $post->title }}</h1>
                
                <div class="author-info mb-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fas fa-user me-2"></i>By <strong>{{ $post->user->name }}</strong>
                            <span class="mx-2">•</span>
                            <i class="fas fa-calendar me-2"></i>{{ $post->created_at->format('F d, Y') }}
                        </div>
                        
                        @auth
                            @if($post->user_id === auth()->id())
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
                            @endif
                        @endauth
                    </div>
                    
                    @if($post->status !== 'approved')
                        <div class="mt-2">
                            <span class="badge badge-{{ $post->status }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <div class="blog-content mb-4">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <!-- Post Reactions -->
                @if($post->isApproved())
                    <div class="post-reactions mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            @auth
                                <button class="btn btn-outline-success btn-sm like-btn" 
                                        data-post-id="{{ $post->id }}" 
                                        data-type="like"
                                        data-active="{{ $post->isLikedBy(auth()->user()) ? 'true' : 'false' }}">
                                    <i class="fas fa-thumbs-up me-1"></i>
                                    <span class="likes-count">{{ $post->likes_count }}</span>
                                </button>
                                <button class="btn btn-outline-danger btn-sm dislike-btn" 
                                        data-post-id="{{ $post->id }}" 
                                        data-type="dislike"
                                        data-active="{{ $post->isDislikedBy(auth()->user()) ? 'true' : 'false' }}">
                                    <i class="fas fa-thumbs-down me-1"></i>
                                    <span class="dislikes-count">{{ $post->dislikes_count }}</span>
                                </button>
                            @else
                                <span class="text-muted">
                                    <i class="fas fa-thumbs-up me-1"></i>{{ $post->likes_count }}
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-thumbs-down me-1"></i>{{ $post->dislikes_count }}
                                </span>
                            @endauth
                            <span class="text-muted">
                                <i class="fas fa-comment me-1"></i>{{ $post->comments_count }} comments
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Comments Section -->
                @if($post->isApproved())
                    <div class="comments-section">
                        <h4 class="mb-4">
                            <i class="fas fa-comments me-2"></i>Comments ({{ $comments->count() }})
                        </h4>

                        <!-- Comment Form -->
                        @auth
                            <div class="comment-form mb-4">
                                <form id="comment-form" data-post-id="{{ $post->id }}">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea class="form-control" id="comment-content" name="content" 
                                                  rows="3" placeholder="Share your thoughts about this observation..." 
                                                  maxlength="1000" required></textarea>
                                        <div class="form-text">
                                            <span id="char-count">0</span>/1000 characters
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>Post Comment
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <a href="{{ route('login') }}">Login</a> or 
                                <a href="{{ route('register') }}">register</a> to join the discussion.
                            </div>
                        @endauth

                        <!-- Comments List -->
                        <div id="comments-list">
                            @forelse($comments as $comment)
                                <div class="comment mb-4 pb-3 border-bottom" data-comment-id="{{ $comment->id }}">
                                    <div class="d-flex">
                                        <div class="comment-avatar me-3">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; font-size: 14px; font-weight: bold;">
                                                {{ $comment->user->initials }}
                                            </div>
                                        </div>
                                        <div class="comment-content flex-grow-1">
                                            <div class="comment-header mb-2">
                                                <strong>{{ $comment->user->name }}</strong>
                                                <small class="text-muted ms-2">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </small>
                                                @auth
                                                    @if($comment->user_id === auth()->id() || auth()->user()->isAdmin())
                                                        <button class="btn btn-sm btn-outline-danger ms-2 delete-comment-btn" 
                                                                data-comment-id="{{ $comment->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                @endauth
                                            </div>
                                            <div class="comment-text mb-2">
                                                {{ $comment->content }}
                                            </div>
                                            <div class="comment-actions">
                                                @auth
                                                    <button class="btn btn-sm btn-outline-success comment-like-btn" 
                                                            data-comment-id="{{ $comment->id }}" 
                                                            data-type="like"
                                                            data-active="{{ $comment->isLikedBy(auth()->user()) ? 'true' : 'false' }}">
                                                        <i class="fas fa-thumbs-up me-1"></i>
                                                        <span class="comment-likes-count">{{ $comment->likes_count }}</span>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger comment-dislike-btn" 
                                                            data-comment-id="{{ $comment->id }}" 
                                                            data-type="dislike"
                                                            data-active="{{ $comment->isDislikedBy(auth()->user()) ? 'true' : 'false' }}">
                                                        <i class="fas fa-thumbs-down me-1"></i>
                                                        <span class="comment-dislikes-count">{{ $comment->dislikes_count }}</span>
                                                    </button>
                                                @else
                                                    <span class="text-muted small">
                                                        <i class="fas fa-thumbs-up me-1"></i>{{ $comment->likes_count }}
                                                        <span class="mx-2">•</span>
                                                        <i class="fas fa-thumbs-down me-1"></i>{{ $comment->dislikes_count }}
                                                    </span>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-comment fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No comments yet. Be the first to share your thoughts!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Latest Blogs Section -->
        <div class="section-header mt-5">
            <h3 class="mb-0">Latest Observations</h3>
        </div>

        @if($latestPosts->count() > 0)
            <div class="row">
                @foreach($latestPosts as $latestPost)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            @if($latestPost->image)
                                <img src="{{ asset('images/posts/' . $latestPost->image) }}" 
                                     class="blog-image" 
                                     alt="{{ $latestPost->title }}">
                            @else
                                <div class="blog-image bg-secondary d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">{{ Str::limit($latestPost->title, 50) }}</h6>
                                <p class="card-text flex-grow-1">{{ Str::limit($latestPost->content, 100) }}</p>
                                
                                <div class="author-info mb-3">
                                    <small>
                                        <i class="fas fa-user me-1"></i>By {{ $latestPost->user->name }}
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-calendar me-1"></i>{{ $latestPost->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                                
                                <a href="{{ route('posts.show', $latestPost) }}" class="btn btn-primary btn-sm">
                                    Read More <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Most Popular Section -->
        <div class="section-header">
            <h3 class="mb-0">Most Popular</h3>
        </div>

        @if($popularPosts->count() > 0)
            @foreach($popularPosts as $popularPost)
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-4">
                            @if($popularPost->image)
                                <img src="{{ asset('images/posts/' . $popularPost->image) }}" 
                                     class="img-fluid rounded-start h-100" 
                                     style="object-fit: cover;"
                                     alt="{{ $popularPost->title }}">
                            @else
                                <div class="bg-secondary h-100 rounded-start d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-8">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-2">{{ Str::limit($popularPost->title, 50) }}</h6>
                                <div class="author-info mb-1">
                                    <small>{{ $popularPost->created_at->format('M d, Y') }}</small>
                                </div>
                                <div class="popularity-stats">
                                    <small class="text-muted">
                                        <i class="fas fa-thumbs-up me-1"></i>{{ $popularPost->likes_count }}
                                        <span class="mx-1">•</span>
                                        <i class="fas fa-comment me-1"></i>{{ $popularPost->comments_count }}
                                    </small>
                                </div>
                                <a href="{{ route('posts.show', $popularPost) }}" class="stretched-link"></a>
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

        <!-- Back to Home -->
        <div class="mt-4">
            <a href="{{ route('home') }}" class="btn btn-outline-primary w-100">
                <i class="fas fa-arrow-left me-2"></i>Back to Home
            </a>
        </div>
    </div>
</div>

<style>
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

.like-btn.active, .comment-like-btn.active {
    background-color: #198754;
    border-color: #198754;
    color: white;
}

.dislike-btn.active, .comment-dislike-btn.active {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.comment-avatar {
    flex-shrink: 0;
}

.comment {
    transition: background-color 0.3s ease;
}

.comment:hover {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    margin: -15px;
    margin-bottom: 10px;
}

#char-count {
    font-weight: bold;
}

.loading {
    opacity: 0.6;
    pointer-events: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for comment form
    const commentTextarea = document.getElementById('comment-content');
    const charCount = document.getElementById('char-count');
    
    if (commentTextarea && charCount) {
        commentTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            if (this.value.length > 900) {
                charCount.style.color = '#dc3545';
            } else if (this.value.length > 800) {
                charCount.style.color = '#ffc107';
            } else {
                charCount.style.color = '#6c757d';
            }
        });
    }

    // Comment form submission
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const postId = this.dataset.postId;
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Posting...';
            submitBtn.disabled = true;
            
            fetch(`/posts/${postId}/comments`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new comment to the list
                    addCommentToList(data.comment);
                    
                    // Clear form
                    commentTextarea.value = '';
                    charCount.textContent = '0';
                    charCount.style.color = '#6c757d';
                    
                    // Update comments count
                    updateCommentsCount(1);
                    
                    // Show success message
                    showAlert('Comment added successfully!', 'success');
                } else {
                    showAlert(data.message || 'Error adding comment', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error adding comment', 'danger');
            })
            .finally(() => {
                // Restore button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // Post like/dislike functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.like-btn') || e.target.closest('.dislike-btn')) {
            e.preventDefault();
            handlePostLike(e.target.closest('.like-btn, .dislike-btn'));
        }
        
        if (e.target.closest('.comment-like-btn') || e.target.closest('.comment-dislike-btn')) {
            e.preventDefault();
            handleCommentLike(e.target.closest('.comment-like-btn, .comment-dislike-btn'));
        }
        
        if (e.target.closest('.delete-comment-btn')) {
            e.preventDefault();
            handleCommentDelete(e.target.closest('.delete-comment-btn'));
        }
    });

    function handlePostLike(button) {
        const postId = button.dataset.postId;
        const type = button.dataset.type;
        const isActive = button.dataset.active === 'true';
        
        button.classList.add('loading');
        
        fetch(`/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ type: type })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updatePostReactions(data);
            } else {
                showAlert(data.error || 'Error updating reaction', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating reaction', 'danger');
        })
        .finally(() => {
            button.classList.remove('loading');
        });
    }

    function handleCommentLike(button) {
        const commentId = button.dataset.commentId;
        const type = button.dataset.type;
        
        button.classList.add('loading');
        
        fetch(`/comments/${commentId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ type: type })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCommentReactions(commentId, data);
            } else {
                showAlert(data.error || 'Error updating reaction', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error updating reaction', 'danger');
        })
        .finally(() => {
            button.classList.remove('loading');
        });
    }

    function handleCommentDelete(button) {
        if (!confirm('Are you sure you want to delete this comment?')) {
            return;
        }
        
        const commentId = button.dataset.commentId;
        const commentElement = button.closest('.comment');
        
        button.classList.add('loading');
        
        fetch(`/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                commentElement.remove();
                updateCommentsCount(-1);
                showAlert('Comment deleted successfully!', 'success');
            } else {
                showAlert(data.error || 'Error deleting comment', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error deleting comment', 'danger');
        })
        .finally(() => {
            button.classList.remove('loading');
        });
    }

    function updatePostReactions(data) {
        // Update like button
        const likeBtn = document.querySelector('.like-btn');
        const dislikeBtn = document.querySelector('.dislike-btn');
        
        if (likeBtn) {
            likeBtn.querySelector('.likes-count').textContent = data.likes_count;
            likeBtn.dataset.active = data.user_reaction === 'like' ? 'true' : 'false';
            likeBtn.classList.toggle('active', data.user_reaction === 'like');
        }
        
        if (dislikeBtn) {
            dislikeBtn.querySelector('.dislikes-count').textContent = data.dislikes_count;
            dislikeBtn.dataset.active = data.user_reaction === 'dislike' ? 'true' : 'false';
            dislikeBtn.classList.toggle('active', data.user_reaction === 'dislike');
        }
    }

    function updateCommentReactions(commentId, data) {
        const comment = document.querySelector(`[data-comment-id="${commentId}"]`);
        if (!comment) return;
        
        const likeBtn = comment.querySelector('.comment-like-btn');
        const dislikeBtn = comment.querySelector('.comment-dislike-btn');
        
        if (likeBtn) {
            likeBtn.querySelector('.comment-likes-count').textContent = data.likes_count;
            likeBtn.dataset.active = data.user_reaction === 'like' ? 'true' : 'false';
            likeBtn.classList.toggle('active', data.user_reaction === 'like');
        }
        
        if (dislikeBtn) {
            dislikeBtn.querySelector('.comment-dislikes-count').textContent = data.dislikes_count;
            dislikeBtn.dataset.active = data.user_reaction === 'dislike' ? 'true' : 'false';
            dislikeBtn.classList.toggle('active', data.user_reaction === 'dislike');
        }
    }

    function addCommentToList(comment) {
        const commentsList = document.getElementById('comments-list');
        const emptyState = commentsList.querySelector('.text-center');
        
        if (emptyState) {
            emptyState.remove();
        }
        
        const commentHtml = `
            <div class="comment mb-4 pb-3 border-bottom" data-comment-id="${comment.id}">
                <div class="d-flex">
                    <div class="comment-avatar me-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                             style="width: 40px; height: 40px; font-size: 14px; font-weight: bold;">
                            ${comment.user_initials}
                        </div>
                    </div>
                    <div class="comment-content flex-grow-1">
                        <div class="comment-header mb-2">
                            <strong>${comment.user_name}</strong>
                            <small class="text-muted ms-2">
                                ${comment.created_at_human}
                            </small>
                            ${comment.can_delete ? `
                                <button class="btn btn-sm btn-outline-danger ms-2 delete-comment-btn" 
                                        data-comment-id="${comment.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            ` : ''}
                        </div>
                        <div class="comment-text mb-2">
                            ${comment.content}
                        </div>
                        <div class="comment-actions">
                            <button class="btn btn-sm btn-outline-success comment-like-btn" 
                                    data-comment-id="${comment.id}" 
                                    data-type="like"
                                    data-active="false">
                                <i class="fas fa-thumbs-up me-1"></i>
                                <span class="comment-likes-count">${comment.likes_count}</span>
                            </button>
                            <button class="btn btn-sm btn-outline-danger comment-dislike-btn" 
                                    data-comment-id="${comment.id}" 
                                    data-type="dislike"
                                    data-active="false">
                                <i class="fas fa-thumbs-down me-1"></i>
                                <span class="comment-dislikes-count">${comment.dislikes_count}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        commentsList.insertAdjacentHTML('afterbegin', commentHtml);
    }

    function updateCommentsCount(change) {
        const commentsHeader = document.querySelector('.comments-section h4');
        if (commentsHeader) {
            const match = commentsHeader.textContent.match(/Comments \((\d+)\)/);
            if (match) {
                const newCount = parseInt(match[1]) + change;
                commentsHeader.innerHTML = `<i class="fas fa-comments me-2"></i>Comments (${newCount})`;
            }
        }
        
        // Update post comments count in reactions
        const postCommentsCount = document.querySelector('.post-reactions .text-muted');
        if (postCommentsCount) {
            const match = postCommentsCount.textContent.match(/(\d+) comments/);
            if (match) {
                const newCount = parseInt(match[1]) + change;
                postCommentsCount.innerHTML = `<i class="fas fa-comment me-1"></i>${newCount} comments`;
            }
        }
    }

    function showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const container = document.querySelector('main.container');
        container.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }

    // Initialize active states
    document.querySelectorAll('.like-btn[data-active="true"], .comment-like-btn[data-active="true"]').forEach(btn => {
        btn.classList.add('active');
    });
    
    document.querySelectorAll('.dislike-btn[data-active="true"], .comment-dislike-btn[data-active="true"]').forEach(btn => {
        btn.classList.add('active');
    });
});
</script>
@endsection