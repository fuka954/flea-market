<?php

use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;

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

// Route::get('/register', function () {
//     return view('register');
// });

// Route::get('/login', function () {
//     return view('login');
// });

// Route::get('/mypage/profile', function () {
//     return view('profile');
// });
Route::middleware('check.profile')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
});
// Route::post('/register', [UserController::class, 'store']);
Route::post('/mypage/profile', [ProfileController::class, 'update']);
Route::get('/mypage/profile', [ProfileController::class, 'edit']);
// Route::get('/mypage/profile/{id}', [ProfileController::class, 'edit']);
// Route::GET('/', [UserController::class, 'store']);
