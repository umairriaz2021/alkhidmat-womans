<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'payment_method_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'amount',
        'currency',
        'status',
        'order_number',
        'meezan_order_ref',
        'link_status'
    ];
}
