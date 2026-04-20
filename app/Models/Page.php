<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Media;
use App\Models\PageTemplate;
use App\Models\Slider;
use App\Models\Status;

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
    public function getSlidersAttribute()
{
    // JSON ko array mein convert karein
    $ids = json_decode($this->attributes['slider_id']) ?? [];
    
    // Manual query chala kar sliders le ayein
    return Slider::whereIn('id', $ids)->get();
}
public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

}
