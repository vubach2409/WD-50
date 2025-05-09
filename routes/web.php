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
//comment trong sản phẩm
Route::post('/product/{product}/comment', [ProductDetailController::class, 'comment'])->middleware('auth')->name('product.comment');

// đánh giá sản phẩm trong đơn hàng
Route::get('/orders/{order}/feedback', [OrderController::class, 'feedbackForm'])->name('orders.feedback');
Route::post('/orders/{order}/feedback', [OrderController::class, 'submitFeedback'])->name('orders.feedback.submit');
//áp mã giảm giá
Route::post('/cart/apply-voucher', [CartController::class, 'applyVoucher'])->name('cart.apply-voucher');
Route::post('/cart/remove-voucher', [CartController::class, 'removeVoucher'])->name('cart.remove-voucher');
Route::get('/vouchers', [CartController::class, 'listAvailableVouchers'])->name('cart.voucher-list');
Route::post('/chat/send', function (Request $request) {
    $message = $request->input('message');
    $lower = Str::lower($message);

    // Kiểm tra từ khóa loại sản phẩm (danh mục)
    $type = '';
    if (Str::contains($lower, 'ghế')) $type = 'ghế';
    elseif (Str::contains($lower, 'bàn')) $type = 'bàn';
    elseif (Str::contains($lower, 'phụ kiện')) $type = 'phụ kiện';

    // Kiểm tra giá
    preg_match('/(\d+)[\s\.,]*k|(\d+)[\s\.,]*triệu/i', $lower, $matches);
    $price = 0;
    if (isset($matches[1])) $price = (int)$matches[1] * 1000;
    elseif (isset($matches[2])) $price = (int)$matches[2] * 1000000;

    // Lấy category_id từ bảng categories
    $category = Category::where('name', 'like', '%' . $type . '%')->first();

    if (!$category) {
        return response()->json(['reply' => '<div class="text-muted">Xin lỗi, chúng tôi không tìm thấy danh mục phù hợp.</div>']);
    }

    // Truy vấn sản phẩm theo danh mục
    $query = Product::query();
    $query->where('category_id', $category->id);

    if ($price > 0) {
        $query->where('price_sale', '<=', $price);
    }

    $products = $query->take(3)->get();

    // Kiểm tra mã giảm giá còn hiệu lực
    $event_message = '';

    $voucher = Voucher::where('starts_at', '<=', now())
                      ->where('expires_at', '>=', now())
                      ->where('is_active', true)
                      ->first();

    if ($voucher) {
        // Nếu có voucher, hiển thị thông báo mã giảm giá
        $event_message = '<div class="alert alert-info mb-2">Thông báo: Bạn có thể sử dụng mã giảm giá <strong>' . $voucher->code . '</strong> ';
        if ($voucher->type == 'percent') {
            $event_message .= 'giảm ' . $voucher->value . '% cho đơn hàng.';
        } else {
            $event_message .= 'giảm ' . number_format($voucher->value, 0, ',', '.') . 'đ cho đơn hàng.';
        }

        if ($voucher->min_order_amount) {
            $event_message .= ' Đơn hàng tối thiểu ' . number_format($voucher->min_order_amount, 0, ',', '.') . 'đ.';
        }
        $event_message .= ' Bạn muốn sử dụng không?</div>';
    }

    // Nếu không tìm thấy sản phẩm
    if ($products->isEmpty()) {
        return response()->json(['reply' => $event_message . '<div class="text-muted">Không tìm thấy sản phẩm phù hợp.</div>']);
    }

    // Xây dựng phản hồi với thông tin sản phẩm
    $response = $event_message;
    $response .= '<div class="chat-products">';
    $response .= '<div class="mb-2 fw-semibold text-success">Đây là sản phẩm thích hợp nhất với yêu cầu của bạn:</div>';
    foreach ($products as $product) {
        $link = route('product.details', $product->id);
        $response .= '
            <div class="product mb-2 p-2 border rounded bg-light">
            <img src="' . asset('storage/' . $product->image) . '" alt="Ảnh" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                <a href="' . $link . '" class="text-decoration-none fw-bold text-primary" target="_blank">'
                    . e($product->name) . 
                '</a>
                <div>Giá: ';

        // Hiển thị giá sản phẩm
        $response .= '<span class="text-danger fw-semibold">' . number_format($product->price_sale, 0, ',', '.') . 'đ</span>';

        $response .= '</div>
            </div>';
    }
    $response .= '</div>';

    return response()->json(['reply' => $response]);
})->name('chat.send');












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
    Route::get('/products/product_detail', [ProductController::class, 'show'])->name('products.show');

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
//voucher
Route::resource('vouchers', VoucherController::class);


Route::get('/order/show', [AdminOrderController::class, 'index'])->name('orders.show');
Route::get('/orders/detail/{id}', [AdminOrderController::class, 'show'])->name('orders.detail');
Route::put('/admin/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');


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
        
        Route::get('/trash', [ProductVariantController::class, 'trash'])->name('product_variants.trash');
        Route::post('/{variant}/restore', [ProductVariantController::class, 'restore'])->name('product_variants.restore');
        Route::delete('/{variant}/force-delete', [ProductVariantController::class, 'forceDelete'])->name('product_variants.forceDelete');

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
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/confirm/paymnet', [PaymentController::class, 'PaymentOnline'])->name('checkout.process');

// // thanh toán vnpay

// Route::post('/confirm/vnpay', [PaymentController::class, 'vnpayPayment'])->name('checkout.process');

// Route::middleware('handle.payment')->group(function () {
//     Route::post('/confirm/payment', [PaymentController::class, 'CodPayment']);
//     Route::post('/confirm/vnpay', [PaymentController::class, 'vnpayPayment']);
// })->name('checkout.process');

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
?>


