<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Status;
class MegaMenu extends Model
{
     protected $table = 'mega_menus';
    protected $fillable = ['group_name', 'links','status_id'];
    protected $attributes = [
        'links' => '[]', // Database level par empty JSON string
    ];
    protected $casts = [
        'links' => 'array',
    ];
    protected $appends = ['links_data'];

    public function getMenuDetails()
    {
        // Check karein ke links null toh nahi
        if (!$this->links || !is_array($this->links)) {
            return collect(); // Khali collection return karein
        }

        return Menu::whereIn('id', $this->links)->get();
    }
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public function getLinksDataAttribute()
{
    if (!empty($this->links) && is_array($this->links)) {
        // Menu table se data fetch karna
        return \App\Models\Menu::whereIn('id', $this->links)
            ->select('id', 'title', 'url')
            ->get();
    }
    return collect();
}
}
