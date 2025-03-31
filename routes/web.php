<?php 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ConfirmController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Client\BlogController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\UserController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Client\AboutController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\InvoiceController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\ProductsController;
use App\Http\Controllers\Client\ServicesController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Client\ProductDetailController;

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


Auth::routes(['verify' => true]);


Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/password/reset', 'showLinkRequestForm')->name('password.request');
    Route::post('/password/email', 'sendResetLinkEmail')->name('password.email');
});

Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('/password/reset/{token}', 'showResetForm')->name('password.reset');
    Route::post('/password/reset', 'reset')->name('password.update');
});


Route::controller(VerificationController::class)->group(function () {
    Route::get('/email/verify', 'show')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verify')->name('verification.verify');
    Route::post('/email/resend', 'resend')->name('verification.resend');
});


Route::controller(ConfirmPasswordController::class)->group(function () {
    Route::get('/password/confirm', 'showConfirmForm')->name('password.confirm');
    Route::post('/password/confirm', 'confirm');
});


Route::get('/home', [HomeController::class, 'index'])->name(name: 'home')->middleware(['auth', 'verified']);

// Route cho trang chính (home) sau khi đăng nhập cho người dùng thông thường
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductsController::class, 'index'])->name('products');
Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/about', [AboutController::class, 'index'])->name('about');
// Route::get('/userclient', [UserController::class, 'index'])->name('userclient');
Route::put('/userclient/update', [UserController::class, 'updateProfile'])->name('userclient.update');
Route::get('/product/{product}', [ProductDetailController::class, 'index'])->name('product.details');

// Nhóm route cho admin với prefix '/admin', middleware 'auth' và 'admin'
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'as' => 'admin.'], function () {
    
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'index']);
    
    Route::resource('categories', CategoryController::class);

    Route::resource('brands', BrandController::class);

    Route::resource('products', ProductController::class);

    Route::resource('colors', ColorController::class);

    Route::resource('sizes', SizeController::class);

    Route::get('/users', [CustomerController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [CustomerController::class, 'show'])->name('users.show');
    Route::delete('/users/{id}', [CustomerController::class, 'destroy'])->name('users.destroy');

    // Routes cho biến thể sản phẩm 
    Route::prefix('products/{product}/variants')->group(function () {
        Route::get('/', [ProductVariantController::class, 'index'])->name('product_variants.index');
        Route::get('/create', [ProductVariantController::class, 'create'])->name('product_variants.create');
        Route::post('/', [ProductVariantController::class, 'store'])->name('product_variants.store');
        Route::get('/{variant}/edit', [ProductVariantController::class, 'edit'])->name('product_variants.edit');
        Route::put('/{variant}', [ProductVariantController::class, 'update'])->name('product_variants.update');
        Route::delete('/{variant}', [ProductVariantController::class, 'destroy'])->name('product_variants.destroy');
    });

     // Route danh sách tất cả sản phẩm có biến thể
     Route::get('/product-variants', [ProductVariantController::class, 'productsWithVariants'])
     ->name('product_variants.list');
     // Route tìm kiếm sản phẩm có biến thể
    Route::get('/product-variants/search', [ProductVariantController::class, 'search'])
    ->name('product_variants.search');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\Client\HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    Route::post('/cart/sync', [CartController::class, 'syncCart']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
});

// Thanh toán cod
Route::get('/order', [OrderController::class, 'index'])->name('order');
Route::get('/confirm/cod', [ConfirmController::class, 'Confirm_cod'])->name('confirm.cod');
Route::post('/checkout', [PaymentController::class, 'CodPayment'])->name('checkout.process');

// thanh toán vnpay
Route::get('/confirm/vnpay', [ConfirmController::class, 'Confirm_vnpay'])->name('confirm.vnpay');
Route::post('/checkout/vnpay', [PaymentController::class, 'vnpayPayment'])->name('checkout.vnpay');
Route::get('/thanks/vnpay', [PaymentController::class, 'xuly'])->name('thanks.vnpay');
// thankyou
Route::get('/thankyou', [PaymentController::class, 'thankyou'])->name('thankyou');
//history
Route::get('/lich-su-giao-dich', [UserController::class, 'transactionHistory'])->name('transactions.history')->middleware('auth');

// show hoá đơn
Route::get('/invoice/{orderId}', [InvoiceController::class, 'show'])->name('invoice.show');
// tải hoá đơn
Route::get('/invoice/download/{orderId}', [InvoiceController::class, 'downloadPDF'])->name('invoice.download');

Route::middleware(['auth'])->group(function () {
    Route::get('/account', [UserController::class, 'index'])->name('account');
    Route::put('/account', [UserController::class, 'update'])->name('account.update');
    Route::get('/account/orders', [UserController::class, 'showOrder'])->name('account.orders');
    Route::get('/account/orders/{order}', [UserController::class, 'show'])->name('account.orders.show');
});
?>


