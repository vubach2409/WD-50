<?php 
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Client\ProductsController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\BlogController;
use App\Http\Controllers\Client\ServicesController;
use App\Http\Controllers\Client\AboutController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\PaymentController;

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


Auth::routes();

// Route cho trang chính (home) sau khi đăng nhập cho người dùng thông thường
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductsController::class, 'index'])->name('products');
Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::get('/thankyou', [PaymentController::class, 'xulythanhtoantienmat'])->name('thankyou');

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

// Route::get('/home',HomeController::class, 'index')->name('home')->middleware('auth');

?>


