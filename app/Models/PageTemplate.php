<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Page;
use App\Models\Status;
class PageTemplate extends Model
{
    protected $table = 'page_templates';
    
    protected $hidden = ['created_at'];
    protected $casts = [
        'updated_at' => 'datetime:d M, Y'
    ];
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'template_id', 'id');
    }
    
    public function statuses()
    {
         return $this->belongsTo(Status::class,'status_id');
    }
}