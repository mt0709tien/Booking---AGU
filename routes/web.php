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

    if (Auth::check() && Auth::user()->vai_tro == 'admin') {
        return redirect()->route('admin.dashboard');
    }

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

// Trang lịch
Route::get('/booking/{facility}',
[BookingController::class,'create'])
->name('booking.create');

// Form đặt
Route::get('/booking/form/{facility}',
[BookingController::class,'form'])
->name('booking.form');

// Form đặt nhiều ca (POST từ checkbox trên bảng lịch)
Route::post('/booking/form-multiple', [BookingController::class,'formMultiple'])
    ->name('booking.form.multiple');

// Lưu nhiều booking
Route::post('/booking/store-multiple', [BookingController::class,'storeMultiple'])
    ->name('booking.store.multiple');

// Lưu
Route::post('/booking/store',
[BookingController::class,'store'])
->name('booking.store');


/*
|--------------------------------------------------------------------------
| 🔥 LỊCH CỦA TÔI (USER)
|--------------------------------------------------------------------------
*/

// Xem lịch đã đặt
Route::middleware('auth')->get('/my-bookings',
[BookingController::class,'myBookings'])
->name('booking.my');

// Hủy lịch
Route::middleware('auth')->post('/booking/cancel/{id}',
[BookingController::class,'cancel'])
->name('booking.cancel');


/*
|--------------------------------------------------------------------------
| 🔔 NOTIFICATION
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/notification/read/{id}', function ($id) {

    $noti = auth()->user()->notifications->find($id);

    if ($noti) {
        $noti->markAsRead();
        return redirect($noti->data['link']);
    }

    return back();

});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
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


    // 🔥 DUYỆT / TỪ CHỐI
    Route::get('/booking/approve/{id}',
    [AdminController::class,'approve'])
    ->name('admin.booking.approve');

    Route::get('/booking/reject/{id}',
    [AdminController::class,'reject'])
    ->name('admin.booking.reject');


    // 🔥 KHÓA SÂN (ĐÃ FIX)
    Route::post('/booking/lock',
    [AdminController::class,'lock'])
    ->name('admin.booking.lock');


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

use App\Http\Controllers\StatsController;

// Khi bạn vào tên-miền.com/stats, nó sẽ gọi hàm index trong StatsController
Route::get('/admin/stats', [StatsController::class, 'index'])->name('admin.stats');

