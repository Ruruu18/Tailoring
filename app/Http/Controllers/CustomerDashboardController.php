<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Summary data
        $activeOrdersCount = Order::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'in_progress', 'ready'])
            ->count();
        $pendingPayments = Order::where('user_id', $user->id)
            ->whereRaw('paid_amount < total_amount')
            ->selectRaw('SUM(total_amount - paid_amount) as pending')
            ->value('pending') ?? 0;
        $completedOrdersCount = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        $nextAppointment = Appointment::where('user_id', $user->id)
            ->where('appointment_date', '>', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_date')
            ->first();

        // Recent orders (last 5)
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent notifications (last 10 for scrollable container)
        $recentNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Unread notifications count
        $unreadNotificationsCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Last measurement update
        $lastMeasurementUpdate = $user->measurements?->updated_at;

        return view('dashboard', compact(
            'user',
            'activeOrdersCount',
            'pendingPayments',
            'completedOrdersCount',
            'nextAppointment',
            'recentOrders',
            'recentNotifications',
            'unreadNotificationsCount',
            'lastMeasurementUpdate'
        ));
    }

    public function trackOrder(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.track', compact('order'));
    }

    public function markNotificationAsRead(Notification $notification)
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Get dashboard statistics for real-time updates (API)
     */
    public function getDashboardStats()
    {
        $user = Auth::user();

        $activeOrdersCount = Order::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'in_progress', 'ready'])
            ->count();

        $pendingPayments = Order::where('user_id', $user->id)
            ->whereRaw('paid_amount < total_amount')
            ->selectRaw('SUM(total_amount - paid_amount) as pending')
            ->value('pending') ?? 0;

        $completedOrdersCount = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $unreadNotificationsCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'active_orders_count' => $activeOrdersCount,
            'pending_payments' => $pendingPayments,
            'completed_orders_count' => $completedOrdersCount,
            'unread_notifications_count' => $unreadNotificationsCount
        ]);
    }
}
