<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denomination extends Model
{
    protected $fillable = [
        'game_id',
        'group',
        'sort_order',
        'name',
        'price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'integer',
        'sort_order' => 'integer',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
