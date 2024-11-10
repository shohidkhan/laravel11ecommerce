<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AuthAdminMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get("/shop", [ShopController::class, 'index'])->name('shop.index');
Route::get("/product-details/{slug}", [ShopController::class, 'product_details'])->name('shop.product.details');
Route::get("/cart", [CartController::class, 'index'])->name('cart.index');
Route::get("/wishlists", [WishlistController::class, 'index'])->name('wishlist.index');
//user
Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
    Route::post("/add-to-cart", [CartController::class, 'addToCart'])->name('add-to-cart');
    Route::get("/remove-cart/{id}", [CartController::class, 'removeCart'])->name('remove-cart');
    Route::put("/update-cart", [CartController::class, 'updateCart'])->name('update-cart');
    Route::delete('/clear-cart', [CartController::class, 'clearCart'])->name('clear.cart');

    Route::post("/add-to-wishlist", [WishlistController::class, 'addToWishlist'])->name('add.to.wishlist');
    Route::get("/remove-wishlist/{id}", [WishlistController::class, 'removeWishlist'])->name('remove-wishlist');
    Route::delete("/remove-wishlist", [WishlistController::class, 'clearWishlist'])->name('clear.whishlist');

    //apply coupon
    Route::post("/apply-coupon", [CartController::class, 'applyCoupon'])->name('apply.coupon');
    Route::delete("/remove-coupon", [CartController::class, 'removeCoupon'])->name('remove.coupon');	

});
//admin
Route::middleware(['auth', AuthAdminMiddleware::class])->group(function () {
    // Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        //brand in backend
        Route::get("/brands", [BrandController::class, 'index'])->name('brand.index');
        Route::get("/add-brand", [BrandController::class, 'create'])->name('brand.create');
        Route::post("/store-brand", [BrandController::class, 'store'])->name('brand.store');
        Route::get("/edit-brand/{id}", [BrandController::class, 'edit'])->name('brand.edit');
        Route::put("/update-brand/{id}", [BrandController::class, 'update'])->name('brand.update');
        Route::delete("/delete-brand/{id}", [BrandController::class, 'destroy'])->name('brand.destroy');

        //category in backend

        Route::get("/categories", [CategoryController::class, 'index'])->name('category.index');
        Route::get("/add-category", [CategoryController::class, 'create'])->name('category.create');
        Route::post("/stroe-category", [CategoryController::class, 'store'])->name('category.store');
        Route::get("/edit-category/{id}", [CategoryController::class, 'edit'])->name('category.edit');
        Route::put("/update-category/{id}", [CategoryController::class, 'update'])->name('category.update');
        Route::delete("/delete-category/{id}", [CategoryController::class, 'destroy'])->name('category.destroy');

        //Products in backend

        Route::get("/products", [ProductController::class, 'index'])->name('product.index');
        Route::get("/add-product", [ProductController::class, 'create'])->name('product.create');
        Route::post("/store-product", [ProductController::class, 'store'])->name('product.store');
        Route::get("/edit-product/{id}", [ProductController::class, 'edit'])->name('product.edit');
        Route::put("/update-product/{id}", [ProductController::class, 'update'])->name('product.update');
        Route::delete("/delete-product/{id}", [ProductController::class, 'destroy'])->name('product.destroy');
        Route::get("/product-status-change/{id}", [ProductController::class, 'productStatusChange'])->name('product.status.change');

        //Coupons in backend
        Route::get("/coupons", [CouponController::class, 'index'])->name('coupon.index');
        Route::get("/add-coupon", [CouponController::class, 'create'])->name('coupon.create');
        Route::post("/store-coupon", [CouponController::class, 'store'])->name('coupon.store');
        Route::get("/edit-coupon/{id}", [CouponController::class, 'edit'])->name('coupon.edit');
        Route::put("/update-coupon/{id}", [CouponController::class, 'update'])->name('coupon.update');
        Route::delete("/delete-coupon/{id}", [CouponController::class, 'destroy'])->name('coupon.destroy');
    });
});
