<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'image',
        'user_id',
        'status',
        'approved_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all comments for this post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all likes for this post.
     */
    public function postLikes()
    {
        return $this->hasMany(PostLike::class);
    }

    /**
     * Get approved comments for this post.
     */
    public function approvedComments()
    {
        return $this->comments()->latest();
    }

    /**
     * Get likes count for this post.
     */
    public function getLikesCountAttribute()
    {
        return $this->postLikes()->where('is_like', true)->count();
    }

    /**
     * Get dislikes count for this post.
     */
    public function getDislikesCountAttribute()
    {
        return $this->postLikes()->where('is_like', false)->count();
    }

    /**
     * Get net likes (likes - dislikes) for this post.
     */
    public function getNetLikesAttribute()
    {
        return $this->likes_count - $this->dislikes_count;
    }

    /**
     * Get comments count for this post.
     */
    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    /**
     * Check if a user has liked this post.
     */
    public function isLikedBy($user)
    {
        if (!$user) return false;
        
        $like = $this->postLikes()->where('user_id', $user->id)->first();
        return $like && $like->is_like;
    }

    /**
     * Check if a user has disliked this post.
     */
    public function isDislikedBy($user)
    {
        if (!$user) return false;
        
        $like = $this->postLikes()->where('user_id', $user->id)->first();
        return $like && !$like->is_like;
    }

    /**
     * Get user's reaction to this post.
     */
    public function getUserReaction($user)
    {
        if (!$user) return null;
        
        $like = $this->postLikes()->where('user_id', $user->id)->first();
        if (!$like) return null;
        
        return $like->is_like ? 'like' : 'dislike';
    }

    /**
     * Scope for approved posts only.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending posts only.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for rejected posts only.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope to order posts by popularity (net likes + comments).
     */
    public function scopePopular($query)
    {
        return $query->withCount([
            'postLikes as likes_count' => function ($query) {
                $query->where('is_like', true);
            },
            'postLikes as dislikes_count' => function ($query) {
                $query->where('is_like', false);
            },
            'comments as comments_count'
        ])->orderByRaw('(likes_count - dislikes_count) + (comments_count * 0.5) DESC');
    }

    /**
     * Get the excerpt of the content.
     */
    public function getExcerptAttribute($length = 150)
    {
        return strlen($this->content) > $length 
            ? substr($this->content, 0, $length) . '...' 
            : $this->content;
    }

    /**
     * Check if post is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if post is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if post is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get the post's image URL.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('images/posts/' . $this->image);
        }
        return null;
    }

    /**
     * Check if the post has an image.
     */
    public function hasImage(): bool
    {
        return !empty($this->image) && file_exists(public_path('images/posts/' . $this->image));
    }

    /**
     * Get the post status with icon.
     */
    public function getStatusWithIconAttribute()
    {
        $icons = [
            'approved' => 'fas fa-check-circle',
            'pending' => 'fas fa-clock',
            'rejected' => 'fas fa-times-circle'
        ];

        return [
            'status' => $this->status,
            'icon' => $icons[$this->status] ?? 'fas fa-question-circle',
            'text' => ucfirst($this->status)
        ];
    }

    /**
     * Get posts by status count.
     */
    public static function getStatusCounts()
    {
        return [
            'total' => self::count(),
            'approved' => self::approved()->count(),
            'pending' => self::pending()->count(),
            'rejected' => self::rejected()->count(),
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Set default status to pending when creating
        static::creating(function ($post) {
            if (empty($post->status)) {
                $post->status = 'pending';
            }
        });

        // Delete image file when post is deleted
        static::deleting(function ($post) {
            if ($post->image && file_exists(public_path('images/posts/' . $post->image))) {
                unlink(public_path('images/posts/' . $post->image));
            }
        });
    }
}