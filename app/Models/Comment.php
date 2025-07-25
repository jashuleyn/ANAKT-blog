<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id', 
        'user_id', 
        'content'
    ];

    /**
     * Get the post that the comment belongs to.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that created the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all likes for this comment.
     */
    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Get likes count for this comment.
     */
    public function getLikesCountAttribute()
    {
        return $this->commentLikes()->where('is_like', true)->count();
    }

    /**
     * Get dislikes count for this comment.
     */
    public function getDislikesCountAttribute()
    {
        return $this->commentLikes()->where('is_like', false)->count();
    }

    /**
     * Get net likes (likes - dislikes) for this comment.
     */
    public function getNetLikesAttribute()
    {
        return $this->likes_count - $this->dislikes_count;
    }

    /**
     * Check if a user has liked this comment.
     */
    public function isLikedBy($user)
    {
        if (!$user) return false;
        
        $like = $this->commentLikes()->where('user_id', $user->id)->first();
        return $like && $like->is_like;
    }

    /**
     * Check if a user has disliked this comment.
     */
    public function isDislikedBy($user)
    {
        if (!$user) return false;
        
        $like = $this->commentLikes()->where('user_id', $user->id)->first();
        return $like && !$like->is_like;
    }

    /**
     * Get user's reaction to this comment.
     */
    public function getUserReaction($user)
    {
        if (!$user) return null;
        
        $like = $this->commentLikes()->where('user_id', $user->id)->first();
        if (!$like) return null;
        
        return $like->is_like ? 'like' : 'dislike';
    }

    /**
     * Scope to order comments by popularity (net likes).
     */
    public function scopePopular($query)
    {
        return $query->withCount([
            'commentLikes as likes_count' => function ($query) {
                $query->where('is_like', true);
            },
            'commentLikes as dislikes_count' => function ($query) {
                $query->where('is_like', false);
            }
        ])->orderByRaw('likes_count - dislikes_count DESC');
    }

    /**
     * Scope to order comments by recent activity.
     */
    public function scopeRecent($query)
    {
        return $query->latest();
    }
}