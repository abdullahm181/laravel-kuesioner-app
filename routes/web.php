<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
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
    
    Route::get('/auth/registerPage',[AuthController::class,'registerPage']);
    Route::post('/auth/register',[AuthController::class,'register']);

    Route::get('/auth/forgotPasswordPage',[AuthController::class,'forgotPasswordPage']);
    Route::post('/auth/submitForgetPasswordForm',[AuthController::class,'submitForgetPasswordForm']);

    Route::get('/auth/resetPasswordPage',[AuthController::class,'forgotPasswordPage'])->name('auth.resetPasswordPage');
    Route::post('/auth/submitResetPasswordForm',[AuthController::class,'submitResetPasswordForm']);
});

Route::middleware(['auth'])->group(function(){
    Route::get('/home',[HomeController::class,'index']);
    Route::get('/logout',[AuthController::class,'logout']);

    Route::get('/role/index',[RoleController::class,'index'])->middleware('userAccessFilter');
    Route::get('/role/showRoleAccess',[RoleController::class,'showRoleAccess'])->middleware('userAccessFilter:index');
    Route::post('/role/ManageAccessRole',[RoleController::class,'ManageAccessRole'])->middleware('userAccessFilter:index');
    Route::resource('role', RoleController::class)->middleware('userAccessFilter:index');

    Route::get('/user/index',[UserController::class,'index'])->middleware('userAccessFilter');
    Route::get('/user/getRoles',[UserController::class,'getRoles'])->middleware('userAccessFilter:index');
    Route::post('/user/disable',[UserController::class,'disable'])->middleware('userAccessFilter:index');
    Route::resource('user', UserController::class)->middleware('userAccessFilter:index');

    Route::get('/module/index',[ModuleController::class,'index'])->middleware('userAccessFilter');
    Route::resource('module', ModuleController::class)->middleware('userAccessFilter:index');
});

// Route::group(['middleware'=>'XSS', function() {
//     Route::get('/product', 'ProductController@index');
// }]);

// Route::get('/send-email',function(){
//     $data = [
//         'name' => 'Syahrizal As',
//         'body' => 'Testing Kirim Email di Santri Koding'
//     ];
   
//     Mail::to('emailtujuan@gmail.com')->send(new SendEmail($data));
   
//     dd("Email Berhasil dikirim.");
// });