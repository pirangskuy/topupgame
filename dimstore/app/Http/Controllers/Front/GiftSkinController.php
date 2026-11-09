<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\GiftSkinSchedule;
use Illuminate\Http\Request;

class GiftSkinController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all'); // all|done|progress
        if (!in_array($status, ['all', 'done', 'progress'], true)) {
            $status = 'all';
        }

        $perPage = (int) $request->get('perPage', 10);
        $allowedPerPage = [10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $q = trim((string) $request->get('q', ''));

        $query = GiftSkinSchedule::query()->orderByDesc('order_date');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('nickname', 'like', "%{$q}%")
                  ->orWhere('user_id', 'like', "%{$q}%");
            });
        }

        $rows = $query->paginate($perPage)->appends($request->query());

        return view('front.jadwal_gift_skin', compact('rows', 'status', 'perPage', 'q'));
    }
}
