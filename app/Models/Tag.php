<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
class Tag extends Model
{
   public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
