<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\DealsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\WishListController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreShippingScheduleController;
use App\Http\Controllers\PickupScheduleController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StorewiseDealController;
use App\Http\Controllers\WebSettingController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminCustomerController;
use App\Http\Controllers\FooterSettingController;
use App\Http\Controllers\NotificationController;






/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('change/password', [AuthController::class, 'ChangePassword'])->name('change-password');

    Route::post('role/add',[RoleController::class,'addRole']);
    Route::delete('role/delete/{id}',[RoleController::class,'deleteRole']);
    Route::get('role/{id}',[RoleController::class,'getRole']);
    Route::post('role/update',[RoleController::class,'updateRole']);
    Route::post('permission/add',[RoleController::class,'addPermission']);
    Route::post('permission/assign',[RoleController::class,'assignPermission']);
    
    Route::controller(StoreController::class)->group(function () {

    Route::post('/store/add', 'store');
    Route::get('/store/count', 'count');
    Route::get('/store/list', 'list');
    Route::post('/store/update', 'update');
    Route::get('/customer/list', 'getCustomers');
    Route::get('/customer/count', 'customerCount');
    
});

Route::get('notification/manage',[NotificationController::class,'notificationManage']);

Route::controller(CategoryController::class)->group(function () {

    Route::post('/category/add', 'store');
    Route::get('/category/count', 'count');
    Route::get('/category/list', 'list');
    Route::post('/category/update', 'update');
    Route::delete('/delete/category/{id}', 'deleteCategory');
    Route::post('/change/category/status', 'ChangeCategorystatus');
    Route::get('/category/{id}', 'getSubCategory');
    Route::get('/trending/category','trendingCategory');
    Route::get('/trengingcategory/count','trendingCount');
    Route::delete('/delete/tcategory/{id}', 'deleteTcategory');
    
});
Route::controller(BrandController::class)->group(function () {

    Route::post('/brand/add', 'store');
    Route::get('/brand/count', 'count');
    Route::get('/brand/list', 'list');
    Route::post('/brand/update', 'update');
    Route::delete('/delete/brand/{id}', 'deleteDocument');
    Route::post('/change/brand/status', 'brandStatus');
    Route::post('/brand/import', 'brandImport');
    
    
});
Route::controller(UploadController::class)->group(function (){
    // dropzone 
    Route::post('upload-file','uploadFile');
    Route::delete('delete-file','deleteFile');

    Route::get('/gallery/count', 'count');
    Route::get('/gallery/list', 'list');
    Route::post('/gallery/update', 'update');
    Route::delete('/delete/gallery/item/{id}', 'deleteGalleryItem');

});
    //state city
    Route::post('/fetch-states', [StoreController::class, 'fetchState']);
    Route::post('/fetch-cities', [StoreController::class, 'fetchCity']);

    //Register
    Route::post('register',[AuthController::class,'register']);
    Route::post('verify/{id}',[AuthController::class,'VerifyAccount']);
    Route::post('email/resend',[AuthController::class,'emailResend']);
    //Login
    Route::post('login',[AuthController::class,'login']);
    Route::post('forget-password',[AuthController::class,'submitForgetPasswordForm'])->name('forget.password.post');
    Route::post('reset-password', [AuthController::class, 'submitResetPasswordForm'])->name('reset.password.post');


    
Route::controller(ProductController::class)->group(function () {

    Route::post('/product/add', 'store');
    Route::post('/product/import', 'import');
    Route::post('/product/updateimport', 'Updateimport');
    Route::post('/product/storeimport', 'UpdateStoreimport');
    Route::post('/product/bulkdelete', 'productBulkdelete');
    Route::get('/product/count', 'count');
    Route::get('/product/list', 'list');
    Route::post('/product/update', 'update');
    Route::delete('/product/delete/{id}', 'delete');
    Route::get('/store/product/count', 'storecount');
    Route::get('/store/product/list', 'storelist');
    Route::post('/change/product/status', 'ChangeProductstatus');
    Route::get('/products/compare', 'products_compare');
    Route::post('/category/import', 'category_sale');
    
});

Route::post('deal/update', [DealsController::class, 'updatedealProduct'])->name('deal.update');

Route::controller(DealsController::class)->group(function () {

    Route::post('/deals/add', 'store');
    Route::post('/change/alldealproduct/status', 'ChangeProductstatus');
    Route::delete('/deal/delete/{id}', 'delete');
  

    
});

Route::controller(StorewiseDealController::class)->group(function () {

    Route::post('/storedeals/add', 'store');
    Route::get('/storedeal/product/count', 'storeDealcount');
    Route::get('/storedeal/product/list', 'storeDeallist');
    Route::post('/change/dealproduct/status', 'ChangeProductstatus');
    Route::delete('/storedeal/delete/{id}', 'delete');
    Route::post('/store/deal/update', 'editProduct');


    
});
//oredr cancel request

Route::get('/cancel/order/{id}',[OrderController::class,'cancel_order']);
Route::post('/cancel/order/{id}',[OrderController::class,'cancel_order']);
Route::post('order/request/cancel/update',[OrderController::class,'cancelRequestUpdate']);
Route::post('order/request/reject/update',[OrderController::class,'rejectlRequestUpdate']);
Route::get('order-report',[OrderController::class,'exportWorkingHour']);
Route::get('delete-report/{filename}',[OrderController::class,'deleteReport']);

