<?php

use App\Http\Controllers\Api\BlogController;

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\BusinessSettingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\NoticeController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PromotionPopupController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SliderController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('/register', [CustomerAuthController::class, 'register']);
    Route::post('/login', [CustomerAuthController::class, 'login']);
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->middleware('auth:customer');
    Route::post('/forgot-password', [CustomerAuthController::class, 'forgotPassword']);
    Route::post('/verify-otp', [CustomerAuthController::class, 'verifyOtp']);
    Route::post('/reset-password', [CustomerAuthController::class, 'resetPassword']);

    Route::group(['middleware' => 'auth:customer'], function () {

        Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
            Route::get('/dashboard', [CustomerAuthController::class, 'dashboard']);
            Route::get('/profile', [CustomerAuthController::class, 'profile']);
            Route::post('/update-profile', [CustomerAuthController::class, 'updateProfile']);
            Route::get('/addresses', [CustomerAuthController::class, 'addresses']);
            Route::post('/add-address', [CustomerAuthController::class, 'addAddress']);
            Route::get('/address/{id}', [CustomerAuthController::class, 'getAddress']);
            Route::post('/update-address/{id}', [CustomerAuthController::class, 'updateAddress']);
            Route::get('/orders-list', [CustomerAuthController::class, 'ordersList']);
            Route::get('/order-details/{order_id}', [CustomerAuthController::class, 'orderDetails']);
    
            // Wishlist
            Route::get('/wishlist', [CustomerAuthController::class, 'index']);
            Route::post('/wishlist', [CustomerAuthController::class, 'store']);
            Route::delete('/wishlist/{product_id}', [CustomerAuthController::class, 'destroy']);

            Route::post('/change-password', [CustomerAuthController::class, 'changePassword']);
            Route::post('/logout', [CustomerAuthController::class, 'logout']);

            Route::post('/product-request', [CategoryController::class, 'requestProductStore']);
        });

        Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
            Route::get('/current-customer-details', [OrderController::class, 'currentCustomer']);
            Route::post('/place-order', [OrderController::class, 'placeOrder']);
            Route::get('/customer-orders-list', [OrderController::class, 'orderListOfaCustomer']);
            Route::get('/specific-order-details/{order_number}', [OrderController::class, 'showSpecificOrderDetails']);
            Route::get('/track-order/{order_number}', [OrderController::class, 'trackOrder']);
        });
    });

    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{slug}', [CategoryController::class, 'show']);
        Route::get('/popular/list', [CategoryController::class, 'popularCategories']);
    });
 
    // Blog API Routes
    Route::prefix('blogs')->group(function () {
        // Get all blogs (with pagination)
        Route::get('/', [BlogController::class, 'index']);
        
        // Get featured blogs
        Route::get('/featured', [BlogController::class, 'featuredBlogs']);
        
        // Get blogs by category slug
        Route::get('/category/{slug}', [BlogController::class, 'blogsByCategory']);
        
        // Get single blog by slug
        Route::get('/{slug}', [BlogController::class, 'show']);
    });

    // Blog Categories API Routes
    Route::prefix('blog-categories')->group(function () {
        // Get all blog categories
        Route::get('/', [BlogController::class, 'categories']);
    });

    // Notice API Route
    Route::get('/notice/live', [NoticeController::class, 'liveNotice']);

    Route::get('/faqs', [FaqController::class, 'index']);

    // Get all settings
    Route::get('/settings', [SettingController::class, 'index']);

    // Get specific setting by key
    Route::get('/settings/{key}', [SettingController::class, 'show']);


    // Promotion Popup API Route
    Route::get('/promotion-popup/live', [PromotionPopupController::class, 'livePopup']);








    Route::group(['prefix' => 'brands', 'as' => 'brands.'], function () {
        Route::get('/', [BrandController::class, 'index']); // List all brands
        Route::get('/{slug}', [BrandController::class, 'show']); // Single brand
    });
 
    Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
        Route::get('/', [CouponController::class, 'activeCoupons']);
        Route::get('/check', [CouponController::class, 'checkCoupon']);
    });
 
    Route::get('/header-sliders', [SliderController::class, 'headerIndex']);
    Route::get('/footer-sliders', [SliderController::class, 'footerIndex']);
 
    Route::get('/settings', [BusinessSettingController::class, 'show']);
 
    Route::get('/popular-products', [CategoryController::class, 'popularProducts']);
    Route::get('/trending-products', [CategoryController::class, 'trendingProducts']);

    Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/show/{slug}', [ProductController::class, 'show']);
        Route::get('/category/{slug}', [ProductController::class, 'getCategories']);
        Route::get('/search', [ProductController::class, 'search']);
        Route::get('/related-products/{slug}', [ProductController::class, 'relatedProducts']);
        Route::get('/filter-data', [ProductController::class, 'filterOptionsData']);
    });
   
    Route::post('/success', [OrderController::class, 'successPayment']);
    Route::post('/fail', [OrderController::class, 'failPayment']);
    Route::post('/cancel', [OrderController::class, 'cancelPayment']);
});