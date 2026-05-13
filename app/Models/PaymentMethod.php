<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Status;
use App\Models\Media;
class PaymentMethod extends Model
{
    protected $table = 'payment_methods';
    protected $fillable = [
        'name',
        'image_id',
        'status_id',
        'general'
        
    ];
    protected $casts = [
    'general' => 'array',
];

    public function status(): BelongsTo
    {
        // Yahan 'status_id' foreign key hai aur 'id' Status model ki primary key
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }
     public function image()
    {
        // belongsTo(RelatedModel, foreign_key, owner_key)
        return $this->belongsTo(Media::class, 'image_id', 'id')->select('id','file_path');
    }
}
