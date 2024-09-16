<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdminMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get("/shop", [ShopController::class, 'index'])->name('shop.index');
Route::get("/product-details/{slug}", [ShopController::class, 'product_details'])->name('shop.product.details');
//user
Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
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
    });
});
