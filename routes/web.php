<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AssociationController;
use App\Http\Controllers\SafeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// Admin Routes
Route::middleware('guest')->group(function () {
    Route::get('admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('admin/login', [AdminController::class, 'login']);
});

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('tenants/create', [AdminController::class, 'createTenant'])->name('create-tenant');
    Route::post('tenants', [AdminController::class, 'storeTenant'])->name('store-tenant');
    Route::get('tenants/{tenant}/edit', [AdminController::class, 'editTenant'])->name('edit-tenant');
    Route::put('tenants/{tenant}', [AdminController::class, 'updateTenant'])->name('update-tenant');
    Route::delete('tenants/{tenant}', [AdminController::class, 'deleteTenant'])->name('delete-tenant');
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Tenants management (يمكن إضافة middleware للـ super admin لاحقاً)
Route::resource('tenants', TenantController::class);

Route::resource('clients', ClientController::class);
Route::resource('associations', AssociationController::class);
Route::resource('safes', SafeController::class);
Route::resource('loans', LoanController::class);
Route::resource('deposits', DepositController::class);
Route::resource('debts', DebtController::class);
Route::resource('commissions', CommissionController::class)->except(['edit', 'update']);
Route::resource('penalties', PenaltyController::class);

Route::post('penalties/{penalty}/mark-paid', [PenaltyController::class, 'markPaid'])->name('penalties.markPaid');
Route::post('penalties/{penalty}/waive', [PenaltyController::class, 'waive'])->name('penalties.waive');

Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
Route::get('payments/create', [PaymentController::class, 'create'])->name('payments.create');
Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');

Route::get('receipts', [ReceiptController::class, 'index'])->name('receipts.index');
Route::get('receipts/create', [ReceiptController::class, 'create'])->name('receipts.create');
Route::post('receipts', [ReceiptController::class, 'store'])->name('receipts.store');

Route::post('association-members/{member}/collect', [AssociationController::class, 'collect'])->name('members.collect');
Route::post('association-members/{member}/force-end', [AssociationController::class, 'forceEnd'])->name('members.forceEnd');
Route::get('associations/{association}/edit-turns', [AssociationController::class, 'editTurns'])->name('associations.edit-turns');
Route::post('associations/{association}/update-turns', [AssociationController::class, 'updateTurns'])->name('associations.update-turns');
Route::post('associations/{association}/add-member', [AssociationController::class, 'addMember'])->name('associations.add-member');
Route::delete('association-members/{member}', [AssociationController::class, 'deleteMember'])->name('members.delete');

Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('reports/payments-receipts', [ReportController::class, 'paymentsReceipts'])->name('reports.payments-receipts');
Route::get('reports/client-activity', [ReportController::class, 'clientActivity'])->name('reports.client-activity');
Route::get('reports/safe-activity', [ReportController::class, 'safeActivity'])->name('reports.safe-activity');
Route::get('reports/upcoming-completions', [ReportController::class, 'upcomingCompletions'])->name('reports.upcoming-completions');
Route::get('reports/liquidity', [ReportController::class, 'liquidity'])->name('reports.liquidity');
Route::get('reports/risky-clients', [ReportController::class, 'riskyClients'])->name('reports.risky-clients');
});
