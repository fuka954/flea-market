<?php

use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Route;
// use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['check.profile'])->group(function () {
    Route::get('/', [ProductController::class, 'index']);
});

Route::post('/', [ProductController::class, 'search']);
Route::get('/item/{itemId}', [ProductController::class, 'show']);
Route::post('/item/{itemId}/favorite', [ProductController::class, 'favorite']);
Route::post('/item/{itemId}/comment', [ProductController::class, 'comment']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [ProfileController::class, 'show'])->middleware('check.profile');
    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    Route::post('/mypage/profile', [ProfileController::class, 'update']);
    Route::get('/sell', [ProductController::class, 'createSell'])->middleware('check.profile');
    Route::post('/sell', [ProductController::class, 'storeSell']);
    Route::get('/purchase/{itemId}', [ProductController::class, 'createBuy']);
    Route::post('/purchase/{itemId}', [ProductController::class, 'storeBuy']);
    Route::get('/purchase/address/{itemId}', [ProductController::class, 'edit']);
    Route::post('/purchase/address/{itemId}', [ProductController::class, 'update']);
});

Route::get('/email/verify', function () {
    return view('auth.verify-email'); 
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    Auth::logout();
    return redirect('/login')->with('verifyMessage', 'メール認証が完了しました！');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/stripe/success/{itemId}', [StripeController::class, 'success'])->name('stripe.success');
Route::get('/stripe/cancel/{itemId}', [StripeController::class, 'cancel'])->name('stripe.cancel');