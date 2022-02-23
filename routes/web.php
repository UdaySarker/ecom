<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SslCommerzPaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
// */
// Auth::routes();
Auth::routes(['register'=>true]);
Auth::routes(['verify'=>true]);

Route::get('user/login','FrontendController@login')->name('login.form');
Route::post('user/login','FrontendController@loginSubmit')->name('login.submit');
Route::get('user/logout','FrontendController@logout')->name('user.logout');

Route::get('user/register','FrontendController@register')->name('register.form');
Route::post('user/register','FrontendController@registerSubmit')->name('register.submit')->middleware('verified');
// Reset password
Route::post('password-reset', 'FrontendController@showResetForm')->name('password.reset');
// Socialite
Route::get('login/{provider}/', 'Auth\LoginController@redirect')->name('login.redirect');
Route::get('login/{provider}/callback/', 'Auth\LoginController@Callback')->name('login.callback');

Route::get('/','FrontendController@home')->name('home');

// Frontend Routes
Route::get('/home', 'FrontendController@index');
Route::get('/about-us','FrontendController@aboutUs')->name('about-us');
Route::get('/contact','FrontendController@contact')->name('contact');
Route::post('/contact/message','MessageController@store')->name('contact.store');
Route::get('product-detail/{slug}','FrontendController@productDetail')->name('product-detail');
Route::post('/product/search','FrontendController@productSearch')->name('product.search');
Route::get('/product-cat/{slug}','FrontendController@productCat')->name('product-cat');
Route::get('/product-sub-cat/{slug}/{sub_slug}','FrontendController@productSubCat')->name('product-sub-cat');
Route::get('/product-brand/{slug}','FrontendController@productBrand')->name('product-brand');
// Cart section
Route::get('/add-to-cart/{slug}','CartController@addToCart')->name('add-to-cart')->middleware(['auth','verified']);
Route::get('/add-to-cart-old/{slug}','CartController@addToCartOld')->name('add-to-cart-old')->middleware(['auth','verified']);
Route::post('/add-to-cart','CartController@singleAddToCart')->name('single-add-to-cart')->middleware('auth');
Route::get('cart-delete/{id}','CartController@cartDelete')->name('cart-delete');
Route::post('cart-update','CartController@cartUpdate')->name('cart.update');
Route::get('/terms','FrontendController@terms')->name('terms');

Route::get('/cart',function(){
    return view('frontend.pages.cart');
})->name('cart');
Route::get('/checkout','CartController@checkout')->name('checkout')->middleware('auth');
// Wishlist
Route::get('/wishlist',function(){
    return view('frontend.pages.wishlist');
})->name('wishlist');
Route::get('/wishlist/{slug}','WishlistController@wishlist')->name('add-to-wishlist')->middleware('auth');
Route::get('wishlist-delete/{id}','WishlistController@wishlistDelete')->name('wishlist-delete');
Route::post('cart/order','OrderController@store')->name('cart.order');
Route::get('order/pdf/{id}','OrderController@pdf')->name('order.pdf');
Route::get('/income','OrderController@incomeChart')->name('product.order.income');
// Route::get('/user/chart','AdminController@userPieChart')->name('user.piechart');
Route::get('/product-grids','FrontendController@productGrids')->name('product-grids');
Route::get('/product-lists','FrontendController@productLists')->name('product-lists');
Route::match(['get','post'],'/filter','FrontendController@productFilter')->name('shop.filter');
// Order Track
Route::get('/product/track','OrderController@orderTrack')->name('order.track');
Route::post('product/track/order','OrderController@productTrackOrder')->name('product.track.order');
// Blog
Route::get('/blog','FrontendController@blog')->name('blog');
Route::get('/blog-detail/{slug}','FrontendController@blogDetail')->name('blog.detail');
Route::get('/blog/search','FrontendController@blogSearch')->name('blog.search');
Route::post('/blog/filter','FrontendController@blogFilter')->name('blog.filter');
Route::get('blog-cat/{slug}','FrontendController@blogByCategory')->name('blog.category');
Route::get('blog-tag/{slug}','FrontendController@blogByTag')->name('blog.tag');

// Product Review
Route::resource('/review','ProductReviewController');
Route::post('product/{slug}/review','ProductReviewController@store')->name('review.store');

// Post Comment
Route::post('post/{slug}/comment','PostCommentController@store')->name('post-comment.store');
Route::resource('/comment','PostCommentController');
// Coupon
Route::post('/coupon-store','CouponController@couponStore')->name('coupon-store');
//old book sale
Route::get('/oldbooksale','FrontendController@oldBookSale')->name('oldsalefront');


// Backend section start prefix by /admin

