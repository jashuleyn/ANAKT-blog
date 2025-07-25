@extends('layouts.app')

@section('title', 'Edit Post - ANAKT Blog')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent border-bottom-0">
                <h3 class="card-title mb-0">
                    <i class="fas fa-edit me-2 text-primary"></i>Edit Blog Post
                </h3>
            </div>
            
            <div class="card-body">
                <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Post Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $post->title) }}" 
                               placeholder="Enter an engaging title for your post">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Featured Image</label>
                        
                        @if($post->image)
                            <div class="mb-3">
                                <img src="{{ asset('images/posts/' . $post->image) }}" 
                                     class="img-thumbnail" style="max-height: 200px;"
                                     alt="Current image">
                                <div class="form-text">Current image</div>
                            </div>
                        @endif
                        
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        <div class="form-text">
                            @if($post->image)
                                Upload a new image to replace the current one (JPEG, PNG, JPG, GIF - Max 2MB)
                            @else
                                Upload a featured image for your post (JPEG, PNG, JPG, GIF - Max 2MB)
                            @endif
                        </div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="content" class="form-label">Post Content</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="12" 
                                  placeholder="Write your blog content here...">{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> After editing, your post will be resubmitted for review and approval by an administrator.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Post
                        </button>
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-eye me-2"></i>View Post
                        </a>
                        <a href="{{ route('posts.my') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>My Posts
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection