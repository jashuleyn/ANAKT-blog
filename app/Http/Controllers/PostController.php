<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of approved posts.
     */
    public function index()
    {
        $posts = Post::with('user')
            ->approved()
            ->latest('approved_at')
            ->paginate(6);

        // Use popular scope for most popular posts based on likes and comments
        $popularPosts = Post::with('user')
            ->approved()
            ->popular()
            ->take(3)
            ->get();

        return view('posts.index', compact('posts', 'popularPosts'));
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post)
    {
        // Only show approved posts or user's own posts
        if (!$post->isApproved() && (!Auth::check() || $post->user_id !== Auth::id())) {
            abort(404);
        }

        // Load post with relationships
        $post->load(['user', 'comments.user', 'comments.commentLikes']);

        // Get comments with their like counts
        $comments = $post->comments()
            ->with(['user', 'commentLikes'])
            ->latest()
            ->get();

        // Use popular scope for most popular posts
        $popularPosts = Post::with('user')
            ->approved()
            ->where('id', '!=', $post->id)
            ->popular()
            ->take(3)
            ->get();

        // Get latest posts for the sidebar
        $latestPosts = Post::with('user')
            ->approved()
            ->where('id', '!=', $post->id)
            ->latest('approved_at')
            ->take(4)
            ->get();

        return view('posts.show', compact('post', 'popularPosts', 'latestPosts', 'comments'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'user_id' => Auth::id(),
            'status' => 'pending',
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/posts'), $imageName);
            $data['image'] = $imageName;
        }

        Post::create($data);

        return redirect()->route('posts.my')->with('success', 'Post created successfully and is pending approval!');
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post)
    {
        // Only allow editing own posts
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Only allow updating own posts
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'status' => 'pending', // Reset to pending after edit
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image && file_exists(public_path('images/posts/' . $post->image))) {
                unlink(public_path('images/posts/' . $post->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/posts'), $imageName);
            $data['image'] = $imageName;
        }

        $post->update($data);

        return redirect()->route('posts.my')->with('success', 'Post updated successfully and is pending approval!');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            abort(401, 'Unauthenticated');
        }

        // Only allow deleting own posts or admin can delete any
        if ($post->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Delete image if exists
        if ($post->image && file_exists(public_path('images/posts/' . $post->image))) {
            unlink(public_path('images/posts/' . $post->image));
        }

        $post->delete();

        if (Auth::user()->isAdmin()) {
            return redirect()->back()->with('success', 'Post deleted successfully!');
        }

        return redirect()->route('posts.my')->with('success', 'Post deleted successfully!');
    }

    /**
     * Display user's profile with their posts.
     */
    public function profile()
    {
        $status = request('status');
        
        $query = Post::where('user_id', Auth::id());
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $posts = $query->latest()->paginate(10);

        return view('posts.my-posts', compact('posts'));
    }

    /**
     * Show about page.
     */
    public function about()
    {
        return view('posts.about');
    }

    /**
     * Show all blogs with search and filter.
     */
    public function allBlogs()
    {
        $query = Post::with('user')->approved();
        
        // Search functionality
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }
        
        // Sorting
        $sort = request('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title':
                $query->orderBy('title');
                break;
            case 'popular':
                $query->popular();
                break;
            default: // latest
                $query->latest('approved_at');
                break;
        }
        
        $posts = $query->paginate(12);
        
        // Additional data for sidebar
        $totalPosts = Post::approved()->count();
        $totalUsers = User::whereHas('posts', function($q) {
            $q->approved();
        })->count();
        
        $recentAuthors = User::withCount(['posts' => function($q) {
                $q->approved();
            }])
            ->having('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->take(5)
            ->get();
        
        return view('posts.blogs', compact('posts', 'totalPosts', 'totalUsers', 'recentAuthors'));
    }
}