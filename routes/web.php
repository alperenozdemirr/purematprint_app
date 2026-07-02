<?php

use App\Http\Controllers\User\Auth\AuthController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('user.default.index');
});
Route::get('giris-yap',[AuthController::class,'loginPage'])->name('loginPage');
Route::get('admin/login',[App\Http\Controllers\Admin\Auth\AuthController::class,'loginPage'])->name('admin.loginPage');
Route::group(['middleware' => 'user'],function (){

});

Route::group(['prefix' => 'admin/', 'middleware' => 'admin'],function (){

});