<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome-gym', [
        'plans' => DB::table('membership_plans')->where('is_active', 1)->get(),
        'equipment' => DB::table('equipment')->limit(8)->get(),
        'trainers' => DB::table('trainers')->where('status', 'Active')->get(),
        'classes' => DB::table('classes')->where('is_active', 1)->get(),
    ]);
})->name('home');

Route::get('/customer/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
Route::post('/customer/login', [CustomerAuthController::class, 'login']);
Route::get('/login', fn () => redirect()->route('customer.login'))->name('login');
Route::get('/customer/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
Route::post('/customer/register', [CustomerAuthController::class, 'register']);

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (in_array(Auth::user()->role?->name, ['admin', 'staff'])) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.dashboard');
    })->name('dashboard');

    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::post('/customer/membership/cancel', [CustomerDashboardController::class, 'cancelMembership'])->name('customer.membership.cancel');
    Route::get('/customer/payments', [CustomerDashboardController::class, 'payments'])->name('customer.payments');
    Route::post('/customer/payments/{payment}/pay', [CustomerDashboardController::class, 'payPending'])->name('customer.payments.pay');
    Route::get('/customer/classes', [CustomerDashboardController::class, 'classes'])->name('customer.classes');
    Route::get('/customer/equipment', [CustomerDashboardController::class, 'equipment'])->name('customer.equipment');
    Route::get('/customer/trainers', [CustomerDashboardController::class, 'trainers'])->name('customer.trainers');
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/members', [AdminDashboardController::class, 'members'])->name('admin.members');
    Route::get('/admin/payments', [AdminDashboardController::class, 'payments'])->name('admin.payments');
    Route::get('/admin/sessions', [AdminDashboardController::class, 'sessions'])->name('admin.sessions');
    Route::get('/admin/equipment', [AdminDashboardController::class, 'equipment'])->name('admin.equipment');
    Route::get('/admin/reports', [AdminDashboardController::class, 'reports'])->name('admin.reports');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('home');
    })->name('logout');
});
