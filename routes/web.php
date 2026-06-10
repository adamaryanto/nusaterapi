<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WebManagementController;
use App\Http\Controllers\TherapistController;

// 1. Public Routes
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role === 'therapist') {
            return redirect()->route('therapist.dashboard');
        }
    }
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
    Route::post('/riwayat-pesanan/detail/{id}/pay', [BookingController::class, 'payBooking'])->name('customer.history.pay');
    Route::get('/riwayat-pesanan/ulasan/{id}', [BookingController::class, 'reviewForm'])->name('customer.review');
    Route::post('/riwayat-pesanan/ulasan/{id}', [BookingController::class, 'storeReview'])->name('customer.review.store');

    Route::get('/profile', [AuthController::class, 'profile'])->name('customer.profile');
    Route::post('/profile', [AuthController::class, 'profileUpdate']);
    
    Route::get('/membership', [AuthController::class, 'membership'])->name('customer.membership');
});

// 5. Admin-only Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Static lists for views not migrated
    Route::get('/patients', [AdminController::class, 'patients'])->name('patients');
    Route::view('/reviews', 'admin.reviews')->name('reviews');
    Route::view('/reports', 'admin.reports')->name('reports');

    // Therapists Management CRUD
    Route::get('/therapists', [AdminController::class, 'therapists'])->name('therapists');
    Route::get('/therapists/create', [AdminController::class, 'therapistsCreate'])->name('therapists.create');
    Route::post('/therapists/create', [AdminController::class, 'therapistsStore']);
    Route::get('/therapists/{id}/edit', [AdminController::class, 'therapistsEdit'])->name('therapists.edit');
    Route::post('/therapists/{id}/edit', [AdminController::class, 'therapistsUpdate']);
    Route::post('/therapists/{id}/delete', [AdminController::class, 'therapistsDestroy'])->name('therapists.delete');
    Route::get('/therapists/{id}/detail', [AdminController::class, 'therapistsDetail'])->name('therapists.detail');

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
    Route::post('/web-management/services/{id}/update', [WebManagementController::class, 'servicesUpdate'])->name('web_management.services.update');
    Route::delete('/web-management/services/{id}', [WebManagementController::class, 'servicesDestroy'])->name('web_management.services.destroy');

    // Patient Membership Toggle
    Route::post('/patients/{id}/toggle-membership', [AdminController::class, 'toggleMembership'])->name('patients.toggle_membership');

    // Dedicated Membership Settings Page & CRUD
    Route::get('/membership', [AdminController::class, 'membershipIndex'])->name('membership');
    Route::post('/membership/store', [AdminController::class, 'membershipStore'])->name('membership.store');
    Route::post('/membership/{id}/update', [AdminController::class, 'membershipUpdate'])->name('membership.update');
    Route::post('/membership/{id}/delete', [AdminController::class, 'membershipDestroy'])->name('membership.delete');
    Route::post('/membership/change-patient-tier/{id}', [AdminController::class, 'changePatientTier'])->name('membership.change_patient_tier');
});

// 6. Therapist-only Routes
Route::middleware(['auth', 'therapist'])->prefix('terapis')->name('therapist.')->group(function () {
    Route::get('/dashboard', [TherapistController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [TherapistController::class, 'bookings'])->name('bookings');

    Route::get('/pendapatan', [TherapistController::class, 'income'])->name('income');
    Route::get('/review', [TherapistController::class, 'reviews'])->name('reviews');
    Route::post('/bookings/{id}/start-journey', [TherapistController::class, 'startJourney'])->name('start_journey');
    Route::post('/bookings/{id}/arrive', [TherapistController::class, 'arrive'])->name('arrive');
    Route::post('/bookings/{id}/complete', [TherapistController::class, 'completeBooking'])->name('complete');
    Route::post('/bookings/{id}/accept', [TherapistController::class, 'acceptBooking'])->name('accept');
    Route::post('/bookings/{id}/reject', [TherapistController::class, 'rejectBooking'])->name('reject');
});

// Temporary Database Fix Route
Route::get('/fix-database', function () {
    $output = [];
    
    // 1. Fix Users Role Enum
    try {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'customer', 'therapist') NOT NULL DEFAULT 'customer'");
        $output[] = "✅ Users table role enum fixed successfully!";
    } catch (\Exception $e) {
        $output[] = "❌ Users table role enum error: " . $e->getMessage();
    }

    // 2. Fix Therapists user_id Column
    try {
        $hasColumn = false;
        try {
            \Illuminate\Support\Facades\DB::select("SELECT user_id FROM therapists LIMIT 1");
            $hasColumn = true;
        } catch (\Exception $ex) {
            $hasColumn = false;
        }
        
        if (!$hasColumn) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE therapists ADD COLUMN user_id BIGINT UNSIGNED NULL AFTER id");
            try {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE therapists ADD CONSTRAINT fk_therapists_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL");
            } catch (\Exception $ex) {
                // If constraint fails (e.g. SQLite or minor DB difference), log it but proceed
                $output[] = "⚠️ Foreign key constraint could not be added (column was added though): " . $ex->getMessage();
            }
            $output[] = "✅ Therapists table user_id column added successfully!";
        } else {
            $output[] = "ℹ️ Therapists table user_id column already exists.";
        }
    } catch (\Exception $e) {
        $output[] = "❌ Therapists table user_id error: " . $e->getMessage();
    }

    // 3. Fix Bookings Table Status Column (convert ENUM to VARCHAR for flexibility)
    try {
        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();
        if ($driver === 'mysql') {
            \Illuminate\Support\Facades\DB::unprepared("ALTER TABLE bookings MODIFY COLUMN status VARCHAR(50) DEFAULT 'Pending'");
            $output[] = "✅ Bookings table status column converted to VARCHAR successfully!";
        } else {
            $output[] = "ℹ️ SQLite detected, skipping (handled via migrations).";
        }
    } catch (\Exception $e) {
        $output[] = "❌ Bookings table status error: " . $e->getMessage();
    }

    // 4. Run pending migrations
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output[] = "✅ Database migrations executed successfully!<br><pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        $output[] = "❌ Database migrations error: " . $e->getMessage();
    }

    return implode("<br>", $output);
});

// Fallback redirect for old route name
Route::get('/fix-role-enum', function() {
    return redirect('/fix-database');
});

// Temporary route to delete dummy booking and patient data (to be removed later)
Route::get('/delete-dummy-data', function () {
    $ids = ['TRX-2605-001', 'TRX-2605-002', 'TRX-2605-003', 'TRX-2605-004', 'TRX-2605-005'];
    $deletedBookings = \App\Models\Booking::whereIn('id', $ids)->delete();
    $deletedUsers = \App\Models\User::where('email', 'budi@gmail.com')->delete();
    return "Berhasil menghapus " . $deletedBookings . " data booking dummy dan " . $deletedUsers . " user customer dummy (Budi Santoso) dari database.";
});
