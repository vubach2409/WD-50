<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route cho trang chính (home) sau khi đăng nhập cho người dùng thông thường
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Nhóm route cho admin với prefix '/admin', middleware 'auth' và 'admin'
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'as' => 'admin.'], function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // Thêm các route khác nếu cần cho các trang trong template SB Admin 2
    // Ví dụ:
    // Route::get('/users', [AdminController::class, 'users'])->name('users');
    // Route::get('/charts', [AdminController::class, 'charts'])->name('charts');
    // Route::get('/tables', [AdminController::class, 'tables'])->name('tables');
});

Route::prefix('admin')->group(function () {
    Route::resource('categories', CategoryController::class);
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    Route::post('/cart/sync', [CartController::class, 'syncCart']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
});


Route::get('/order', [OrderController::class, 'index'])->name('order');
Route::post('/order/process', [OrderController::class, 'process'])->name('order.process');

