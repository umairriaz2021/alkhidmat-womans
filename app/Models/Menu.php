<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i',
    ];
    protected $hidden = [
        
         'created_at'
    ];
    public function submenu() {
    return $this->hasMany(Menu::class, 'parent_id');
    }

// Parent menu check karne ke liye
    public function parent() {
        return $this->belongsTo(Menu::class, 'parent_id');
    }
}
