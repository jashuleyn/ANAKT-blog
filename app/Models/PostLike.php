<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id', 
        'user_id', 
        'is_like'
    ];

    protected $casts = [
        'is_like' => 'boolean'
    ];

    /**
     * Get the post that the like belongs to.
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that created the like.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}