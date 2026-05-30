<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WebManagementController;

// 1. Public Routes
Route::get('/', function () {
    $settings = \App\Models\WebSetting::all()->pluck('value', 'key');
    $services  = \App\Models\Service::active()->get();
    return view('landing', compact('settings', 'services'));
})->name('landing');

Route::view('/layanan/detail', 'services.detail')->name('services.detail');

// 2. Guest-only Auth Routes (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// 3. Authenticated Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 4. Customer-only Routes
Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/booking', [BookingController::class, 'bookingForm'])->name('customer.booking');
    Route::post('/booking', [BookingController::class, 'storeBooking']);

    Route::get('/riwayat-pesanan', [BookingController::class, 'history'])->name('customer.history');
    Route::get('/riwayat-pesanan/detail/{id}', [BookingController::class, 'historyDetail'])->name('customer.history.detail');
    Route::post('/riwayat-pesanan/detail/{id}/cancel', [BookingController::class, 'cancelBooking'])->name('customer.history.cancel');
});

// 5. Admin-only Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Static lists for views not migrated
    Route::view('/patients', 'admin.patients')->name('patients');
    Route::view('/reviews', 'admin.reviews')->name('reviews');
    Route::view('/reports', 'admin.reports')->name('reports');

    // Therapists Management CRUD
    Route::get('/therapists', [AdminController::class, 'therapists'])->name('therapists');
    Route::get('/therapists/create', [AdminController::class, 'therapistsCreate'])->name('therapists.create');
    Route::post('/therapists/create', [AdminController::class, 'therapistsStore']);
    Route::get('/therapists/{id}/edit', [AdminController::class, 'therapistsEdit'])->name('therapists.edit');
    Route::post('/therapists/{id}/edit', [AdminController::class, 'therapistsUpdate']);
    Route::post('/therapists/{id}/delete', [AdminController::class, 'therapistsDestroy'])->name('therapists.delete');

    // Bookings & Transactions Management
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/detail/{id}', [AdminController::class, 'bookingsDetail'])->name('bookings.detail');
    Route::post('/bookings/detail/{id}/cancel', [AdminController::class, 'bookingsCancel'])->name('bookings.cancel');
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/detail/{id}', [AdminController::class, 'transactionsDetail'])->name('transactions.detail');

    // Profile Settings
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('/profile', [AdminController::class, 'profileUpdate']);

    // Web Management (Konten Website)
    Route::get('/web-management', [WebManagementController::class, 'index'])->name('web_management');
    Route::post('/web-management/banner', [WebManagementController::class, 'updateBanner'])->name('web_management.banner');
    Route::post('/web-management/about', [WebManagementController::class, 'updateAbout'])->name('web_management.about');
    Route::post('/web-management/services', [WebManagementController::class, 'servicesStore'])->name('web_management.services.store');
    Route::put('/web-management/services/{id}/update', [WebManagementController::class, 'servicesUpdate'])->name('web_management.services.update');
    Route::delete('/web-management/services/{id}', [WebManagementController::class, 'servicesDestroy'])->name('web_management.services.destroy');
});
