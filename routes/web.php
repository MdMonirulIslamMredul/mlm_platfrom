<?php

use App\Http\Controllers\Admin\DepositController as AdminDepositController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PackageOrderController as AdminPackageOrderController;
use App\Http\Controllers\Admin\PaymentMethodController as AdminPaymentMethodController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\UserRecordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\PackageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Route
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Visa Search Route
Route::post('/search', function (Request $request) {
    Log::info('Search route accessed');
    Log::info('Request data: ' . json_encode($request->all()));

    try {
        $searchNumber = trim((string) ($request->input('search_number') ?? $request->input('search') ?? ''));

        if ($searchNumber === '') {
            return response()->view('partials.search-popup', [
                'searchNumber' => '',
                'userRecord' => null,
            ], 422);
        }

        Log::info('Searching for: ' . $searchNumber);

        $userRecord = \App\Models\UserRecord::with('documents')
            ->where('passport', $searchNumber)
            ->first();

        Log::info('Search completed. Found: ' . ($userRecord ? 'Yes' : 'No'));

        return response()->view('partials.search-popup', compact('searchNumber', 'userRecord'));
    } catch (\Exception $e) {
        Log::error('Search Error: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());

        return response()->view('partials.search-popup', [
            'searchNumber' => trim((string) ($request->input('search_number') ?? $request->input('search') ?? '')),
            'userRecord' => null,
        ], 500);
    }
});

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Portal Routes (Protected by Auth Middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Deposit Money Routes
    Route::get('/user/deposit', [DepositController::class, 'index'])->name('user.deposit');
    Route::post('/user/deposit', [DepositController::class, 'store'])->name('user.deposit.store');

    // Package Purchase & Checkout Routes
    Route::get('/user/packages/{package}/checkout', [PackageController::class, 'checkout'])->name('user.packages.checkout');
    Route::post('/user/packages/{package}/checkout', [PackageController::class, 'storeOrder'])->name('user.packages.order');
    Route::post('/user/packages/buy', [PackageController::class, 'buyPackage'])->name('packages.buy');
    Route::post('/user/packages/{package}/buy', [PackageController::class, 'buyPackage'])->name('user.packages.buy');

    // Navigation & History Pages
    Route::get('/user/plans', [DashboardController::class, 'plans'])->name('user.plans');
    Route::get('/user/team-withdrawals', [DashboardController::class, 'teamWithdrawals'])->name('user.team-withdrawals');
    Route::get('/user/referral-bonus', [DashboardController::class, 'referralBonus'])->name('user.referral-bonus');
    Route::get('/user/team-invest', [DashboardController::class, 'teamInvest'])->name('user.team-invest');
    Route::get('/user/team', [DashboardController::class, 'team'])->name('user.team');
    Route::get('/user/packages', [DashboardController::class, 'packages'])->name('user.packages');
    Route::get('/user/history', [DashboardController::class, 'history'])->name('user.history');
    Route::get('/user/balance-history', [DashboardController::class, 'balanceHistory'])->name('user.balance-history');
    Route::get('/user/profile', [DashboardController::class, 'profile'])->name('user.profile');
    Route::post('/user/profile/update', [DashboardController::class, 'updateProfile'])->name('user.profile.update');
    Route::post('/user/profile/password', [DashboardController::class, 'updatePassword'])->name('user.profile.password');
});

// Admin Portal Routes (Protected by Auth & Admin Middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        $totalMessages = \App\Models\Message::count();
        $unreadMessages = \App\Models\Message::where('is_read', false)->count();
        return view('admin.dashboard', compact('totalMessages', 'unreadMessages'));
    })->name('admin.dashboard');

    Route::resource('user-records', UserRecordController::class, ['as' => 'admin']);
    Route::delete('user-records/{document}/document', [UserRecordController::class, 'deleteDocument'])
        ->name('admin.user-records.delete-document');

    // Payment Methods Management
    Route::resource('payment-methods', AdminPaymentMethodController::class, ['as' => 'admin']);
    Route::patch('payment-methods/{paymentMethod}/toggle', [AdminPaymentMethodController::class, 'toggleStatus'])
        ->name('admin.payment-methods.toggle');

    // Deposit Requests Management
    Route::get('deposits', [AdminDepositController::class, 'index'])->name('admin.deposits.index');
    Route::post('deposits/{deposit}/approve', [AdminDepositController::class, 'approve'])->name('admin.deposits.approve');
    Route::post('deposits/{deposit}/reject', [AdminDepositController::class, 'reject'])->name('admin.deposits.reject');

    // Package Orders Management
    Route::get('package-orders', [AdminPackageOrderController::class, 'index'])->name('admin.package-orders.index');
    Route::post('package-orders/{order}/approve', [AdminPackageOrderController::class, 'approve'])->name('admin.package-orders.approve');
    Route::post('package-orders/{order}/reject', [AdminPackageOrderController::class, 'reject'])->name('admin.package-orders.reject');

    // Packages Management
    Route::resource('packages', AdminPackageController::class, ['as' => 'admin']);

    // User Management Routes
    Route::get('users/{user}/activity', [AdminUserController::class, 'activity'])->name('admin.users.activity');
    Route::resource('users', AdminUserController::class, ['as' => 'admin']);

    // Admin Profile Management
    Route::get('profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
});
