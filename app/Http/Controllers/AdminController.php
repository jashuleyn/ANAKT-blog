<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_posts' => Post::count(),
            'pending_posts' => Post::pending()->count(),
            'approved_posts' => Post::approved()->count(),
            'total_users' => User::where('role', 'user')->count(),
        ];

        $recentPosts = Post::with('user')->latest()->take(5)->get();
        $pendingPosts = Post::with('user')->pending()->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentPosts', 'pendingPosts'));
    }

    /**
     * Display all posts for admin management.
     */
    public function posts()
    {
        $posts = Post::with('user')->latest()->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Display pending posts for approval.
     */
    public function pendingPosts()
    {
        $posts = Post::with('user')->pending()->latest()->paginate(10);

        return view('admin.posts.pending', compact('posts'));
    }

    /**
     * Show the specified post for admin.
     */
    public function showPost(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Approve a post.
     */
    public function approvePost(Post $post)
    {
        $post->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Post approved successfully!');
    }

    /**
     * Reject a post.
     */
    public function rejectPost(Post $post)
    {
        $post->update([
            'status' => 'rejected',
            'approved_at' => null,
        ]);

        return back()->with('success', 'Post rejected!');
    }

    /**
     * Delete a post (admin can delete any post).
     */
    public function deletePost(Post $post)
    {
        // Delete image if exists
        if ($post->image && file_exists(public_path('images/posts/' . $post->image))) {
            unlink(public_path('images/posts/' . $post->image));
        }

        $post->delete();

        return back()->with('success', 'Post deleted successfully!');
    }

    /**
     * Display all users.
     */
    public function users()
    {
        $users = User::where('role', 'user')->withCount('posts')->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user details.
     */
    public function showUser(User $user)
    {
        $posts = $user->posts()->latest()->paginate(5);

        return view('admin.users.show', compact('user', 'posts'));
    }

    /**
     * Delete a user and all their posts.
     */
    public function deleteUser(User $user)
    {
        // Don't allow deleting admin users
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot delete admin users!');
        }

        // Delete user's post images
        foreach ($user->posts as $post) {
            if ($post->image && file_exists(public_path('images/posts/' . $post->image))) {
                unlink(public_path('images/posts/' . $post->image));
            }
        }

        $user->delete(); // This will cascade delete posts too

        return back()->with('success', 'User and all their posts deleted successfully!');
    }

    /**
     * Get pending posts count for AJAX requests.
     */
    public function getPendingCount()
    {
        $count = Post::pending()->count();
        
        return response()->json([
            'count' => $count,
            'has_pending' => $count > 0
        ]);
    }

    /**
     * Bulk approve posts.
     */
    public function bulkApprove(Request $request)
    {
        $postIds = $request->input('post_ids', []);
        
        if (empty($postIds)) {
            return back()->with('error', 'No posts selected!');
        }

        $updated = Post::whereIn('id', $postIds)
            ->where('status', 'pending')
            ->update([
                'status' => 'approved',
                'approved_at' => now()
            ]);

        return back()->with('success', "Successfully approved {$updated} post(s)!");
    }

    /**
     * Bulk reject posts.
     */
    public function bulkReject(Request $request)
    {
        $postIds = $request->input('post_ids', []);
        
        if (empty($postIds)) {
            return back()->with('error', 'No posts selected!');
        }

        $updated = Post::whereIn('id', $postIds)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'approved_at' => null
            ]);

        return back()->with('success', "Successfully rejected {$updated} post(s)!");
    }

    /**
     * Get admin dashboard statistics.
     */
    public function getStats()
    {
        $stats = [
            'posts' => Post::getStatusCounts(),
            'users' => [
                'total' => User::count(),
                'regular' => User::regularUsers()->count(),
                'admins' => User::admins()->count(),
                'verified' => User::verified()->count(),
                'unverified' => User::unverified()->count(),
            ],
            'recent_activity' => [
                'new_posts_today' => Post::whereDate('created_at', today())->count(),
                'new_users_today' => User::whereDate('created_at', today())->count(),
                'pending_posts' => Post::pending()->count(),
            ]
        ];

        return response()->json($stats);
    }

    /**
     * Search posts and users.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return response()->json([
                'posts' => [],
                'users' => []
            ]);
        }

        $posts = Post::with('user')
            ->where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        return response()->json([
            'posts' => $posts,
            'users' => $users
        ]);
    }
}