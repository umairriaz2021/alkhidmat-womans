<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Post;
class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'status_id'];
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function status(): BelongsTo
    {
        // Yeh batata hai ke Category table ki status_id, Status table ki id se linked hai
        return $this->belongsTo(Status::class, 'status_id');
    }
}
