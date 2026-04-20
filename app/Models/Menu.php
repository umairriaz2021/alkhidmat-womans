<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Status;
class Menu extends Model
{
    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i',
        'mega_menus_id' => 'array'
    ];
    protected $hidden = [
        
         'created_at'
    ];
    protected $appends = ['mega_menus'];
    public function submenu() {
    return $this->hasMany(Menu::class, 'parent_id')->select('id','title','url');
    }

// Parent menu check karne ke liye
    public function parent() {
        return $this->belongsTo(Menu::class, 'parent_id')->select('id','title','url');
    }

    public function getMegaMenusAttribute()
{
    // Check karein ke property empty na ho aur array ho
    if (!empty($this->mega_menus_id) && is_array($this->mega_menus_id)) {
        
        // Ensure karein ke IDs saaf hon aur strings ko integers mein convert kar dein (optional but safe)
        $ids = array_map('intval', $this->mega_menus_id);

        $data =  \App\Models\MegaMenu::with(['status'=>function($query){$query->select('id','name');}])->whereIn('id', $ids)->select('id','group_name','status_id','links')->get();

        return $data->each(function($item) {
            $item->makeHidden(['status_id', 'links']);
        });
    }
    
    return collect();
}
public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
