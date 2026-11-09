<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    public function index(Request $request)
    {
        $gameId = $request->integer('game'); // ?game=ID

        // Dropdown game (hanya yang aktif)
        $games = Game::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Default: ambil game pertama kalau belum pilih
        if (!$gameId && $games->isNotEmpty()) {
            $gameId = (int) $games->first()->id;
        }

        // Ambil game terpilih + denominations (aktif) dikelompokkan per paket
        $selectedGame = null;
        $groups = collect();

        if ($gameId) {
            $selectedGame = Game::query()
                ->with(['denominations' => function ($q) {
                    $q->where('is_active', true)
                      ->orderBy('group')
                      ->orderBy('sort_order')
                      ->orderBy('price');
                }])
                ->find($gameId);

            if ($selectedGame) {
                $groups = $selectedGame->denominations->groupBy('group');
            }
        }

        return view('front.price-list', compact('games', 'selectedGame', 'groups', 'gameId'));
    }
}
