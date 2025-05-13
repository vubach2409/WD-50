<?php

use App\Models\Product;
use App\Models\Voucher;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
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
use App\Http\Controllers\Client\AboutController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\InvoiceController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\FeedbackController;
use App\Http\Controllers\Client\ProductsController;
use App\Http\Controllers\Client\ServicesController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\HistoryPaymentController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Client\ProductDetailController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;

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
    // Trang hiển thị form nhập email quên mật khẩu (React)
    Route::get('/forgot-password', 'showLinkRequestForm')->name('password.request');
    // Xử lý việc gửi email chứa link đặt lại mật khẩu (API endpoint cho React)
    Route::post('/forgot-password', 'sendResetLinkEmail')->name('password.email');
});

Route::controller(ResetPasswordController::class)->group(function () {
    // Trang hiển thị form nhập mật khẩu mới (React, truy cập qua link trong email)
    Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
    // Xử lý việc cập nhật mật khẩu mới (API endpoint cho React)
    Route::post('/reset-password', 'reset')->name('password.update');
});

// Route này để backend có thể trả về token và email cho frontend
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
//comment trong sản phẩm
Route::post('/product/{product}/comment', [ProductDetailController::class, 'comment'])->middleware('auth')->name('product.comment');

// đánh giá sản phẩm trong đơn hàng
Route::get('/orders/{order}/feedback', [OrderController::class, 'feedbackForm'])->name('orders.feedback');
Route::post('/orders/{order}/feedback', [OrderController::class, 'submitFeedback'])->name('orders.feedback.submit');
//áp mã giảm giá
Route::post('/cart/apply-voucher', [CartController::class, 'applyVoucher'])->name('cart.apply-voucher');
Route::post('/cart/remove-voucher', [CartController::class, 'removeVoucher'])->name('cart.remove-voucher');
Route::get('/vouchers', [CartController::class, 'listAvailableVouchers'])->name('cart.voucher-list');

// Nhóm route cho admin với prefix '/admin', middleware 'auth' và 'admin'
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'as' => 'admin.'], function () {

    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'index']);

    Route::resource('categories', CategoryController::class);

    Route::resource('brands', BrandController::class);

    Route::get('products/trash', [ProductController::class, 'trash'])->name('products.trash');
    Route::post('products/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/force-delete/{id}', [ProductController::class, 'forceDelete'])->name('products.forceDelete');

    Route::resource('products', ProductController::class);

    Route::get('products/detail/{id}', [ProductController::class, 'show'])->name('products.detail');

    Route::resource('colors', ColorController::class);
    //show hoá đơn
    Route::get('/invoice/{orderId}', [HistoryPaymentController::class, 'show'])->name('invoice.show');
    // tải hoá đơn
    Route::get('/invoice/download/{orderId}', [HistoryPaymentController::class, 'downloadPDF'])->name('invoice.download');
    //lịch sử mua hàng và giao dịch
    Route::get('/payment/history', [HistoryPaymentController::class, 'index'])->name('payment.history');
    Route::get('/history/detail/{id}', [HistoryPaymentController::class, 'history_detail'])->name('history.detail');
    // Show và cập nhật trạng thái thanh toán
    Route::get('/payment/show', [AdminPaymentController::class, 'showOrderPayments'])->name('payment.show');
    Route::put('/admin/order/{orderId}/update-payment-status', [AdminPaymentController::class, 'updatePaymentStatus'])->name('update.payment.status');
    // Route để lọc đơn hàng theo mã giao dịch
    Route::get('/admin/orders/filter', [AdminPaymentController::class, 'filterOrders'])->name('orders.filter');
    // Feedbacks
    Route::get('/feedbacks', [AdminFeedbackController::class, 'index'])->name('feedbacks.index');
    Route::delete('/feedbacks/{id}', [AdminFeedbackController::class, 'destroy'])->name('feedbacks.destroy');
    Route::patch('/feedbacks/{id}/toggle-hide', [AdminFeedbackController::class, 'toggleHide'])->name('feedbacks.toggleHide'); // Route cho toggleHide

    //voucher
    Route::resource('vouchers', VoucherController::class);


    Route::get('/order/show', [AdminOrderController::class, 'index'])->name('orders.show');
    Route::get('/orders/detail/{id}', [AdminOrderController::class, 'show'])->name('orders.detail');
    Route::put('/admin/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');


    Route::resource('sizes', SizeController::class);

    Route::get('/users', [CustomerController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [CustomerController::class, 'show'])->name('users.show');
    Route::delete('/users/{id}', [CustomerController::class, 'destroy'])->name('users.destroy');

Route::get('admin/users/{id}/edit', [CustomerController::class, 'edit'])->name('users.edit');

// Route xử lý cập nhật thông tin người dùng
Route::put('admin/users/{id}', [CustomerController::class, 'update'])->name('users.update');


    // Routes cho biến thể sản phẩm
    Route::prefix('products/{product}/variants')->group(function () {
        Route::get('/', [ProductVariantController::class, 'index'])->name('product_variants.index');
        Route::get('/create', [ProductVariantController::class, 'create'])->name('product_variants.create');
        Route::post('/', [ProductVariantController::class, 'store'])->name('product_variants.store');

        Route::get('/{variant}/edit', [ProductVariantController::class, 'edit'])->name('product_variants.edit');
        Route::put('/{variant}', [ProductVariantController::class, 'update'])->name('product_variants.update');
        Route::delete('/{variant}', [ProductVariantController::class, 'destroy'])->name('product_variants.destroy');

        Route::get('/trash', [ProductVariantController::class, 'trash'])->name('product_variants.trash');
        Route::post('/{variant}/restore', [ProductVariantController::class, 'restore'])->name('product_variants.restore');
        Route::delete('/{variant}/force-delete', [ProductVariantController::class, 'forceDelete'])->name('product_variants.forceDelete');

        Route::get('/{variant}', [ProductVariantController::class, 'show'])->name('product_variants.show');
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
    
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/mini-cart', [CartController::class, 'miniCart'])->name('mini.cart');
});


// Thanh toán cod
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/confirm/paymnet', [PaymentController::class, 'PaymentOnline'])->name('checkout.process');

// // thanh toán vnpay


Route::get('/thanks/vnpay', [PaymentController::class, 'xuly'])->name('thanks.vnpay');
// thankyou
Route::get('/thankyou', [PaymentController::class, 'thankyou'])->name('thankyou');
//history
Route::get('/lich-su-giao-dich', [UserController::class, 'transactionHistory'])->name('transactions.history')->middleware('auth');

// show hoá đơn
Route::get('/invoice/{orderId}', [InvoiceController::class, 'show'])->name('invoice.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/account', [UserController::class, 'index'])->name('account');
    Route::put('/account', [UserController::class, 'update'])->name('account.update');
    Route::get('/account/orders', [OrderController::class, 'showOrder'])->name('account.orders');
    Route::get('/account/orders/{order}', [OrderController::class, 'show'])->name('account.orders.show');
    Route::put('/account/orders/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('account.orders.cancel');
});