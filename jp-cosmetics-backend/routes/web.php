<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BusinessSettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionPopupController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SkinTypeController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/do-login', [LoginController::class, 'doLogin'])->name('doLogin');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', [DashboardController::class, 'showDashboard'])->name('user.dashboard')->middleware('auth');
Route::get('/dashboard/data', [DashboardController::class, 'dashboardData'])->name('dashboard.data')->middleware('auth');


Route::group(['middleware' => ['auth']], function () {


    Route::get('/profile', [DashboardController::class, 'showProfile'])->name('profile.show');


    Route::group(['prefix' => 'analytics', 'as' => 'analytics.'], function () {

        Route::get('/customer-orders',              [AnalyticsController::class, 'customerOrders'])      ->name('customer-orders');
        Route::get('/customer-orders/data',         [AnalyticsController::class, 'customerOrdersData'])  ->name('customer-orders.data');
        Route::get('/customer-orders/export',       [AnalyticsController::class, 'customerOrdersExport'])->name('customer-orders.export');

        Route::get('/total-sales',                  [AnalyticsController::class, 'totalSales'])          ->name('total-sales');
        Route::get('/total-sales/data',             [AnalyticsController::class, 'totalSalesData'])      ->name('total-sales.data');
        Route::get('/total-sales/export',           [AnalyticsController::class, 'totalSalesExport'])    ->name('total-sales.export');

        Route::get('/product-sales',                [AnalyticsController::class, 'productSales'])        ->name('product-sales');
        Route::get('/product-sales/data',           [AnalyticsController::class, 'productSalesData'])    ->name('product-sales.data');
        Route::get('/product-sales/export',         [AnalyticsController::class, 'productSalesExport'])  ->name('product-sales.export');

    });

    Route::group(['prefix' => 'roles', 'as' => 'user.roles.','module'=>'Role', 'middleware' => 'auth'], function () {
        Route::get('/', [RoleController::class, 'index'])->name('list');
        Route::get('/create', [RoleController::class, 'create'])->name('create');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [RoleController::class, 'update'])->name('update');
        Route::post('{id}/toggle-status', [RoleController::class, 'toggleStatus'])->name('toggleStatus');
    });

    Route::group(['prefix' => 'users', 'as' => 'users.', 'module' => 'users'], function () {
        Route::get('/', [UserController::class, 'index'])->name('list');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('/create', [UserController::class, 'store'])->name('store');
        Route::get('/view/{id}', [UserController::class, 'show'])->name('edit');
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('update'); // Using POST instead of PUT/PATCH
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'category', 'as' => 'category.', 'module' => 'category'], function () {
        Route::get('/', [CategoryController::class, 'list'])->name('list');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::post('{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggleStatus');

    });

    /*
    |--------------------------------------------------------------------------
    | Blog Category Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'blog/category', 'as' => 'blog.category.', 'module' => 'blog-category'], function () {
        Route::get('/', [BlogController::class, 'categoryList'])->name('list');
        Route::get('/create', [BlogController::class, 'categoryCreate'])->name('create');
        Route::post('/store', [BlogController::class, 'categoryStore'])->name('store');
        Route::get('/edit/{id}', [BlogController::class, 'categoryEdit'])->name('edit');
        Route::post('/update/{id}', [BlogController::class, 'categoryUpdate'])->name('update');
        Route::post('{id}/toggle-status', [BlogController::class, 'categoryToggleStatus'])->name('toggleStatus');
    });

    /*
    |--------------------------------------------------------------------------
    | Blog Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'blog', 'as' => 'blog.', 'module' => 'blog'], function () {
        Route::get('/', [BlogController::class, 'list'])->name('list');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/store', [BlogController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BlogController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [BlogController::class, 'update'])->name('update');
        Route::post('{id}/toggle-status', [BlogController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('{id}/toggle-featured', [BlogController::class, 'toggleFeatured'])->name('toggleFeatured');
    });

    Route::group(['prefix' => 'notice', 'as' => 'notice.', 'module' => 'notice'], function () {
        Route::get('/', [NoticeController::class, 'list'])->name('list');
        Route::get('/create', [NoticeController::class, 'create'])->name('create');
        Route::post('/store', [NoticeController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [NoticeController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [NoticeController::class, 'update'])->name('update');
        Route::post('{id}/toggle-status', [NoticeController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('{id}/set-live', [NoticeController::class, 'setLive'])->name('setLive');
    });

    Route::group(['prefix' => 'faq', 'as' => 'faq.', 'module' => 'faq'], function () {
        Route::get('/', [FaqController::class, 'list'])->name('list');
        Route::get('/create', [FaqController::class, 'create'])->name('create');
        Route::post('/store', [FaqController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [FaqController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [FaqController::class, 'update'])->name('update');
        Route::post('{id}/toggle-status', [FaqController::class, 'toggleStatus'])->name('toggleStatus');
    });

    // Settings Management Routes
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings/{key}', [SettingController::class, 'update'])->name('settings.update');


    Route::group(['prefix' => 'promotion-popup', 'as' => 'promotion-popup.', 'module' => 'promotion-popup'], function () {
        Route::get('/', [PromotionPopupController::class, 'list'])->name('list');
        Route::get('/create', [PromotionPopupController::class, 'create'])->name('create');
        Route::post('/store', [PromotionPopupController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PromotionPopupController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [PromotionPopupController::class, 'update'])->name('update');
        Route::post('{id}/toggle-status', [PromotionPopupController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('{id}/set-live', [PromotionPopupController::class, 'setLive'])->name('setLive');
    });

    Route::group(['prefix' => 'brand', 'as' => 'brand.', 'module' => 'brand'], function () {
        Route::get('/', [BrandController::class, 'list'])->name('list');
        Route::get('/create', [BrandController::class, 'create'])->name('create');
        Route::post('/store', [BrandController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [BrandController::class, 'update'])->name('update');
        Route::post('{id}/toggle-status', [BrandController::class, 'toggleStatus'])->name('toggleStatus');
        Route::delete('{id}/destroy', [BrandController::class, 'destroy'])->name('destroy');

    });

    Route::group(['prefix' => 'skin-type', 'as' => 'skin-type.', 'module' => 'skin-type'], function () {
        Route::get('/', [SkinTypeController::class, 'list'])->name('list');
        Route::get('/create', [SkinTypeController::class, 'create'])->name('create');
        Route::post('/store', [SkinTypeController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [SkinTypeController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [SkinTypeController::class, 'update'])->name('update');
        Route::post('{id}/toggle-status', [SkinTypeController::class, 'toggleStatus'])->name('toggleStatus');
        Route::delete('{id}/destroy', [SkinTypeController::class, 'destroy'])->name('destroy');
    });

    Route::group(['prefix' => 'product', 'as' => 'product.', 'module' => 'product'], function () {
        Route::get('/', [ProductController::class, 'list'])->name('list');
        Route::get('/{id}/view', [ProductController::class, 'view'])->name('view');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [ProductController::class, 'update'])->name('update');
        Route::post('{id}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggleStatus');
        Route::get('/product-requests', [ProductController::class, 'productRequestindex'])->name('product-requests.index');
        Route::delete('/product-attribute-image/{id}', [ProductController::class, 'deleteAttributeImage'])->name('attribute.image.delete');
        Route::post('{id}/duplicate', [ProductController::class, 'duplicate'])->name('duplicate');
        // Route::delete('{id}/destroy', [BrandController::class, 'destroy'])->name('destroy');

    });

    Route::group(['prefix' => 'customer', 'as' => 'customer.', 'module' => 'customer'], function () {
        Route::get('/', [CustomerController::class, 'list'])->name('list');
        Route::get('/{id}/orders', [CustomerController::class, 'orders'])->name('orders');
        Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [CustomerController::class, 'update'])->name('update');
        Route::delete('{id}/destroy', [CustomerController::class, 'destroy'])->name('destroy');

    });

    Route::group(['prefix' => 'order', 'as' => 'order.', 'module' => 'order'], function () {
        Route::get('/', [OrderController::class, 'list'])->name('list');
        Route::get('/show/{id}', [OrderController::class, 'show'])->name('show');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [OrderController::class, 'update'])->name('update');
        // Route::delete('{id}/destroy', [CustomerController::class, 'destroy'])->name('destroy');
        Route::get('/print/{id}', [OrderController::class, 'print'])->name('print');

    });

    Route::group(['prefix' => 'wishlist', 'as' => 'wishlist.', 'module' => 'wishlist'], function () {
        Route::get('/', [WishlistController::class, 'list'])->name('list');
    });  
    
    // Business Settings
    Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.', 'module' => 'business-settings'], function () {
        Route::get('/edit', [BusinessSettingController::class, 'edit'])->name('edit');
        Route::post('/update', [BusinessSettingController::class, 'update'])->name('update');
    });

    // Header Sliders
    Route::group(['prefix' => 'header-sliders', 'as' => 'header-sliders.','module' => 'header-sliders'], function () {
        Route::get('/', [SliderController::class, 'headerIndex'])->name('index');
        Route::get('/create', [SliderController::class, 'headerCreate'])->name('create');
        Route::post('/store', [SliderController::class, 'headerStore'])->name('store');
        Route::get('/{headerSlider}/edit', [SliderController::class, 'headerEdit'])->name('edit');
        Route::put('/{headerSlider}/update', [SliderController::class, 'headerUpdate'])->name('update');
        Route::post('{id}/toggle-status', [SliderController::class, 'headerToggleStatus'])->name('headerToggleStatus');
        Route::delete('/{headerSlider}/delete', [SliderController::class, 'headerDestroy'])->name('destroy');
    });

    // Footer Sliders
    Route::group(['prefix' => 'footer-sliders', 'as' => 'footer-sliders.','module' => 'footer-sliders'], function () {
        Route::get('/', [SliderController::class, 'footerIndex'])->name('index');
        Route::get('/create', [SliderController::class, 'footerCreate'])->name('create');
        Route::post('/store', [SliderController::class, 'footerStore'])->name('store');
        Route::get('/{footerSlider}/edit', [SliderController::class, 'footerEdit'])->name('edit');
        Route::put('/{footerSlider}/update', [SliderController::class, 'footerUpdate'])->name('update');
        Route::post('{id}/toggle-status', [SliderController::class, 'footerToggleStatus'])->name('footerToggleStatus');
        Route::delete('/{footerSlider}/delete', [SliderController::class, 'footerDestroy'])->name('destroy');
    });

    Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
        Route::get('/', [CouponController::class, 'list'])->name('list');
        Route::get('/create', [CouponController::class, 'create'])->name('create');
        Route::post('/store', [CouponController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CouponController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [CouponController::class, 'update'])->name('update');
        Route::post('{id}/toggle-status', [CouponController::class, 'toggleStatus'])->name('toggleStatus');
    });

    Route::group(['prefix' => 'reviews', 'as' => 'reviews.', 'module' => 'reviews'], function () {
        Route::get('/', [ReviewController::class, 'index'])->name('list');
        Route::post('{orderDetail}/toggle-approve', [ReviewController::class, 'toggleApprove'])->name('toggleApprove');
        Route::delete('{orderDetail}/destroy', [ReviewController::class, 'destroy'])->name('destroy');
    });
    
});