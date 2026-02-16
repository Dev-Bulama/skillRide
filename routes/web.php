<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Manager;
use App\Http\Controllers\Rider;

// Landing page
Route::get('/', [App\Http\Controllers\LandingPageController::class, 'index'])->name('landing');

// Reports fraud alias
Route::get('/admin/reports/fraud', function () {
    return redirect()->route('admin.fraud.index');
})->name('admin.reports.fraud');

// QR Verification (public)
Route::get('/verify/{code}', [Rider\QrCodeController::class, 'verify'])->name('verify.qr');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Role-based dashboard redirect
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'rider' => redirect()->route('rider.dashboard'),
            default => redirect()->route('login'),
        };
    })->name('dashboard');

    // ========== ADMIN ROUTES ==========
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Vehicles
        Route::resource('vehicles', Admin\VehicleController::class);
        Route::post('vehicles/{vehicle}/assign-rider', [Admin\VehicleController::class, 'assignRider'])->name('vehicles.assign-rider');
        Route::post('vehicles/{vehicle}/unassign-rider', [Admin\VehicleController::class, 'unassignRider'])->name('vehicles.unassign-rider');
        Route::get('vehicles/{vehicle}/qr', [Admin\VehicleController::class, 'generateQr'])->name('vehicles.qr');
        Route::get('vehicles/{vehicle}/track', [Admin\VehicleController::class, 'track'])->name('vehicles.track');
        Route::get('vehicles-export', [Admin\VehicleController::class, 'exportList'])->name('vehicles.export');

        // Riders
        Route::resource('riders', Admin\RiderController::class);
        Route::post('riders/{rider}/approve', [Admin\RiderController::class, 'approve'])->name('riders.approve');
        Route::post('riders/{rider}/suspend', [Admin\RiderController::class, 'suspend'])->name('riders.suspend');
        Route::post('riders/{rider}/activate', [Admin\RiderController::class, 'activate'])->name('riders.activate');
        Route::get('riders-verification', [Admin\RiderController::class, 'verificationDashboard'])->name('riders.verification');
        Route::get('riders/{rider}/documents', [Admin\RiderController::class, 'documents'])->name('riders.documents');

        // Managers
        Route::resource('managers', Admin\ManagerController::class);
        Route::post('managers/{manager}/assign-routes', [Admin\ManagerController::class, 'assignRoutes'])->name('managers.assign-routes');

        // Payments
        Route::get('payments', [Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [Admin\PaymentController::class, 'show'])->name('payments.show');
        Route::post('payments/{payment}/approve', [Admin\PaymentController::class, 'approve'])->name('payments.approve');
        Route::get('payment-plans', [Admin\PaymentController::class, 'configurePlans'])->name('payments.plans');
        Route::post('payment-plans', [Admin\PaymentController::class, 'storePlan'])->name('payments.plans.store');
        Route::put('payment-plans/{plan}', [Admin\PaymentController::class, 'updatePlan'])->name('payments.plans.update');
        Route::delete('payment-plans/{plan}', [Admin\PaymentController::class, 'deletePlan'])->name('payments.plans.delete');
        Route::post('payment-plans/{plan}/toggle', [Admin\PaymentController::class, 'togglePlan'])->name('payments.plans.toggle');

        // Payout Requests
        Route::get('payout-requests', [Admin\PaymentController::class, 'payoutRequests'])->name('payouts.index');
        Route::post('payout-requests/{payout}/approve', [Admin\PaymentController::class, 'approvePayout'])->name('payouts.approve');
        Route::post('payout-requests/{payout}/reject', [Admin\PaymentController::class, 'rejectPayout'])->name('payouts.reject');
        Route::get('payments-arrears', [Admin\PaymentController::class, 'arrears'])->name('payments.arrears');
        Route::get('payments-revenue', [Admin\PaymentController::class, 'revenue'])->name('payments.revenue');
        Route::get('payments-export', [Admin\PaymentController::class, 'exportPayments'])->name('payments.export');

        // Settings
        Route::get('settings', [Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [Admin\SettingsController::class, 'update'])->name('settings.update');
        Route::get('settings/branding', [Admin\SettingsController::class, 'branding'])->name('settings.branding');
        Route::put('settings/branding', [Admin\SettingsController::class, 'updateBranding'])->name('settings.branding.update');
        Route::get('settings/notifications', [Admin\SettingsController::class, 'notifications'])->name('settings.notifications');
        Route::put('settings/notifications', [Admin\SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');
        Route::get('settings/routes', [Admin\SettingsController::class, 'routes'])->name('settings.routes');
        Route::post('settings/routes', [Admin\SettingsController::class, 'storeRoute'])->name('settings.routes.store');
        Route::put('settings/routes/{route}', [Admin\SettingsController::class, 'updateRoute'])->name('settings.routes.update');
        Route::delete('settings/routes/{route}', [Admin\SettingsController::class, 'deleteRoute'])->name('settings.routes.delete');

        // Reports
        Route::get('reports', [Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/revenue', [Admin\ReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('reports/riders', [Admin\ReportController::class, 'riders'])->name('reports.riders');
        Route::get('reports/fleet', [Admin\ReportController::class, 'fleet'])->name('reports.fleet');
        Route::get('reports/export', [Admin\ReportController::class, 'export'])->name('reports.export');
        Route::get('reports/pdf', [Admin\ReportController::class, 'downloadPdf'])->name('reports.pdf');

        // Audit Logs
        Route::get('audit', [Admin\AuditController::class, 'index'])->name('audit.index');
        Route::get('audit/{log}', [Admin\AuditController::class, 'show'])->name('audit.show');
        Route::get('audit/user/{user}', [Admin\AuditController::class, 'userActivity'])->name('audit.user');

        // Fraud Alerts
        Route::get('fraud-alerts', [Admin\FraudAlertController::class, 'index'])->name('fraud.index');
        Route::get('fraud-alerts/{alert}', [Admin\FraudAlertController::class, 'show'])->name('fraud.show');
        Route::post('fraud-alerts/{alert}/resolve', [Admin\FraudAlertController::class, 'resolve'])->name('fraud.resolve');
        Route::post('fraud-alerts/{alert}/dismiss', [Admin\FraudAlertController::class, 'dismiss'])->name('fraud.dismiss');

        // System Updates
        Route::get('system-updates', [Admin\SystemUpdateController::class, 'index'])->name('system-updates.index');
        Route::post('system-updates/upload', [Admin\SystemUpdateController::class, 'upload'])->name('system-updates.upload');
        Route::post('system-updates/{update}/apply', [Admin\SystemUpdateController::class, 'apply'])->name('system-updates.apply');
        Route::post('system-updates/{update}/rollback', [Admin\SystemUpdateController::class, 'rollback'])->name('system-updates.rollback');

        // Impersonation
        Route::post('impersonate/{user}', [Admin\ImpersonateController::class, 'start'])->name('impersonate.start');
        Route::post('impersonate-stop', [Admin\ImpersonateController::class, 'stop'])->name('impersonate.stop');
    });

    // ========== MANAGER ROUTES ==========
    Route::middleware(['role:manager'])->prefix('manager')->name('manager.')->group(function () {

        Route::get('/dashboard', [Manager\DashboardController::class, 'index'])->name('dashboard');

        // Riders
        Route::get('riders', [Manager\RiderController::class, 'index'])->name('riders.index');
        Route::get('riders/{rider}', [Manager\RiderController::class, 'show'])->name('riders.show');
        Route::get('riders/{rider}/payment-status', [Manager\RiderController::class, 'paymentStatus'])->name('riders.payment-status');

        // Routes
        Route::get('routes', [Manager\RouteController::class, 'index'])->name('routes.index');
        Route::get('routes/{route}', [Manager\RouteController::class, 'show'])->name('routes.show');
        Route::get('tracking', [Manager\RouteController::class, 'track'])->name('routes.track');

        // Maintenance
        Route::get('maintenance', [Manager\MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::get('maintenance/{record}', [Manager\MaintenanceController::class, 'show'])->name('maintenance.show');
        Route::post('maintenance/{record}/approve', [Manager\MaintenanceController::class, 'approve'])->name('maintenance.approve');
        Route::get('maintenance-schedules', [Manager\MaintenanceController::class, 'schedules'])->name('maintenance.schedules');

        // Vehicle Assignment
        Route::get('vehicles', [Manager\VehicleController::class, 'index'])->name('vehicles.index');
        Route::get('vehicles/{vehicle}/assign', [Manager\VehicleController::class, 'showAssign'])->name('vehicles.assign');
        Route::post('vehicles/{vehicle}/assign', [Manager\VehicleController::class, 'assignRider'])->name('vehicles.assign.store');
        Route::post('vehicles/{vehicle}/unassign', [Manager\VehicleController::class, 'unassignRider'])->name('vehicles.unassign');

        // Payments
        Route::get('payments', [Manager\PaymentController::class, 'index'])->name('payments.index');
        Route::get('payout', [Manager\PaymentController::class, 'requestPayout'])->name('payments.payout');
        Route::post('payout', [Manager\PaymentController::class, 'storePayout'])->name('payments.payout.store');
        Route::get('payout-history', [Manager\PaymentController::class, 'payoutHistory'])->name('payments.history');
        Route::get('payments-report', [Manager\PaymentController::class, 'downloadReport'])->name('payments.report');
    });

    // ========== RIDER ROUTES ==========
    Route::middleware(['role:rider'])->prefix('rider')->name('rider.')->group(function () {

        Route::get('/dashboard', [Rider\DashboardController::class, 'index'])->name('dashboard');

        // Payments
        Route::get('payments', [Rider\PaymentController::class, 'index'])->name('payments.index');
        Route::post('payments/pay', [Rider\PaymentController::class, 'pay'])->name('payments.pay');
        Route::get('payments/callback', [Rider\PaymentController::class, 'callback'])->name('payments.callback');
        Route::get('payments/{payment}/receipt', [Rider\PaymentController::class, 'receipt'])->name('payments.receipt');

        // Maintenance
        Route::get('maintenance', [Rider\MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::get('maintenance/create', [Rider\MaintenanceController::class, 'create'])->name('maintenance.create');
        Route::post('maintenance', [Rider\MaintenanceController::class, 'store'])->name('maintenance.store');
        Route::get('maintenance/{record}', [Rider\MaintenanceController::class, 'show'])->name('maintenance.show');

        // QR Code
        Route::get('qr', [Rider\QrCodeController::class, 'index'])->name('qr.index');
        Route::post('qr/generate', [Rider\QrCodeController::class, 'generate'])->name('qr.generate');

        // Work Logs
        Route::get('work', [Rider\WorkLogController::class, 'index'])->name('work.index');
        Route::post('work/clock-in', [Rider\WorkLogController::class, 'clockIn'])->name('work.clock-in');
        Route::post('work/clock-out', [Rider\WorkLogController::class, 'clockOut'])->name('work.clock-out');
        Route::post('work/assistant', [Rider\WorkLogController::class, 'registerAssistant'])->name('work.assistant');

        // Profile
        Route::get('profile', [Rider\ProfileController::class, 'show'])->name('profile.show');
        Route::get('profile/edit', [Rider\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [Rider\ProfileController::class, 'update'])->name('profile.update');
        Route::post('profile/document', [Rider\ProfileController::class, 'uploadDocument'])->name('profile.document');
    });
});

require __DIR__.'/auth.php';
