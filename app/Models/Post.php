<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\Status;

class Post extends Model
{
    // Post belongsTo Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Post belongsToMany Tag (Many-to-Many)
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

     public function profileImage()
    {
        // belongsTo(RelatedModel, foreign_key, owner_key)
        return $this->belongsTo(Media::class, 'image_id', 'id')->select('id','file_path');
    }
    
    // Post hasMany Comment
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Kyunki humne pehle status_id ki baat ki thi:
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
