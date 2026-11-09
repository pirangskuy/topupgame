<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        $orderId     = (string) ($payload['order_id'] ?? '');
        $statusCode  = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signature   = (string) ($payload['signature_key'] ?? '');

        $serverKey = (string) config('services.midtrans.server_key');
        $expected  = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);

        if (!$orderId || !hash_equals($expected, $signature)) {
            Log::warning('Midtrans webhook invalid signature', $payload);
            return response('invalid signature', 403);
        }

        $order = Order::where('order_code', $orderId)->first();
        if (!$order) {
            Log::warning('Midtrans webhook order not found: ' . $orderId, $payload);
            return response('order not found', 404);
        }

        $txStatus = (string) ($payload['transaction_status'] ?? '');
        $fraud    = (string) ($payload['fraud_status'] ?? '');
        $trxId    = (string) ($payload['transaction_id'] ?? '');

        // mapping status
        // settlement/capture = paid (untuk CC biasanya capture + accept)
        $newStatus = $order->status;

        if ($txStatus === 'settlement') {
            $newStatus = 'paid';
        } elseif ($txStatus === 'capture') {
            $newStatus = ($fraud === 'accept' || $fraud === '') ? 'paid' : 'pending';
        } elseif ($txStatus === 'pending') {
            $newStatus = 'pending';
        } elseif (in_array($txStatus, ['expire', 'cancel', 'deny'], true)) {
            $newStatus = 'failed';
        }

        $update = [
            'payment_provider' => 'midtrans',
            'midtrans_status' => $txStatus,
            'midtrans_transaction_id' => $trxId ?: $order->midtrans_transaction_id,
            'midtrans_raw' => $payload,
        ];

        // paid_at diisi pertama kali saat jadi paid
        if ($newStatus === 'paid' && empty($order->paid_at)) {
            $update['paid_at'] = now();
        }

        // update status order
        $update['status'] = $newStatus;

        $order->update($update);

        /**
         * NEXT (nanti kita sambung Hopestore):
         * Jika status jadi 'paid' dan belum delivered_at, panggil API Hopestore untuk kirim diamond.
         * Kalau sukses -> status='delivered' + delivered_at=now()
         */

        return response('ok', 200);
    }
}
