<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DesignBrochureController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\MeasurementController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SmsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Customer Management
    Route::resource('customers', CustomerController::class);

    // Measurement Management
    Route::resource('measurements', MeasurementController::class);

    // Appointment Management
    Route::resource('appointments', AppointmentController::class);

    // Order Management
    Route::resource('orders', AdminOrderController::class)->except(['create', 'store']);
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('orders/{order}/set-price', [AdminOrderController::class, 'setPrice'])->name('orders.set-price');
    Route::patch('orders/{order}/confirm', [AdminOrderController::class, 'confirmOrder'])->name('orders.confirm');
    Route::patch('orders/{order}/cancel', [AdminOrderController::class, 'cancelOrder'])->name('orders.cancel');

    // Order Materials Management
    Route::post('orders/{order}/materials', [AdminOrderController::class, 'addMaterial'])->name('orders.materials.add');
    Route::delete('orders/{order}/materials/{orderMaterial}', [AdminOrderController::class, 'removeMaterial'])->name('orders.materials.remove');
    Route::patch('orders/{order}/materials/{orderMaterial}/deduct', [AdminOrderController::class, 'deductMaterial'])->name('orders.materials.deduct');
    Route::patch('orders/{order}/materials/deduct-all', [AdminOrderController::class, 'deductAllMaterials'])->name('orders.materials.deduct-all');

    // Design Brochure Management
    Route::resource('design-brochures', DesignBrochureController::class);
    Route::post('design-brochures/{designBrochure}/delete-image', [DesignBrochureController::class, 'deleteImage'])->name('design-brochures.delete-image');
    Route::patch('design-brochures/{designBrochure}/toggle-featured', [DesignBrochureController::class, 'toggleFeatured'])->name('design-brochures.toggle-featured');
    Route::patch('design-brochures/{designBrochure}/toggle-active', [DesignBrochureController::class, 'toggleActive'])->name('design-brochures.toggle-active');
    Route::patch('design-brochures/sort-order', [DesignBrochureController::class, 'updateSortOrder'])->name('design-brochures.sort-order');

    // Inventory Management
    Route::get('inventory/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low-stock');
    Route::patch('inventory/{inventory}/stock', [InventoryController::class, 'updateStock'])->name('inventory.update-stock');
    Route::delete('inventory/{inventory}/image', [InventoryController::class, 'deleteImage'])->name('inventory.delete-image');
    Route::resource('inventory', InventoryController::class);

    // Simple Reports
    Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('reports/orders', [ReportsController::class, 'orders'])->name('reports.orders');
    Route::get('reports/payments', [ReportsController::class, 'payments'])->name('reports.payments');
    Route::get('reports/inventory', [ReportsController::class, 'inventory'])->name('reports.inventory');
    Route::get('reports/export/{type}', [ReportsController::class, 'export'])->name('reports.export');

    // SMS Management
    Route::get('sms', [SmsController::class, 'index'])->name('sms.index');
    Route::get('sms/compose', [SmsController::class, 'compose'])->name('sms.compose');
    Route::post('sms/send', [SmsController::class, 'send'])->name('sms.send');
    Route::get('sms/bulk', [SmsController::class, 'bulkSend'])->name('sms.bulk');
    Route::post('sms/bulk', [SmsController::class, 'sendBulk'])->name('sms.send-bulk');
    Route::get('sms/settings', [SmsController::class, 'settings'])->name('sms.settings');
    Route::post('sms/test', [SmsController::class, 'testConnection'])->name('sms.test');
    Route::get('sms/{smsLog}', [SmsController::class, 'show'])->name('sms.show');
});
