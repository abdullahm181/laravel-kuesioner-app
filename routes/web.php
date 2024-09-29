<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
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
Route::middleware(['guest'])->group(function(){
    Route::get('/',[AuthController::class,'index'])->name('login');
    Route::post('/',[AuthController::class,'login']);
});

Route::middleware(['auth'])->group(function(){
    Route::get('/home',[HomeController::class,'index'])->middleware('userAccessFilter');
    Route::get('/home/admin',[HomeController::class,'admin'])->middleware('userAccessFilter:2');
    Route::get('/logout',[AuthController::class,'logout']);
});

Route::group(['middleware'=>'XSS', function() {
    Route::get('/product', 'ProductController@index');
}]);