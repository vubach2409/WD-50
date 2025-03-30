<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\SizeController;

use App\Http\Controllers\Client\AboutController;
use App\Http\Controllers\Client\BlogController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\ProductsController;
use App\Http\Controllers\Client\ServicesController;
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

Auth::routes();

// Route cho trang chính (home) sau khi đăng nhập cho người dùng thông thường
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductsController::class, 'index'])->name('products');
Route::get('/products/{product}', [ProductsController::class, 'show'])->name('products.show');
Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::get('/thankyou', [PaymentController::class, 'xulythanhtoantienmat'])->name('thankyou');

// Nhóm route cho admin với prefix '/admin', middleware 'auth' và 'admin'
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'as' => 'admin.'], function () {
    
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    
    Route::resource('categories', CategoryController::class);

    Route::resource('brands', BrandController::class);

    Route::resource('products', ProductController::class);

    Route::resource('colors', ColorController::class);

    Route::resource('sizes', SizeController::class);

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

