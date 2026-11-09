<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MlbbReview extends Model
{
    protected $table = 'mlbb_reviews';

    protected $fillable = [
        'order_code',
        'name',
        'rating',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'rating' => 'integer',
    ];
}
