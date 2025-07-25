<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostLikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Toggle like/dislike on a post.
     */
    public function toggle(Request $request, Post $post)
    {
        // Only allow likes on approved posts
        if (!$post->isApproved()) {
            return response()->json(['error' => 'Cannot like this post'], 403);
        }

        $request->validate([
            'type' => 'required|in:like,dislike'
        ]);

        $isLike = $request->type === 'like';
        $userId = Auth::id();

        // Find existing reaction
        $existingLike = $post->postLikes()->where('user_id', $userId)->first();

        if ($existingLike) {
            if ($existingLike->is_like == $isLike) {
                // Same reaction - remove it
                $existingLike->delete();
                $action = 'removed';
            } else {
                // Different reaction - update it
                $existingLike->update(['is_like' => $isLike]);
                $action = 'updated';
            }
        } else {
            // No existing reaction - create new one
            $post->postLikes()->create([
                'user_id' => $userId,
                'is_like' => $isLike
            ]);
            $action = 'added';
        }

        // Get updated counts
        $likesCount = $post->postLikes()->where('is_like', true)->count();
        $dislikesCount = $post->postLikes()->where('is_like', false)->count();
        $userReaction = $post->getUserReaction(Auth::user());

        return response()->json([
            'success' => true,
            'action' => $action,
            'likes_count' => $likesCount,
            'dislikes_count' => $dislikesCount,
            'user_reaction' => $userReaction,
            'net_likes' => $likesCount - $dislikesCount
        ]);
    }
}