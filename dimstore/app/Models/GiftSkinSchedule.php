<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftSkinSchedule extends Model
{
    protected $fillable = [
        'nickname',
        'user_id',
        'order_date',
        'gift_date',
        'status',
        'admin_name',
        'note',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'gift_date'  => 'date',
    ];
}
