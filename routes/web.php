<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostLikeController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/about', [PostController::class, 'about'])->name('about');
Route::get('/blogs', [PostController::class, 'allBlogs'])->name('blogs');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// User routes (authenticated users only) - MOVED BEFORE posts/{post} route
Route::middleware('auth')->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/profile', [PostController::class, 'profile'])->name('profile');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    // Keep old route for backward compatibility
    Route::get('/my-posts', [PostController::class, 'profile'])->name('posts.my');
    
    // Comment routes
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/like', [CommentController::class, 'toggleLike'])->name('comments.like');
    
    // Post like routes
    Route::post('/posts/{post}/like', [PostLikeController::class, 'toggle'])->name('posts.like');
});

// Public post show route - MOVED AFTER auth routes to avoid conflicts
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Admin routes
Route::prefix('admin')->middleware('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // API routes for AJAX calls
    Route::get('/api/pending-count', [AdminController::class, 'getPendingCount'])->name('admin.api.pending-count');
    Route::get('/api/stats', [AdminController::class, 'getStats'])->name('admin.api.stats');
    Route::get('/api/search', [AdminController::class, 'search'])->name('admin.api.search');
    
    // Post management
    Route::get('/posts', [AdminController::class, 'posts'])->name('admin.posts.index');
    Route::get('/posts/pending', [AdminController::class, 'pendingPosts'])->name('admin.posts.pending');
    Route::get('/posts/{post}', [AdminController::class, 'showPost'])->name('admin.posts.show');
    Route::patch('/posts/{post}/approve', [AdminController::class, 'approvePost'])->name('admin.posts.approve');
    Route::patch('/posts/{post}/reject', [AdminController::class, 'rejectPost'])->name('admin.posts.reject');
    Route::delete('/posts/{post}', [AdminController::class, 'deletePost'])->name('admin.posts.destroy');
    
    // Bulk actions for posts
    Route::post('/posts/bulk-approve', [AdminController::class, 'bulkApprove'])->name('admin.posts.bulk-approve');
    Route::post('/posts/bulk-reject', [AdminController::class, 'bulkReject'])->name('admin.posts.bulk-reject');
    
    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.destroy');
});