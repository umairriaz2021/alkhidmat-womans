<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Media;
class Slider extends Model
{
    public function profileImage()
    {
        // belongsTo(RelatedModel, foreign_key, owner_key)
        return $this->belongsTo(Media::class, 'image_id', 'id')->select('id','file_path');
    }
    protected $fillable = ['tagline','content','cta_text','cta_url','image_id','status_id','main_heading','donation_projects',
    'donation_types'];
    protected $hidden = ['created_at'];
    protected $casts = [
    'updated_at' => 'datetime:d M, Y',
    'created_at' => 'datetime:d M, Y h:i A',
    'donation_projects' => 'array',
    'donation_types' => 'array'
];
public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
 
}
