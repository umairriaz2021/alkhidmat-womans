<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Media;

class Setting extends Model
{
    protected $fillable = [
    'id', // <--- Add this
    'site_title', 
    'site_tag', 
    'site_logo', 
    'footer_logo'
];
 protected $hidden = [
     'created_at',
     'updated_at'
 ];
    public function siteLogo()
    {
        // Yahan hum explicitly batate hain ke 'site_logo' column 'Media' ki 'id' ko point kar raha hai
        return $this->belongsTo(Media::class, 'site_logo', 'id')->select('id','file_path');
    }

    public function footerLogo()
    {
        return $this->belongsTo(Media::class, 'footer_logo', 'id')->select('id','file_path');
    }
}
