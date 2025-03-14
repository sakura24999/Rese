<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ShopController;
use App\Models\Shop;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\Admin\ReminderController;
use App\Http\Controllers\Admin\ShopImageController;
use App\Http\Controllers\Admin\ShopOwnerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Owner\ShopOwnerDashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QrCodeController;

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
    if (auth()->check()) {

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('shops.index');
    }
    return redirect()->route('shops.index');
});

Route::get('/shops.index', [MenuController::class, 'showGuest'])->name('shops.index');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');

Route::get('/shops/{shop}', [ShopController::class, 'show'])->name('shops.show');

Route::get('/api/shops/search', [ShopController::class, 'search'])->name('shops.search');

Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('shops.index');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証メールを再送信しました');
    })->middleware(['throttle:6,1'])->name('verification.send');

    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');

    Route::post('/favorites/{shop}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage.index');

    Route::delete('/reservations/{reservation}', [ReservationController::class, 'cancel'])->name('reservation.cancel');

    Route::get('/done', [ReservationController::class, 'done'])->name('done');

    Route::patch('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');

    Route::post('shops/{shop}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    Route::delete('shops/{shop}/reviews', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('shops/{shop}/reviews', [ReviewController::class, 'index'])->name('reviews.index');

    Route::get('shops/{shop}/user-review', [ReviewController::class, 'getUserReview'])->name('reviews.user');

    Route::get('/reservations/{id}/qrcode', [QrCodeController::class, 'show'])->name('reservations.qrcode');

    Route::get('/reservations/{id}/qrcode/generate', [QrCodeController::class, 'generate'])->name('reservations.qrcode.generate');

    Route::post('/qrcode/verify', [QrCodeController::class, 'verify'])->name('qrcode.verify');

    Route::get('/shop/scan', function () {
        return view('shop.scan');
    })->name('shop.scan')->middleware('role:shop_owner');

    Route::post('/reservations/{reservation}/payment', [PaymentController::class, 'processPayment'])->name('reservations.payment');

    Route::post('/reservations/{reservation}/payment/demo', [PaymentController::class, 'processDemo'])->name('reservations.payment.demo');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('shop-owners', ShopOwnerController::class);

    Route::resource('users', UserController::class);

    Route::get('/shop-images', [ShopImageController::class, 'index'])->name('shop-images.index');

    Route::get('/shop-images/create', [ShopImageController::class, 'create'])->name('shop-images.create');

    Route::post('/shop-images', [ShopImageController::class, 'store'])->name('shop-images.store');

    Route::delete('/shop-images/{filename}', [ShopImageController::class, 'destroy'])->name('shop-images.destroy');

    Route::get('/mail', [MailController::class, 'create'])->name('mail.create');

    Route::post('/mail/send', [MailController::class, 'send'])->name('mail.send');

    Route::get('/reminder', [ReminderController::class, 'index'])->name('reminder.index');

    Route::put('/reminder', [ReminderController::class, 'update'])->name('reminder.update');

    Route::post('/reminder/test', [ReminderController::class, 'sendTest'])->name('reminder.test');
});

Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:shop_owner'])->group(function () {
    Route::get('/', [ShopOwnerDashboardController::class, 'index'])->name('dashboard');
});
