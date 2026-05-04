<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\NewPasswordController;

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
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('login.post');

Route::get('/register', [AuthController::class,'register'])->name('register');
Route::post('/register', [AuthController::class,'registerStore'])->name('register.store');

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('booking.home');
})->name('logout');


/*
|--------------------------------------------------------------------------
| Trang thông tin
|--------------------------------------------------------------------------
*/
Route::view('/gioi-thieu', 'booking.gioithieu')->name('gioithieu');


/*
|--------------------------------------------------------------------------
| Danh mục
|--------------------------------------------------------------------------
*/
Route::get('/category/{id}', [CategoryController::class,'show'])
    ->name('category.show');


/*
|--------------------------------------------------------------------------
| Booking
|--------------------------------------------------------------------------
*/
Route::get('/booking/{facility}', [BookingController::class,'create'])
    ->name('booking.create');

Route::post('/booking/form-multiple', [BookingController::class,'formMultiple'])
    ->name('booking.form.multiple');

    Route::post('/booking/store-multiple', [BookingController::class,'storeMultiple'])
    ->name('booking.store.multiple');

Route::middleware('auth')->group(function () {

    Route::get('/booking/form/{facility}', [BookingController::class,'form'])
        ->name('booking.form');


    Route::post('/booking/store', [BookingController::class,'store'])
        ->name('booking.store');


    Route::get('/my-bookings', [BookingController::class,'myBookings'])
        ->name('booking.my');

    Route::post('/booking/cancel/{id}', [BookingController::class,'cancel'])
        ->name('booking.cancel');

    Route::post('/booking/unlock', [BookingController::class,'unlock'])
        ->name('admin.booking.unlock');
    
        Route::get('/check-slot', [BookingController::class, 'checkSlot']);
});


/*
|--------------------------------------------------------------------------
| Notification
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
| Profile (🔥 CHUẨN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');

    Route::post('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('/profile/password', [ProfileController::class, 'changePassword'])
        ->name('profile.password');
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
    Route::get('/categories', [CategoryController::class,'index'])->name('admin.categories');
    Route::get('/categories/create', [CategoryController::class,'create'])->name('admin.categories.create');
    Route::post('/categories/store', [CategoryController::class,'store'])->name('admin.categories.store');
    Route::get('/categories/edit/{id}', [CategoryController::class,'edit'])->name('admin.categories.edit');
    Route::put('/categories/update/{id}', [CategoryController::class,'update'])
    ->name('admin.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class,'destroy'])
    ->name('admin.categories.destroy');

    // Facilities
    Route::get('/facilities', [AdminController::class,'facilities'])->name('admin.facilities');
    Route::get('/facilities/create', [FacilityController::class,'create'])->name('facilities.create');
    Route::post('/facilities/store', [FacilityController::class,'store'])->name('facilities.store');
    Route::get('/facilities/edit/{id}', [FacilityController::class,'edit'])->name('facilities.edit');
    Route::post('/facilities/update/{id}', [FacilityController::class,'update'])->name('facilities.update');
    Route::get('/facilities/delete/{id}', [FacilityController::class,'delete'])->name('facilities.delete');

    // Bookings
    Route::get('/bookings', [AdminController::class,'bookings'])->name('admin.bookings');

    Route::get('/booking/approve/{id}', [AdminController::class,'approve'])->name('admin.booking.approve');
    Route::get('/booking/reject/{id}', [AdminController::class,'reject'])->name('admin.booking.reject');

    Route::post('/booking/lock', [AdminController::class,'lock'])->name('admin.booking.lock');

    // Stats
    Route::get('/stats', [StatsController::class, 'index'])->name('admin.stats');

    // Facility bookings
    Route::get('/facility/{id}/bookings', [AdminController::class,'facilityBookings'])
        ->name('admin.facility.bookings');

    // Users (admin view)
    Route::get('/users', [UserController::class,'index'])
        ->name('admin.users');
});


/*
|--------------------------------------------------------------------------
| CRUD Users
|--------------------------------------------------------------------------
*/
Route::resource('users', UserController::class);

use App\Http\Controllers\InvoiceController;

Route::middleware(['auth','admin'])->group(function () {

    Route::get('/admin/invoices', [InvoiceController::class,'index'])
        ->name('admin.invoices');

    Route::get('/admin/invoice/create/{booking}', [InvoiceController::class,'create'])
        ->name('admin.invoice.create');

    Route::post('/admin/invoice/store', [InvoiceController::class,'store'])
        ->name('admin.invoice.store');

    Route::get('/admin/invoice/{id}', [InvoiceController::class,'show'])
        ->name('admin.invoice.show');

Route::get('/admin/invoice/group/{groupId}',
    [InvoiceController::class,'createByGroup'])
    ->name('admin.invoice.group');

Route::post('/invoice/paid/{id}', [InvoiceController::class,'markAsPaid'])
    ->name('admin.invoice.paid');

Route::get('/invoice/pdf/{id}', [InvoiceController::class,'exportPDF'])
    ->name('admin.invoice.pdf');

Route::get('/admin/report', [App\Http\Controllers\Admin\ReportController::class, 'index'])
    ->name('admin.report');


});
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::get('/report/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])
    ->name('report.export');


// gửi email reset
Route::post('/forgot-password', function (Request $request) {

    $request->validate([
        'email' => 'required|email'
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return back()->with('status', __($status));

})->name('password.email');



// form nhập mật khẩu mới
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('password.reset');

// submit mật khẩu mới
Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->name('password.update');

Route::post('/admin/booking/toggle-payment', [BookingController::class, 'togglePayment'])
    ->name('admin.booking.togglePayment');

Route::get('/booking/my', [BookingController::class, 'my'])->name('booking.my');

Route::get('/payment/{id}', [BookingController::class, 'payment'])
    ->name('booking.payment');

Route::post('/admin/booking/checkin', [BookingController::class, 'checkin'])
    ->name('admin.booking.checkin'); 

Route::get('/lien-he', function () {
    return view('booking.lienhe');
})->name('lienhe');

Route::get('/co-so-vat-chat', [FacilityController::class, 'index'])
    ->name('facilities.index');

/*
|--------------------------------------------------------------------------
| Login Google
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])
    ->name('google.login');

Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])
    ->name('google.callback');

Route::post('/admin/lock-sport', [AdminController::class, 'lockSport'])
    ->name('admin.lock.sport');

Route::get('/booking/{id}/review', [BookingController::class, 'review'])
    ->name('booking.review');

Route::post('/booking/{id}/review', [BookingController::class, 'submitReview'])
    ->name('booking.review.submit');

