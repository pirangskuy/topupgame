<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MlbbReview;
use App\Models\Order;
use App\Models\Game;

class MlbbReviewController extends Controller
{
    private function mlbbGameId(): ?int
    {
        // Aman: kalau slug MLBB kamu beda, tetap ketemu
        $slugs = ['mlbb', 'mobile-legends', 'mobile-legends-bang-bang', 'mobile-legends-bangbang'];
        return Game::whereIn('slug', $slugs)->value('id');
    }

    public function index()
    {
        $reviews = MlbbReview::query()
            ->where('is_approved', true)
            ->latest()
            ->paginate(10);

        $avg = (float) MlbbReview::where('is_approved', true)->avg('rating');

        return view('mlbb.ulasan', compact('reviews', 'avg'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_code' => ['required', 'string', 'max:64'],
            'whatsapp'   => ['required', 'string', 'max:25'],
            'name'       => ['nullable', 'string', 'max:40'],
            'rating'     => ['required', 'integer', 'min:1', 'max:5'],
            'comment'    => ['required', 'string', 'min:5', 'max:500'],
        ]);

        // Cegah dobel ulasan
        if (MlbbReview::where('order_code', $data['order_code'])->exists()) {
            return back()->withErrors(['order_code' => 'Transaksi ini sudah pernah memberi ulasan.'])->withInput();
        }

        // Verifikasi transaksi: order_code + whatsapp harus cocok
        $q = Order::query()
            ->where('order_code', $data['order_code'])
            ->where('whatsapp', $data['whatsapp']);

        // Kalau bisa, pastikan ini transaksi MLBB
        $mlbbId = $this->mlbbGameId();
        if ($mlbbId) {
            $q->where('game_id', $mlbbId);
        }

        $order = $q->first();
        if (!$order) {
            return back()->withErrors([
                'order_code' => 'Transaksi MLBB tidak ditemukan / WhatsApp tidak cocok.'
            ])->withInput();
        }

        MlbbReview::create([
            'order_code'  => $data['order_code'],
            'name'        => $data['name'] ?: 'Pembeli',
            'rating'      => (int) $data['rating'],
            'comment'     => $data['comment'],
            'is_approved' => true, // langsung tampil (kalau mau moderasi: ubah false)
        ]);

        return back()->with('ok', 'Ulasan berhasil dikirim. Terima kasih!');
    }
}
