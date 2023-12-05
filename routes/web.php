<?php

use App\Http\Controllers\AdminCustomerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DealsController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FooterSettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StorewiseDealController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\WebSettingController;
use App\Http\Controllers\WishListController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/clear', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('route:cache');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('optimize:clear');
    $exitCode = Artisan::call('optimize');
    return 'DONE'; //Return anything
});

Route::get('/send-otps', [AuthController::class, 'sendOtps'])->name('sendOtps');

Route::group(['middleware' => ['web']], function () {


    Route::get('/product/detail/{id}', [ProductController::class, 'detailView']);
    Route::view('store/deals/list', 'frontend.all-store-deals');
    Route::view('all/deals/list', 'frontend.all-deals-list');
    //Review

    Route::get('/add/cart/{id}', [CartController::class, 'add'])->name('add.cart');
    Route::get('/add/multiple/cart', [CartController::class, 'add_multiple'])->name('add.multiplecart');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart', [CartController::class, 'view_cart'])->name('cart');
    Route::get('/incr/{id}', [CartController::class, 'increment'])->name('cart.incr');
    Route::get('/dcr/{id}', [CartController::class, 'decrement'])->name('cart.dcr');
    
    Route::post('/place/order', [PaymentController::class, 'payment'])->name('place.order');
    Route::get('/place/order', [PaymentController::class, 'payment'])->name('place.orderx');
    Route::post('/place/payment_token', [PaymentController::class, 'makePaymentObjectToken'])->name('place.payment_token');
    
    Route::get('/product/search', [ProductController::class, 'autoSearch'])->name('product.auto-search');
    Route::get('/products/qsearch', [ProductController::class, 'productSearch'])->name('products.search');
    

    Route::get('/search-product', [ProductController::class, 'search'])->name('search-product');
    //Order admin view

    Route::get('order/status', [OrderController::class, 'order_status']);


    // Register

    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('sendOtp');

    route::view('/register', 'frontend.auth.register');
    Route::get('login', [AuthController::class, 'loginForm'])->name('login');
    Route::get('/user/verification/{id}', [AuthController::class, 'verificationPage']);
    Route::get('forgot/password', function () {
        return view('frontend/auth/forgot-password');
    });
    Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset.password.get');
    Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('google-auth');
    Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
    Route::get('auth/facebook', [SocialController::class, 'facebookRedirect']);
    Route::get('auth/facebook/callback', [SocialController::class, 'loginWithFacebook']);
    Route::get('/home', function () {
        return view('underconstruction');
    });
    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('near/store', [HomeController::class, 'near_store'])->name('near_store');
    Route::get('get/emirates', [HomeController::class, 'get_emirates']);
    Route::get('shop', [ShopController::class, 'home'])->name('shop');
    Route::get('shop/{id}', [ShopController::class, 'shop_categroy'])->name('shop_categroy');
    Route::get('shop/brand/{id}', [ShopController::class, 'shop_brand'])->name('shop_brand');
    Route::get('products/{id}', [ShopController::class, 'catProducts'])->name('cat-products');

    Route::get('sub/category', [HomeController::class, 'sub_category']);
    //notification
    Route::get('notifications', [NotificationController::class, 'allNotifications']);
    Route::get('markAsRead/{id}/{data}', [NotificationController::class, 'markAsRead']);



    #################user dashboard####################
    //user dashboard



    Route::get('order/download/{id}', [OrderController::class, 'invoiceDownload']);
    Route::view('/error/page', 'error');

    Route::get('/page/{slug}', [WebSettingController::class, 'show_custom_page'])->name('custom-pages.show_custom_page');
    #################################### type2 stores #######################################


});

Route::group(['middleware' => ['auth']], function () {
    Route::post('/coupon/apply', [CouponController::class, 'coupon_apply']);
    Route::get('dashboard', [DashboardController::class, 'dashboard']);
    Route::get('/user/dashboard', [DashboardController::class, 'userDashboard']);
    Route::post('review/add', [ReviewController::class, 'review_add']);
    Route::view('/order/report', 'admin.report.order-report');
    // user wallet
    route::view('/wallet', 'user.wallet.user-wallet');
    route::view('/track-order', 'user.orders.track-order');
    Route::view('user/ticket', 'user/ticket/ticket');
    Route::view('user/ticket/list', 'user/ticket/ticketlist');
    // Ticket
    Route::get('ticket/index', [TicketController::class, 'index']);
    Route::get('ticket/create', [TicketController::class, 'create']);
    // Admin Ticket
    Route::get('ticket/adminticket', [TicketController::class, 'adminTicket']);
    //ticket

    //storeagent
    Route::get('ticket/details/{id}', [TicketController::class, 'TicketsDetails']);
    Route::get('ticket/edit/{id}', [TicketController::class, 'TicketEdit']);


    route::get('/user/profile', [ProfileController::class, 'getProfile']);
    route::get('/edit/profile/{id}', [ProfileController::class, 'editProfile']);
    //user orders
    Route::get('view/user/orders', [OrderController::class, 'userOrders']);
    Route::get('user/order/detail/{id}', [OrderController::class, 'userorderDetail']);

    //wishlist
    Route::get('/wishlist', [WishListController::class, 'home']);
    Route::get('/add/wishlist', [WishListController::class, 'add_wishlist']);
    route::view('/profile/v/', 'store.profile.profile');
    route::view('profile/e/', 'store.profile.store_edit_profile');

    Route::post('cancel/order/request', [OrderController::class, 'cancelRequest']);
    
    Route::get('checkout',[CheckoutController::class,'checkoutView']);
    Route::post('add_address',[CheckoutController::class,'addAddress']);
    Route::get('list_address',[CheckoutController::class,'listAddress']);
    Route::get('default_address',[CheckoutController::class,'defaultAddress']);
    Route::get('current_address',[CheckoutController::class,'currentAddress']);
    Route::get('delete_address',[CheckoutController::class,'deleteAddress']);
    
    Route::get('success', [PaymentController::class, 'checkout_success'])->name('success');

    Route::view('role/view', 'admin/roles/role-view');
    Route::get('/role/permission/{id}', [RoleController::class, 'Permission']);
    Route::view('customers', 'store.customers.customer');
    Route::view('staff', 'store.staff.staff');
    Route::view('feedback','admin.feedback.feedback');
});
Route::group(['middleware' => ['type1']], function () {
    
    Route::get('/bulk/uploads', [ProductController::class, 'getUploads']);
    
    Route::get('order/detail/{id}', [OrderController::class, 'orderDetail']);
    Route::get('orders', [OrderController::class, 'getOrders']);

    route::view('/store/add', 'admin.store.store_add');
    route::view('/store/edit', 'admin.store.store_edit');
    route::view('/store/detail', 'admin.store.store_detail');
    route::view('/store/list', 'admin.store.store_list');

    Route::controller(StoreController::class)->group(function () {
        Route::get('/store/edit/{id}', 'edit');
        Route::get('/store/detail/{id}', 'storedetail');
    });
    Route::view('/brand/add', 'admin.brands.brand_add');
    Route::view('/brand/list', 'admin.brands.brand_list');
    Route::controller(BrandController::class)->group(function () {
        Route::get('/brand/edit/{id}', 'edit');
    });
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category/list', 'index');
        Route::get('/category/create', 'create');
        Route::get('/category/edit/{id}', 'edit');
        route::get('categories', 'allCategories');
        Route::get('trending/categories', 'viewTrending');
    });
    Route::controller(UploadController::class)->group(function () {
        Route::view('/upload/add', 'admin.uploads.upload_add');
        Route::view('/upload/list', 'admin.uploads.upload_list');
    });

    Route::post('/upload-images', [UploadController::class, 'uploadFile'])->name('upload.images');

    Route::controller(ProductController::class)->group(function () {
        Route::view('/product/create', 'admin.products.create');
        Route::view('/product/list', 'admin.products.product_list');

        Route::get('/product/edit/{id}', 'edit');
        Route::get('/product/reviews/list/{id}', 'reviews_list');
        Route::get('/compare/products/{id}', 'productcompare');
    });

    Route::get('reviews/list', [ReviewController::class, 'reviews_list']);
    Route::get('reviews/status', [ReviewController::class, 'reviews_status']);
    Route::get('reviews/delete/{id}', [ReviewController::class, 'review_delete']);
    //reviews
    Route::view('product/reviews', 'admin.reviews.review');
    Route::view('create/deals', 'admin/deals/create-deals');
    //Coupn
    Route::get('/coupon/view', [CouponController::class, 'coupon_view']);
    Route::post('/coupon/add', [CouponController::class, 'coupon_add']);
    Route::get('/coupon/edit/{id}', [CouponController::class, 'coupon_edit']);
    Route::post('/coupon/update', [CouponController::class, 'coupon_update']);
    Route::get('/coupon/delete/{id}', [CouponController::class, 'coupon_delete']);
    Route::get('/coupon/status/{id}', [CouponController::class, 'coupon_status']);
 
    Route::view('bulk/upload', 'admin.bulkupload.bulkupload');
    //Storewise deals
    Route::get('store-wise-deal', [StorewiseDealController::class, 'create']);
    Route::get('get_products', [StorewiseDealController::class, 'get_products']);
    Route::get('store/deals', [StorewiseDealController::class, 'storeDealsproduct']);
    Route::get('all/deals/', [DealsController::class, 'allstoreDealsproduct']);
    Route::get('/deal/edit/{id}', [DealsController::class, 'editDeals']);

    //web settings
    Route::get('web-settings', [WebSettingController::class, 'Setting']);
    Route::view('add/slider', 'admin.setting.add-slider');
    Route::view('view/slider', 'admin.setting.view-slider');
    Route::view('footer/setting', 'admin.setting.footer.footer-setting');
    Route::get('edit/footer/setting/{id}', [FooterSettingController::class, 'editFootersetting']);
    Route::get('/slider/edit/{id}', [WebSettingController::class, 'edit']);
    //web pages
    Route::get('add/new-page', [WebsettingController::class, 'createPage']);
    Route::get('all/pages', [WebsettingController::class, 'allPages']);
    
    Route::get('page/edit/{slug}', [WebSettingController::class, 'editPage']);
    //admin staff
    Route::view('admin/staff', 'admin.staff.staff');
    Route::view('/add/admin/staff', 'admin.staff.add-staff');
    // admin customers
    Route::get('admin/customers', [AdminCustomerController::class, 'adminCustomers']);
    Route::get('admin/customers/{id}/{code}', [AdminCustomerController::class, 'adminCustomerdetail']);
});
Route::group(['middleware' => ['type2']], function () {
    route::get('/return/detail/{id}', [OrderController::class, 'returnDetailview']);

    Route::get('view/store/orders', [OrderController::class, 'storeOrders']);
    Route::get('view/store/cancel/orders', [OrderController::class, 'getCancelOrders']);
    Route::get('store/order/detail/{id}', [OrderController::class, 'storeorderDetail']);

    Route::view('store/ticket', 'store/ticket/ticket');
    Route::view('store/add/ticket', 'store/ticket/addticket');
    Route::get('tickets/edit/{id}', [TicketController::class, 'EditTickets']);
    Route::get('ticket/detail/{id}', [TicketController::class, 'TicketDetail']);
    route::view('/add/staff', 'store.staff.add-staff');
    //store


    Route::view('shipping/schedule', 'store.schedule.shipping-schedule');
    Route::view('pickup/schedule', 'store.schedule.pickup-schedule');
    Route::view('/view/store/products', 'admin.products.store-products-list');
    Route::get('store/customer/detail/{id}', [StoreController::class, 'storeCustomer']);
});