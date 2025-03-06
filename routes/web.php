<?php

use App\Http\Controllers\Admin\CategoryController;
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