Route::get('/option/append/',[OrderController::class,'optionAppend']);
// order tracking
Route::post('/order/tracking/',[OrderController::class,'orderTracking']);
Route::get('/edit/order/',[OrderController::class,'editOrder']);
Route::post('/update/order/',[OrderController::class,'updateOrder']);
Route::get('order/count',[OrderController::class,'orderCount']);
Route::get('admin/orders',[OrderController::class,'orderList']);
Route::get('/store/orders',[OrderController::class,'storeOrderlist']);
Route::get('store/order/count',[OrderController::class,'storeorderCount']);

// coupon
Route::controller(CouponController::class)->group(function () {

    Route::delete('/delete/coupon/{id}', 'coupon_delete');
    Route::post('/change/coupon/status', 'ChangeCouponStatus');
    Route::get('coupon/edit', 'CouponEdit');
    Route::post('coupon/update', 'CouponUpdate');
    
});

// wishlist
Route::controller(WishListController::class)->group(function () {
    Route::delete('/delete/wishlist/{id}', 'deleteWishlistProduct');
});

Route::controller(StaffController::class)->group(function () {

    Route::post('/staff/add', 'storeStaff');
    Route::get('/staff/count', 'staffCount');
    Route::get('/staff/list', 'staffList');
    Route::get('/stafflogin/{id}', 'stafflogin');
    
});


Route::controller(StaffController::class)->group(function () {

    Route::post('/admin/staff/add', 'adminStaff');
    Route::get('/admin/staff/count', 'adminstaffCount');
    Route::get('/admin/staff/list', 'adminstaffList');
    Route::get('/admin/stafflogin/{id}', 'adminstafflogin');
    
});

Route::controller(StoreShippingScheduleController::class)->group(function () {

    Route::post('/shipping/date/add', 'storeshippingDate');
    Route::post('/shipping/time/add', 'storeshippingTime');
    Route::delete('/delete/sschedule/{id}', 'deleteSschedule');
    
});

Route::controller(PickupScheduleController::class)->group(function () {

    Route::post('/pickup/date/add', 'storepickupDate');
    Route::post('/pickup/time/add', 'storepickupTime');
    Route::delete('/delete/pickupdate/{id}', 'deletePickupdate');
   
    
});

//user profile update
Route::controller(ProfileController::class)->group(function () {
    Route::post('/profile/update', 'updateProfile');
    Route::post('/change/address/status', 'ChangeAddresstatus');
     
    
});


//support ticket  

Route::post('ticket/add',[TicketController::class,'AddTicket']);
Route::post('admin/ticket/add',[TicketController::class,'AddAdminTicket']);
Route::get('ticket/list',[TicketController::class,'TicketList']);
Route::post('ticket/update',[TicketController::class,'UpdateTicket']);
Route::get('ticket/delete/{id}',[TicketController::class,'DeleteTicket']);

Route::get('ticket/view',[TicketController::class,'TicketView']);

//dropzone
Route::post('upload/files',[TicketController::class,'uploadFiles']);
Route::delete('delete/files',[TicketController::class,'DeleteFiles']);

Route::post('file/uploads',[CommentController::class,'fileUpload']);
Route::get('file/delete',[CommentController::class,'fileDelete']);

//comment
Route::post('comments/add',[CommentController::class,'AddComment']);
Route::get('comment/view',[CommentController::class,'CommentView']);




Route::get('edit/deliveryCharge/{id}',[WebSettingController::class,'editDeliveryCharje']);
Route::post('update/deliveryCharge',[WebSettingController::class,'updateDeliveryCharje']);

Route::get('edit/vat/{id}',[WebSettingController::class,'editVat']);
Route::post('update/vat',[WebSettingController::class,'updateVat']);

Route::get('edit/standardCharge/{id}',[WebSettingController::class,'editStandardCharje']);
Route::post('update/standardCharge',[WebSettingController::class,'updateStandardCharje']);
Route::post('slider/add',[WebSettingController::class,'addhomeSlider']);
Route::get('slider/list',[WebSettingController::class,'listhomeslider']);
Route::delete('slider/delete/{id}',[WebSettingController::class,'deleteHomeslider']);
Route::post('homepage/update',[WebSettingController::class,'updateHomepage']);
Route::post('page/add',[WebSettingController::class,'storePage']);
Route::post('page/update',[WebSettingController::class,'UpdatePage']);
Route::delete('delete/page/{id}',[WebSettingController::class,'deletePage']);

// Admin Customers
Route::get('admin/customer/list',[AdminCustomerController::class,'getCustomers']);
Route::get('admin/customer/count',[AdminCustomerController::class,'customerCount']);
Route::post('feedback/add',[AdminCustomerController::class,'addFeedback']);
Route::get('view/order/feedback',[AdminCustomerController::class,'orderFeedback']);

Route::post('role/types',[StaffController::class,'roleTypes']);

Route::get('products/category',[ShopController::class,'product_category']);
Route::get('products/categorys',[ShopController::class,'productCategory']);

Route::get('products/brand',[ShopController::class,'product_brand']);

Route::get('products/shop',[ShopController::class,'shop_products']);


Route::post('store/address',[AuthController::class,'addAddress']);
Route::delete('/address/delete/{id}', [AuthController::class,'deleteAddress']);

//admin reviews

Route::get('reviews/list',[ReviewController::class,'getReviews']);
Route::post('/change/review/status', [ReviewController::class,'changeReviewstatus']);

Route::controller(FooterSettingController::class)->group(function () {
    Route::post('/footersetting/add', 'storeFooterSetting');
    Route::post('/footer/update','updateFooterSetting');
    

   
    
});

