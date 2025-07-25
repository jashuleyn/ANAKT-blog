@extends('layouts.app')

@section('title', 'Create New Post - ANAKT Blog')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent border-bottom-0">
                <h3 class="card-title mb-0">
                    <i class="fas fa-plus-circle me-2 text-primary"></i>Create New Blog Post
                </h3>
            </div>
            
            <div class="card-body">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Post Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" 
                               placeholder="Enter an engaging title for your post">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Featured Image (Optional)</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        <div class="form-text">Upload a featured image for your post (JPEG, PNG, JPG, GIF - Max 2MB)</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="content" class="form-label">Post Content</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="12" 
                                  placeholder="Write your blog content here...">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Your post will be submitted for review and approval by an administrator before it becomes visible to other users.
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Submit for Review
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Writing Tips -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-lightbulb me-2 text-warning"></i>Writing Tips
                </h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Write a compelling title that grabs attention
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Use clear and engaging language
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Break up long paragraphs for better readability
                    </li>
                    <li class="mb-0">
                        <i class="fas fa-check text-success me-2"></i>
                        Add a relevant image to make your post more attractive
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection