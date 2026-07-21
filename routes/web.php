<?php

use App\Http\Controllers\User\Auth\AuthController;
use App\Http\Controllers\User\Auth\ForgotPasswordController;
use App\Http\Controllers\User\Auth\GoogleAuthController;
use App\Http\Controllers\User\Auth\ResetPasswordController;
use App\Http\Controllers\User\EmailVerification\EmailVerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Default\DefaultController;
use App\Http\Controllers\User\Default\MaintenanceController;
use App\Http\Controllers\User\Product\ProductController;
use App\Http\Controllers\Admin\Default\DefaultController as AdminDefaultController;
use App\Http\Controllers\Admin\Product\ProductController as AdminProductController;
use App\Http\Controllers\Admin\Category\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\Banner\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\Order\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\User\UserController as AdminUserController;
use App\Http\Controllers\Admin\Auth\AuthController as AdminAuthController;
use App\Http\Controllers\User\ShoppingCart\ShoppingCartController;
use App\Http\Controllers\User\Account\AccountController;
use App\Http\Controllers\User\Order\OrderController as UserOrderController;
use App\Http\Controllers\User\Comment\CommentController as UserCommentController;
use App\Http\Controllers\Admin\Collection\CollectionController as AdminCollectionController;
use App\Http\Controllers\Admin\Comment\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\Setting\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\Blog\BlogController as AdminBlogController;
use App\Http\Controllers\User\Blog\BlogController as UserBlogController;

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

