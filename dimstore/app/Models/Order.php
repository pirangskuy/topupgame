<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        // V2 utama (Midtrans)
        'order_code',
        'total_amount',
        'user_id',
        'server_id',

        'game_id',
        'denomination_id',
        'payment_method_id',

        'whatsapp',
        'status',
        'note',

        'payment_provider',
        'payment_channel',

        'snap_token',
        'midtrans_transaction_id',
        'midtrans_status',
        'midtrans_raw',
        'paid_at',
        'delivered_at',

        // legacy (biar kompatibel dengan data lama)
        'code',
        'user_id_value',
        'server_value',
        'price',
    ];

    protected $casts = [
        'midtrans_raw' => 'array',
        'paid_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            // pastikan order_code selalu ada (ini yang dipakai Midtrans sebagai order_id)
            if (empty($order->order_code)) {
                $order->order_code = 'DIM-' . now()->format('ymd') . '-' . Str::upper(Str::random(6));
            }

            // legacy fallback (kalau view lama masih pakai code)
            if (empty($order->code)) {
                $order->code = $order->order_code;
            }

            // sync legacy jika kolom baru terisi
            if (empty($order->price) && !empty($order->total_amount)) {
                $order->price = (int) $order->total_amount;
            }
            if (empty($order->user_id_value) && !empty($order->user_id)) {
                $order->user_id_value = (string) $order->user_id;
            }
            if (empty($order->server_value) && !empty($order->server_id)) {
                $order->server_value = (string) $order->server_id;
            }
        });
    }

    public function game(): BelongsTo { return $this->belongsTo(Game::class); }
    public function denomination(): BelongsTo { return $this->belongsTo(Denomination::class); }
    public function paymentMethod(): BelongsTo { return $this->belongsTo(PaymentMethod::class); }
}
