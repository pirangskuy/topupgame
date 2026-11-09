<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Denomination;
use App\Models\Game;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    private array $midtransChannels = [
        'gopay',
        'shopeepay',
        'qris',
        'bca_va',
        'bni_va',
        'bri_va',
        'mandiri_va',
        'permata_va',
        'cimb_va',
        'danamon_va',
        'maybank_va',
        'bsi_va',
        'neo_va',
        'bjb_va',
    ];

    public function store(Request $request)
    {
        // 1) Validasi minimal untuk ambil game
        $base = $request->validate([
            'game_id'         => ['required', 'integer'],
            'denomination_id' => ['required', 'integer'],
        ]);

        $game = Game::query()
            ->where('id', $base['game_id'])
            ->where('is_active', true)
            ->firstOrFail();

        // 2) Validasi lengkap
        $data = $request->validate([
            'game_id'           => ['required', 'integer'],
            'denomination_id'   => ['required', 'integer'],

            // manual payment
            'payment_method_id' => ['required_without:payment_channel', 'nullable', 'integer'],

            // midtrans payment channel
            'payment_channel'   => ['nullable', 'string', 'max:30'],

            'user_id'   => ['required', 'string', 'max:50'],
            'server_id' => $game->requires_server
                ? ['required', 'string', 'max:50']
                : ['nullable', 'string', 'max:50'],

            'whatsapp'  => ['required', 'string', 'max:20'],
        ]);

        // 3) Denom anti manipulasi
        $denom = Denomination::query()
            ->where('id', $data['denomination_id'])
            ->where('game_id', $game->id)
            ->where('is_active', true)
            ->firstOrFail();

        // 4) Ambil payment method (kalau manual)
        $payment = null;
        if (!empty($data['payment_method_id'])) {
            $payment = PaymentMethod::query()
                ->where('id', $data['payment_method_id'])
                ->where('is_active', true)
                ->first();
        }

        // 5) Tentukan apakah Midtrans dan channel-nya
        $paymentChannel = $data['payment_channel'] ?? null;
        $isMidtrans = false;

        if (is_string($paymentChannel) && $paymentChannel !== '') {
            $paymentChannel = strtolower($paymentChannel);

            if (!in_array($paymentChannel, $this->midtransChannels, true)) {
                return back()
                    ->withErrors(['payment_channel' => 'Metode Midtrans tidak valid / belum tersedia.'])
                    ->withInput();
            }

            $isMidtrans = true;
        }

        // opsi: jika payment_method_id menunjuk ke metode midtrans
        if (!$isMidtrans && $payment) {
            $code = null;

            if (isset($payment->code) && is_string($payment->code) && $payment->code !== '') {
                $code = strtolower($payment->code);
            } else {
                $code = Str::slug((string) $payment->name, '_');
            }

            if (in_array($code, $this->midtransChannels, true)) {
                $isMidtrans = true;
                $paymentChannel = $code;
            } else {
                $isMidtrans = str_contains(strtolower((string) $payment->name), 'midtrans');
            }
        }

        // 6) Generate order code unik (pakai order_code)
        do {
            $orderCode = 'DIM-' . now()->format('ymd') . '-' . strtoupper(Str::random(6));
        } while (Order::where('order_code', $orderCode)->exists());

        // 7) Payload order (isi kolom baru + legacy agar aman)
        $payload = [
            // V2
            'order_code'        => $orderCode,
            'status'            => 'pending',
            'game_id'           => $game->id,
            'denomination_id'   => $denom->id,
            'payment_method_id' => $payment?->id,

            'user_id'      => $data['user_id'],
            'server_id'    => $data['server_id'] ?? null,
            'whatsapp'     => $data['whatsapp'],
            'total_amount' => (int) $denom->price,

            'payment_provider' => $isMidtrans ? 'midtrans' : 'manual',
            'payment_channel'  => $paymentChannel,

            // legacy (biar view lama tidak rusak)
            'code'          => $orderCode,
            'user_id_value' => $data['user_id'],
            'server_value'  => $data['server_id'] ?? null,
            'price'         => (int) $denom->price,
            'note'          => $paymentChannel,
        ];

        // Kalau kolom v2 belum ada (misal kamu belum migrate), hapus dari payload supaya tidak error SQL
        // (Tapi aku sarankan kolom v2 + midtrans kamu tambahkan lewat migration)
        foreach ([
            'total_amount','user_id','server_id',
            'payment_provider','payment_channel'
        ] as $col) {
            if (!Schema::hasColumn('orders', $col)) {
                unset($payload[$col]);
            }
        }

        $order = Order::create($payload);

        // 8) AJAX: balikin JSON untuk JS di Blade kamu
        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'order_code' => $order->order_code,
                'invoice_url' => route('invoice.show', ['order' => $order->order_code]),
                'is_midtrans' => $isMidtrans,
                'payment_channel' => $paymentChannel,
            ], 201);
        }

        // 9) Non-AJAX
        if ($isMidtrans) {
            // kalau kamu punya route midtrans.pay, ok. Kalau tidak ada, hapus baris ini
            return redirect()->route('midtrans.pay', ['order' => $order->order_code]);
        }

        return redirect()->route('invoice.show', ['order' => $order->order_code]);
    }

    public function show(Order $order)
    {
        $order->load(['game', 'denomination', 'paymentMethod']);
        return view('front.order_show', compact('order'));
    }
}
