<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FacilityController;


/*
|--------------------------------------------------------------------------
| Trang chủ
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('booking.home');
})->name('booking.home');


/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('login.post');


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('booking.home');
})->name('logout');


/*
|--------------------------------------------------------------------------
| Register
|--------------------------------------------------------------------------
*/

Route::get('/register', [AuthController::class,'register'])->name('register');
Route::post('/register', [AuthController::class,'registerStore'])->name('register.store');


/*
|--------------------------------------------------------------------------
| Trang thông tin
|--------------------------------------------------------------------------
*/

Route::get('/gioi-thieu', function () {
    return view('booking.gioithieu');
})->name('gioithieu');


/*
|--------------------------------------------------------------------------
| Danh mục
|--------------------------------------------------------------------------
*/

Route::get('/category/{id}', [CategoryController::class,'show'])
->name('category.show');


/*
|--------------------------------------------------------------------------
| Đặt lịch
|--------------------------------------------------------------------------
*/

// Trang lịch đặt
Route::get('/booking/{facility}',
[BookingController::class,'create'])
->name('booking.create');

// Trang form đặt lịch (THÊM MỚI)
Route::get('/booking/form/{facility}',
[BookingController::class,'form'])
->name('booking.form');

// Lưu đặt lịch
Route::post('/booking/store',
[BookingController::class,'store'])
->name('booking.store');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('admin')->group(function () {

    Route::get('/', [AdminController::class,'dashboard'])
    ->name('admin.dashboard');


    // Categories
    Route::get('/categories', [CategoryController::class,'index'])
    ->name('admin.categories');

    Route::get('/categories/create', [CategoryController::class,'create'])
    ->name('admin.categories.create');

    Route::post('/categories/store', [CategoryController::class,'store'])
    ->name('admin.categories.store');

    Route::get('/categories/edit/{id}', [CategoryController::class,'edit'])
    ->name('admin.categories.edit');

    Route::post('/categories/update/{id}', [CategoryController::class,'update'])
    ->name('admin.categories.update');

    Route::get('/categories/delete/{id}', [CategoryController::class,'delete'])
    ->name('admin.categories.delete');


    // Facilities
    Route::get('/facilities', [FacilityController::class,'index'])
    ->name('admin.facilities');

    Route::get('/facilities/create', [FacilityController::class,'create'])
    ->name('facilities.create');

    Route::post('/facilities/store', [FacilityController::class,'store'])
    ->name('facilities.store');

    Route::get('/facilities/edit/{id}', [FacilityController::class,'edit'])
    ->name('facilities.edit');

    Route::post('/facilities/update/{id}', [FacilityController::class,'update'])
    ->name('facilities.update');

    Route::get('/facilities/delete/{id}', [FacilityController::class,'delete'])
    ->name('facilities.delete');


    // Users
    Route::get('/users', [AdminController::class,'users'])
    ->name('admin.users');


    // Bookings
    Route::get('/bookings', [AdminController::class,'bookings'])
    ->name('admin.bookings');


    // Stats
    Route::get('/stats', [AdminController::class,'stats'])
    ->name('admin.stats');

});


/*
|--------------------------------------------------------------------------
| CRUD Users
|--------------------------------------------------------------------------
*/

Route::resource('users', UserController::class);