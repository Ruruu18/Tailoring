<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\DesignBrochureController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [CustomerDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Design Brochures (public access)
Route::get('/designs', [DesignBrochureController::class, 'index'])->name('designs.index');
Route::get('/designs/{designBrochure}', [DesignBrochureController::class, 'show'])->name('designs.show');
Route::get('/api/designs', [DesignBrochureController::class, 'api'])->name('designs.api');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard related routes
    Route::get('/orders/{order}/track', [CustomerDashboardController::class, 'trackOrder'])->name('orders.track');

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Orders routes
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{order}/delete-image', [OrderController::class, 'deleteImage'])->name('orders.delete-image');
    Route::post('/orders/{order}/downpayment', [OrderController::class, 'downpayment'])->name('orders.downpayment');

    // Downpayment via PayMongo routes
    Route::post('/orders/{order}/downpayment/gcash', [PaymentController::class, 'downpaymentWithGCash'])->name('payment.downpayment.gcash');
    Route::post('/orders/{order}/downpayment/paymaya', [PaymentController::class, 'downpaymentWithPayMaya'])->name('payment.downpayment.paymaya');

    // API routes for real-time updates
    Route::get('/api/orders/{order}/status', [OrderController::class, 'getOrderStatus'])->name('api.orders.status');
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('api.notifications.unread-count');
    Route::get('/api/dashboard/stats', [CustomerDashboardController::class, 'getDashboardStats'])->name('api.dashboard.stats');

    // Measurements routes
    Route::resource('measurements', MeasurementController::class);
    Route::get('/measurements/history', [MeasurementController::class, 'history'])->name('measurements.history');

    // Appointments routes
    Route::resource('appointments', AppointmentController::class)->except(['destroy']);
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

    // Payment routes
    Route::post('/payment/gcash/{order}', [PaymentController::class, 'payWithGCash'])->name('payment.gcash');
    Route::post('/payment/paymaya/{order}', [PaymentController::class, 'payWithPayMaya'])->name('payment.paymaya');
    Route::get('/payment/callback/{order}', [PaymentController::class, 'paymentCallback'])->name('payment.callback');
});

// PayMongo webhook (no auth required)
Route::post('/webhook/paymongo', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Include admin routes
require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
