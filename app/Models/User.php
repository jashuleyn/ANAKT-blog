<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all posts for this user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get all comments for this user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all post likes for this user.
     */
    public function postLikes()
    {
        return $this->hasMany(PostLike::class);
    }

    /**
     * Get all comment likes for this user.
     */
    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a regular user.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get user's approved posts.
     */
    public function approvedPosts()
    {
        return $this->posts()->approved();
    }

    /**
     * Get user's pending posts.
     */
    public function pendingPosts()
    {
        return $this->posts()->pending();
    }

    /**
     * Get user's rejected posts.
     */
    public function rejectedPosts()
    {
        return $this->posts()->rejected();
    }

    /**
     * Get user's posts count by status.
     */
    public function getPostsCountAttribute()
    {
        return $this->posts()->count();
    }

    /**
     * Get user's approved posts count.
     */
    public function getApprovedPostsCountAttribute()
    {
        return $this->approvedPosts()->count();
    }

    /**
     * Get user's pending posts count.
     */
    public function getPendingPostsCountAttribute()
    {
        return $this->pendingPosts()->count();
    }

    /**
     * Get user's rejected posts count.
     */
    public function getRejectedPostsCountAttribute()
    {
        return $this->rejectedPosts()->count();
    }

    /**
     * Get user's comments count.
     */
    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    /**
     * Get user's initials for avatar.
     */
    public function getInitialsAttribute()
    {
        $nameParts = explode(' ', $this->name);
        $initials = '';
        
        foreach ($nameParts as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
        
        return substr($initials, 0, 2); // Max 2 initials
    }

    /**
     * Get user's role with proper formatting.
     */
    public function getRoleFormattedAttribute()
    {
        return ucfirst($this->role);
    }

    /**
     * Check if user's email is verified.
     */
    public function isEmailVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Get user's avatar background color based on role.
     */
    public function getAvatarColorAttribute()
    {
        return $this->isAdmin() 
            ? 'linear-gradient(135deg, #f59e0b, #d97706)' 
            : 'linear-gradient(135deg, #06b6d4, #0891b2)';
    }

    /**
     * Get user statistics.
     */
    public function getStatsAttribute()
    {
        return [
            'total_posts' => $this->posts_count,
            'approved_posts' => $this->approved_posts_count,
            'pending_posts' => $this->pending_posts_count,
            'rejected_posts' => $this->rejected_posts_count,
            'comments_count' => $this->comments_count,
            'join_date' => $this->created_at->format('M d, Y'),
            'days_since_join' => $this->created_at->diffInDays(now()),
            'is_verified' => $this->isEmailVerified(),
        ];
    }

    /**
     * Scope to get only regular users (non-admin).
     */
    public function scopeRegularUsers($query)
    {
        return $query->where('role', 'user');
    }

    /**
     * Scope to get only admin users.
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope to get verified users.
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope to get unverified users.
     */
    public function scopeUnverified($query)
    {
        return $query->whereNull('email_verified_at');
    }

    /**
     * Get users with their post counts.
     */
    public function scopeWithPostCounts($query)
    {
        return $query->withCount([
            'posts',
            'posts as approved_posts_count' => function ($query) {
                $query->where('status', 'approved');
            },
            'posts as pending_posts_count' => function ($query) {
                $query->where('status', 'pending');
            },
            'posts as rejected_posts_count' => function ($query) {
                $query->where('status', 'rejected');
            },
            'comments as comments_count'
        ]);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Set default role to 'user' when creating
        static::creating(function ($user) {
            if (empty($user->role)) {
                $user->role = 'user';
            }
        });

        // Delete user's posts and associated files when user is deleted
        static::deleting(function ($user) {
            // Don't allow deleting admin users
            if ($user->isAdmin()) {
                return false;
            }

            // Delete all user's post images
            foreach ($user->posts as $post) {
                if ($post->image && file_exists(public_path('images/posts/' . $post->image))) {
                    unlink(public_path('images/posts/' . $post->image));
                }
            }

            // Delete all user's posts (cascade)
            $user->posts()->delete();
            
            // Delete all user's comments (cascade)
            $user->comments()->delete();
            
            // Delete all user's likes (cascade)
            $user->postLikes()->delete();
            $user->commentLikes()->delete();
        });
    }
}