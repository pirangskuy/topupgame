<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderCheckController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'order_code' => ['nullable', 'string', 'max:50'],
        ]);

        $order = null;
        $notFound = false;

        if (!empty($data['order_code'])) {
            $order = Order::query()
                ->with(['game', 'denomination', 'paymentMethod'])
                ->where('order_code', $data['order_code'])
                ->first();

            $notFound = $order === null;
        }

        return view('front.cek_pesanan', compact('order', 'notFound'));
    }
}
