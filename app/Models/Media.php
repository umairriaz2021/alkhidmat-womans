<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = ['file_name', 'file_path', 'file_type', 'file_size'];
    public function getUrlAttribute() {
        return Storage::url($this->file_path);
    }

 }
