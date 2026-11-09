<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function show(Request $request, Game $game)
    {
        abort_unless($game->is_active, 404);

        // 1) Ambil semua nama paket (group) yang ada untuk game ini (DINAMIS)
        $groups = $game->denominations()
            ->where('is_active', true)
            ->select('group')
            ->distinct()
            ->orderBy('group') // urut alfabet; kalau kamu punya group_order sendiri, kita bisa ganti
            ->pluck('group');

        // 2) Paket aktif: dari URL (?group=...) atau default paket pertama
        $activeGroup = $request->string('group')->toString();
        if ($activeGroup === '' && $groups->isNotEmpty()) {
            $activeGroup = (string) $groups->first();
        }

        // 3) Denom: hanya untuk paket aktif
        $denoms = $game->denominations()
            ->where('is_active', true)
            ->when($activeGroup !== '', fn ($q) => $q->where('group', $activeGroup))
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();

        $payments = PaymentMethod::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        return view('front.game_show', compact('game', 'denoms', 'payments', 'groups', 'activeGroup'));
    }
}
