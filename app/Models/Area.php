<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
     
     protected $guard = [];
     protected $casts = [
     'updated_at' => 'datetime:Y-m-d H:i',
     'gallery' => 'array',
     'our_services' => 'array'
   ];   
     public function category()
    {
        return $this->belongsTo(Category::class);
    }
     public function profileImage()
    {
        // belongsTo(RelatedModel, foreign_key, owner_key)
        return $this->belongsTo(Media::class, 'image_id', 'id')->select('id','file_path');
    }
     public function status()
    {
        return $this->belongsTo(Status::class);
    }

}
