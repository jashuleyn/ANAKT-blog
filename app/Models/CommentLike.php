<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id', 
        'user_id', 
        'is_like'
    ];

    protected $casts = [
        'is_like' => 'boolean'
    ];

    /**
     * Get the comment that the like belongs to.
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * Get the user that created the like.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}