Route::group(['prefix'=>'/admin','middleware'=>['auth','admin']],function(){
    Route::get('/','AdminController@index')->name('admin');
    // user route
    Route::resource('users','UsersController');
    // Banner
    Route::resource('banner','BannerController');
    // Publisher
    Route::resource('publisher','PublisherController');
    //Author
    Route::resource('author','AuthorController');
    // Profile
    Route::get('/profile','AdminController@profile')->name('admin-profile');
    Route::post('/profile/{id}','AdminController@profileUpdate')->name('profile-update');
    // Category
    Route::resource('/category','CategoryController');
    // Product
    Route::resource('/product','ProductController');
    // Ajax for sub category
    Route::post('/category/{id}/child','CategoryController@getChildByParent');
    // POST category
    Route::resource('/post-category','PostCategoryController');
    // Post tag
    Route::resource('/post-tag','PostTagController');
    // Post
    Route::resource('/post','PostController');
    // Message
    Route::resource('/message','MessageController');
    Route::get('/message/five','MessageController@messageFive')->name('messages.five');

    // Order
    Route::resource('/order','OrderController');
    Route::post('/order/{id}/updatepayment','OrderController@updatePayment')->name('order.update.payment');
    // Shipping
    Route::resource('/shipping','ShippingController');
    // Coupon
    Route::resource('/coupon','CouponController');
    // Settings
    Route::get('settings','AdminController@settings')->name('settings');
    Route::post('setting/update','AdminController@settingsUpdate')->name('settings.update');

    // Notification
    Route::get('/notification/{id}','NotificationController@show')->name('admin.notification');
    Route::get('/notifications','NotificationController@index')->name('all.notification');
    Route::delete('/notification/{id}','NotificationController@delete')->name('notification.delete');
    // Password Change
    Route::get('change-password', 'AdminController@changePassword')->name('change.password.form');
    Route::post('change-password', 'AdminController@changPasswordStore')->name('change.password');

    //old book sale admin
    Route::get('/oldbooksale','OldBookSaleController@oldBookSaleAdminIndex')->name('oldbooksale.index');
    Route::get('/oldbooksale/show/{id}','OldBookSaleController@oldBookSaleAdminShow')->name('oldbooksale.show');
    Route::post('/oldbooksale/update/{id}','OldBookSaleController@oldBookSaleAdminUpdateStatus')->name('oldbooksale.updateStatus');
});


// User section start route prefixed by /user
Route::group(['prefix'=>'/user','middleware'=>['auth','verified']],function(){
    Route::get('/','HomeController@index')->name('user');
     // user/Profile
     Route::get('/profile','HomeController@profile')->name('user-profile');
     Route::post('/profile/{id}','HomeController@profileUpdate')->name('user-profile-update');
    //  user/Order
    Route::get('/order',"HomeController@orderIndex")->name('user.order.index');
    Route::get('/order/show/{id}',"HomeController@orderShow")->name('user.order.show');
    Route::delete('/order/delete/{id}','HomeController@userOrderDelete')->name('user.order.delete');
    // user/Product Review
    Route::get('/user-review','HomeController@productReviewIndex')->name('user.productreview.index');
    Route::delete('/user-review/delete/{id}','HomeController@productReviewDelete')->name('user.productreview.delete');
    Route::get('/user-review/edit/{id}','HomeController@productReviewEdit')->name('user.productreview.edit');
    Route::patch('/user-review/update/{id}','HomeController@productReviewUpdate')->name('user.productreview.update');

    // Notification
    Route::get('/notification/{id}','UserNotificationController@show')->name('user.notification');
    Route::get('/notifications','UserNotificationController@index')->name('all.notification');
    Route::delete('/notification/{id}','UserNotificationController@delete')->name('notification.delete');
    // user/Post comment
    Route::get('user-post/comment','HomeController@userComment')->name('user.post-comment.index');
    Route::delete('user-post/comment/delete/{id}','HomeController@userCommentDelete')->name('user.post-comment.delete');
    Route::get('user-post/comment/edit/{id}','HomeController@userCommentEdit')->name('user.post-comment.edit');
    Route::patch('user-post/comment/udpate/{id}','HomeController@userCommentUpdate')->name('user.post-comment.update');

    // user/Password Change
    Route::get('change-password', 'HomeController@changePassword')->name('user.change.password.form');
    Route::post('change-password', 'HomeController@changPasswordStore')->name('change.password');
    Route::get('payment/success', 'PayPalController@success')->name('payment.success');

    //old book
    Route::resource('/oldsale','OldBookSaleController');
    Route::get('/userexpense','UsersController@incomeFromBookSale')->name('userincome');
    // SSLCOMMERZ Start
    Route::get('example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);
    Route::get('example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

    Route::post('/pay', [SslCommerzPaymentController::class, 'index'])->name('pay');
    Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax'])->name('payajax');

    Route::post('success', [SslCommerzPaymentController::class, 'success']);
    Route::post('fail', [SslCommerzPaymentController::class, 'fail']);
    Route::post('cancel', [SslCommerzPaymentController::class, 'cancel']);

    Route::post('ipn', [SslCommerzPaymentController::class, 'ipn']);
    //SSLCOMMERZ END

});


// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
