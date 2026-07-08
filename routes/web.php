<?php

use App\Http\Controllers\User\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Default\DefaultController;
use App\Http\Controllers\User\Product\ProductController;

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
Route::get('giris-yap',[AuthController::class,'loginPage'])->name('loginPage');
Route::get('kayit-ol',[AuthController::class,'registerPage'])->name('registerPage');
Route::get('admin/login',[App\Http\Controllers\Admin\Auth\AuthController::class,'loginPage'])->name('admin.loginPage');

Route::get('tum-urunler',[ProductController::class,'index'])->name('shops');
Route::get('tum-urunler/{slug}',[ProductController::class,'show'])->name('shopDetail','slug');
Route::get('koleksiyonlar',[ProductController::class,'collectionList'])->name('collectionList');
Route::group(['middleware' => 'user'],function (){

});

Route::group(['prefix' => 'admin/', 'middleware' => 'admin'],function (){

});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
