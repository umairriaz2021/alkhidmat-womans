<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Media;
use App\Models\PageTemplate;

class Page extends Model
{
  
   protected $casts = [
     'updated_at' => 'datetime:Y-m-d H:i',
     'slider_id' => 'array'
   ];
 
    public function template()
    {
        return $this->belongsTo(PageTemplate::class, 'template_id');
    }

    public function profileImage()
    {
        // belongsTo(RelatedModel, foreign_key, owner_key)
        return $this->belongsTo(Media::class, 'image_id', 'id')->select('id','file_path');
    }

}
