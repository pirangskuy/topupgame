<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'requires_server',
        'image',
        'is_active',
        'sort_order', // ✅ kalau kolom ini ada di DB
    ];

    protected $casts = [
        'requires_server' => 'boolean',
        'is_active'       => 'boolean',
        'sort_order'      => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function denominations(): HasMany
    {
        return $this->hasMany(Denomination::class);
    }
}
