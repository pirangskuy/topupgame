<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    /**
     * Return Snap Token for given order_code.
     * Endpoint dipanggil dari JS di Blade setelah order dibuat.
     */
    public function token(Request $request, string $orderCode)
    {
        $order = Order::where('order_code', $orderCode)->firstOrFail();

        // Kalau token sudah ada, balikin saja (hindari generate berulang)
        if (!empty($order->snap_token)) {
            return response()->json(['token' => $order->snap_token], 200);
        }

        // Pastikan amount tersedia
        $gross = (int) ($order->total_amount ?? $order->price ?? 0);
        if ($gross <= 0) {
            return response()->json(['message' => 'Gross amount tidak valid.'], 422);
        }

        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_code,      // IMPORTANT: unik & konsisten
                'gross_amount' => $gross,
            ],
            'customer_details' => [
                'first_name' => 'Customer',
                'phone' => (string) $order->whatsapp,
            ],
        ];

        // optional: preselect metode dari pilihan user
        if ($ch = $request->input('payment_channel')) {
            $params['enabled_payments'] = [strtolower((string)$ch)];
        } elseif (!empty($order->payment_channel)) {
            $params['enabled_payments'] = [strtolower((string)$order->payment_channel)];
        }

        $token = \Midtrans\Snap::getSnapToken($params);

        // Simpan token agar 1 order tidak generate token berkali-kali
        $order->update([
            'snap_token' => $token,
            'payment_provider' => 'midtrans',
            'payment_channel' => $order->payment_channel ?: ($request->input('payment_channel') ?: null),
        ]);

        return response()->json(['token' => $token], 200);
    }
}
