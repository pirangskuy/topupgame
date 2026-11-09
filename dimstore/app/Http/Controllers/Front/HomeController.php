<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Game;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Banner aktif
        $banners = Banner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->latest('id')
            ->get();

        // Kategori: urutkan sesuai sort_order (yang paling kecil = paling kiri)
        $categories = Category::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Ambil category dari query string: ?category=ID
        $categoryId = $request->integer('category');

        // Kalau tidak ada tombol "Semua/All", maka default pilih kategori pertama
        if (!$categoryId && $categories->isNotEmpty()) {
            $categoryId = (int) $categories->first()->id;
        }

        // Game aktif: kalau ada categoryId maka filter, kalau tidak ada maka tampilkan game umum
        $popularGames = Game::query()
            ->where('is_active', true)
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->latest('id')
            ->limit(24)
            ->get();

        return view('front.home', compact('banners', 'categories', 'popularGames', 'categoryId'));
    }
}
