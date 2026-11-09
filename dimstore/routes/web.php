<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\{
    HomeController,
    GameController,
    OrderController,
    OrderCheckController,
    GiftSkinController,
    PriceListController,
    ContactController,
    TermsController,
    MlbbReviewController,
    MlbbToolsController,
    MidtransController
};

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/games/{game:slug}', [GameController::class, 'show'])->name('games.show');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');

Route::get('/cek-pesanan', [OrderCheckController::class, 'index'])->name('order.check');
Route::get('/invoice/{order:order_code}', [OrderController::class, 'show'])->name('invoice.show');

Route::get('/jadwal-gift-skin', [GiftSkinController::class, 'index'])->name('gift.skin.schedule');
Route::get('/daftar-harga', [PriceListController::class, 'index'])->name('price.list');
Route::get('/kontak', [ContactController::class, 'index'])->name('contact');
Route::get('/syarat-ketentuan', [TermsController::class, 'index'])->name('terms');
// ✅ MIDTRANS
Route::post('/midtrans/token/{order:order_code}', [MidtransController::class, 'token'])
    ->name('midtrans.token');

Route::get('/pay/midtrans/{order:order_code}', [MidtransController::class, 'redirect'])
    ->name('midtrans.pay');



// ✅ MLBB
Route::prefix('mlbb')->as('mlbb.')->group(function () {

    // ✅ 1 HALAMAN TOOLS (pakai resources/views/front/tools.blade.php)
    Route::get('/tools', function () {
        return view('front.tools');
    })->name('tools');

    // ✅ Endpoint untuk cek nickname (harus di /mlbb supaya cocok dengan JS kamu)
    Route::post('/inquiry-nickname', [MlbbToolsController::class, 'inquiryNickname'])
        ->middleware('throttle:10,1')
        ->name('inquiry.nickname');

    // (Opsional) Ulasan
    Route::get('/ulasan', [MlbbReviewController::class, 'index'])->name('reviews');
    Route::post('/ulasan', [MlbbReviewController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('reviews.store');
});
