<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

Route::middleware('auth:sanctum')->group(function () {
    // GPS Tracking
    Route::post('gps/update', [Api\GpsController::class, 'updateLocation']);
    Route::get('gps/vehicle/{vehicleId}', [Api\GpsController::class, 'getVehicleLocation']);
    Route::get('gps/active-vehicles', [Api\GpsController::class, 'getActiveVehicles']);
    Route::get('gps/trail/{vehicleId}', [Api\GpsController::class, 'getVehicleTrail']);

    // Notifications
    Route::get('notifications', [Api\NotificationController::class, 'index']);
    Route::post('notifications/{id}/read', [Api\NotificationController::class, 'markRead']);
    Route::get('notifications/unread-count', [Api\NotificationController::class, 'unreadCount']);

    // Dashboard Stats (AJAX)
    Route::get('stats/admin', [Api\DashboardStatsController::class, 'adminStats']);
    Route::get('stats/manager', [Api\DashboardStatsController::class, 'managerStats']);
    Route::get('stats/rider', [Api\DashboardStatsController::class, 'riderStats']);
});