Route::get('/',[DefaultController::class,'index'])->name('index');
Route::get('bakim', MaintenanceController::class)->name('maintenance');
Route::get('giris-yap',[AuthController::class,'loginPage'])->name('loginPage');
Route::post('giris-yap',[AuthController::class,'authenticate'])->name('authenticate');
Route::get('kayit-ol',[AuthController::class,'registerPage'])->name('registerPage');
Route::post('kayit-ol',[AuthController::class,'register'])->name('register');
Route::get('kayit-ol/dogrula',[EmailVerificationController::class,'index'])->name('registerVerifyPage');
Route::post('kayit-ol/dogrula/kod-gonder',[EmailVerificationController::class,'sendCode'])->name('registerVerifySend');
Route::post('kayit-ol/dogrula',[EmailVerificationController::class,'verify'])->name('registerVerify');
Route::post('cikis-yap',[AuthController::class,'logout'])->middleware('auth')->name('logout');
Route::get('sifremi-unuttum', [ForgotPasswordController::class, 'create'])->name('password.request');
Route::post('sifremi-unuttum', [ForgotPasswordController::class, 'store'])->name('password.email');
Route::get('sifre-sifirla/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
Route::post('sifre-sifirla', [ResetPasswordController::class, 'update'])->name('password.update');
Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
Route::get('admin/login', [AdminAuthController::class, 'loginPage'])->name('admin.loginPage');
Route::post('admin/login', [AdminAuthController::class, 'authenticate'])->name('admin.authenticate');
Route::post('admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth')->name('admin.logout');

Route::get('tum-urunler',[ProductController::class,'index'])->name('shops');
Route::get('tum-urunler/{slug}',[ProductController::class,'show'])->name('shopDetail','slug');
Route::get('koleksiyonlar',[ProductController::class,'collectionList'])->name('collectionList');
Route::get('koleksiyonlar/{slug}',[ProductController::class,'collectionShow'])->name('collectionShow');
Route::get('arama',[ProductController::class,'searchSuggestions'])->name('searchSuggestions');
Route::get('bloglar', [UserBlogController::class, 'index'])->name('blogList');
Route::get('bloglar/{slug}', [UserBlogController::class, 'show'])->name('blogShow');
Route::group(['middleware' => 'user'],function (){
    Route::get('sepet', [ShoppingCartController::class, 'index'])->name('cart');
    Route::post('sepet/store', [ShoppingCartController::class, 'store'])->name('cartStore');
    Route::post('sepet/update', [ShoppingCartController::class, 'update'])->name('cartUpdate');
    Route::get('sepet/{id}/delete', [ShoppingCartController::class, 'destroy'])->name('cartDelete');

    Route::get('hesabim', [AccountController::class, 'index'])->name('account');
    Route::post('hesabim/update', [AccountController::class, 'update'])->name('accountUpdate');
    Route::get('adreslerim', [AccountController::class, 'addressList'])->name('addressList');
    Route::get('adreslerim/yeni', [AccountController::class, 'addressCreatePage'])->name('addressCreatePage');
    Route::post('adreslerim/store', [AccountController::class, 'addressStore'])->name('addressStore');
    Route::get('adreslerim/{id}/duzenle', [AccountController::class, 'addressEditPage'])->name('addressEditPage');
    Route::post('adreslerim/update', [AccountController::class, 'addressUpdate'])->name('addressUpdate');
    Route::get('adreslerim/{id}/delete', [AccountController::class, 'addressDestroy'])->name('addressDelete');

    Route::get('siparislerim', [UserOrderController::class, 'index'])->name('orderList');
    Route::get('siparislerim/{code}', [UserOrderController::class, 'show'])->name('orderShow');
    Route::post('siparislerim/yorum', [UserCommentController::class, 'store'])->name('commentStore');
    Route::get('odeme', [UserOrderController::class, 'checkoutPage'])->name('checkout');
    Route::post('odeme', [UserOrderController::class, 'checkoutStore'])->name('checkoutStore');
});

Route::group(['prefix' => 'admin/', 'middleware' => 'admin'], function () {
    Route::get('/', [AdminDefaultController::class, 'index'])->name('admin.index');

    Route::get('products', [AdminProductController::class, 'index'])->name('admin.productList');
    Route::get('products/create', [AdminProductController::class, 'storePage'])->name('admin.productStorePage');
    Route::post('products/store', [AdminProductController::class, 'store'])->name('admin.productStore');
    Route::post('products/update', [AdminProductController::class, 'update'])->name('admin.productUpdate');
    Route::get('products/image/{imageId}/delete', [AdminProductController::class, 'imageDelete'])->name('admin.productImageDelete');
    Route::get('products/{id}/delete', [AdminProductController::class, 'destroy'])->name('admin.productDelete');
    Route::get('products/{slug}', [AdminProductController::class, 'show'])->name('admin.productEditPage');

    Route::get('categories', [AdminCategoryController::class, 'index'])->name('admin.categoryList');
    Route::get('categories/create', [AdminCategoryController::class, 'storePage'])->name('admin.categoryStorePage');
    Route::post('categories/store', [AdminCategoryController::class, 'store'])->name('admin.categoryStore');
    Route::post('categories/update', [AdminCategoryController::class, 'update'])->name('admin.categoryUpdate');
    Route::get('categories/{id}/delete', [AdminCategoryController::class, 'destroy'])->name('admin.categoryDelete');
    Route::get('categories/{slug}', [AdminCategoryController::class, 'show'])->name('admin.categoryEditPage');

    Route::get('banners', [AdminBannerController::class, 'index'])->name('admin.bannerList');
    Route::get('banners/create', [AdminBannerController::class, 'storePage'])->name('admin.bannerStorePage');
    Route::post('banners/store', [AdminBannerController::class, 'store'])->name('admin.bannerStore');
    Route::post('banners/update', [AdminBannerController::class, 'update'])->name('admin.bannerUpdate');
    Route::get('banners/{id}/delete', [AdminBannerController::class, 'destroy'])->name('admin.bannerDelete');
    Route::get('banners/{id}', [AdminBannerController::class, 'show'])->name('admin.bannerEditPage');

    Route::get('collections', [AdminCollectionController::class, 'index'])->name('admin.collectionList');
    Route::get('collections/create', [AdminCollectionController::class, 'storePage'])->name('admin.collectionStorePage');
    Route::post('collections/store', [AdminCollectionController::class, 'store'])->name('admin.collectionStore');
    Route::post('collections/update', [AdminCollectionController::class, 'update'])->name('admin.collectionUpdate');
    Route::get('collections/{id}/delete', [AdminCollectionController::class, 'destroy'])->name('admin.collectionDelete');
    Route::get('collections/{id}', [AdminCollectionController::class, 'show'])->name('admin.collectionEditPage');

    Route::get('comments', [AdminCommentController::class, 'index'])->name('admin.commentList');
    Route::post('comments/update', [AdminCommentController::class, 'update'])->name('admin.commentUpdate');
    Route::get('comments/{id}/delete', [AdminCommentController::class, 'destroy'])->name('admin.commentDelete');

    Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orderList');
    Route::post('orders/update', [AdminOrderController::class, 'update'])->name('admin.orderUpdate');
    Route::get('orders/{code}', [AdminOrderController::class, 'show'])->name('admin.orderDetailPage');

    Route::get('users', [AdminUserController::class, 'index'])->name('admin.userList');
    Route::post('users/update', [AdminUserController::class, 'update'])->name('admin.userUpdate');
    Route::get('users/{id}/delete', [AdminUserController::class, 'destroy'])->name('admin.userDelete');
    Route::get('users/{id}', [AdminUserController::class, 'show'])->name('admin.userDetailPage');

    Route::get('blogs', [AdminBlogController::class, 'index'])->name('admin.blogList');
    Route::get('blogs/create', [AdminBlogController::class, 'storePage'])->name('admin.blogStorePage');
    Route::post('blogs/store', [AdminBlogController::class, 'store'])->name('admin.blogStore');
    Route::post('blogs/update', [AdminBlogController::class, 'update'])->name('admin.blogUpdate');
    Route::get('blogs/{id}/delete', [AdminBlogController::class, 'destroy'])->name('admin.blogDelete');
    Route::get('blogs/{id}', [AdminBlogController::class, 'show'])->name('admin.blogEditPage');

    Route::get('settings', [AdminSettingController::class, 'edit'])->name('admin.settings');
    Route::post('settings', [AdminSettingController::class, 'update'])->name('admin.settingsUpdate');
});
