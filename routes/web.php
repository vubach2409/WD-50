<?php 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\BlogController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\AboutController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\PaymentController;
// use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ProductsController;
use App\Http\Controllers\Client\ServicesController;
use App\Http\Controllers\ConfirmController;

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
// Route::get('/cart', [CartController::class, 'index'])->name('cart');
// Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

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

Route::get('/home', [App\Http\Controllers\Client\HomeController::class, 'index'])->name('home');
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

// Thanh toán cod
Route::get('/order', [OrderController::class, 'index'])->name('order');
Route::get('/confirm/cod', [ConfirmController::class, 'Confirm_cod'])->name('confirm.cod');
Route::post('/checkout', [PaymentController::class, 'CodPayment'])->name('checkout.process');


Route::get('/confirm/vnpay', [ConfirmController::class, 'Confirm_vnpay'])->name('confirm.vnpay');
Route::post('/checkout/vnpay', [PaymentController::class, 'vnpayPayment'])->name('checkout.vnpay');
Route::get('/thanks/vnpay', [PaymentController::class, 'xuly'])->name('thanks.vnpay');

Route::get('/thankyou', [PaymentController::class, 'thankyou'])->name('thankyou');
// Route::post('/confirm', [PaymentController::class, 'xuly'])->name('confirm');

?>


