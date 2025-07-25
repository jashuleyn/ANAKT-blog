<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new comment.
     */
    public function store(Request $request, Post $post)
    {
        // Only allow comments on approved posts
        if (!$post->isApproved()) {
            return response()->json(['error' => 'Cannot comment on this post'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        $comment->load('user');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user_name' => $comment->user->name,
                    'user_initials' => $comment->user->initials,
                    'created_at' => $comment->created_at->format('M d, Y'),
                    'created_at_human' => $comment->created_at->diffForHumans(),
                    'likes_count' => 0,
                    'dislikes_count' => 0,
                    'user_reaction' => null,
                    'can_delete' => true
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment)
    {
        // Only allow deleting own comments or admin can delete any
        if ($comment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            abort(403);
        }

        $comment->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }

    /**
     * Toggle like/dislike on a comment.
     */
    public function toggleLike(Request $request, Comment $comment)
    {
        $request->validate([
            'type' => 'required|in:like,dislike'
        ]);

        $isLike = $request->type === 'like';
        $userId = Auth::id();

        // Find existing reaction
        $existingLike = $comment->commentLikes()->where('user_id', $userId)->first();

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
            $comment->commentLikes()->create([
                'user_id' => $userId,
                'is_like' => $isLike
            ]);
            $action = 'added';
        }

        // Get updated counts
        $likesCount = $comment->commentLikes()->where('is_like', true)->count();
        $dislikesCount = $comment->commentLikes()->where('is_like', false)->count();
        $userReaction = $comment->getUserReaction(Auth::user());

        return response()->json([
            'success' => true,
            'action' => $action,
            'likes_count' => $likesCount,
            'dislikes_count' => $dislikesCount,
            'user_reaction' => $userReaction
        ]);
    }
}