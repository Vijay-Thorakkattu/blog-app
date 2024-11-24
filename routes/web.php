<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\BlogPostController;

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


Route::get('/', function () {
    return view('frontend.jwt-login'); 
});


Route::get('/login', function () {
    return view('auth.login');  
})->name('login');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['role:admin'],'prefix' => 'admin'], function () {
    Route::get('/home', [AdminController::class, 'home'])->name('admin.home');
    Route::resource('blog', BlogPostController::class, ['as' => 'admin']);
});
Route::get('blog/search', [BlogPostController::class, 'search'])->name('admin.blog.search');




