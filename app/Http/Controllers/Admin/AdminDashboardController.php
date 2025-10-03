<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Analytics data
        $totalCustomers = User::where('role', 'customer')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $pendingOrders = Order::where('status', 'pending')->count();
        $inProgressOrders = Order::where('status', 'in_progress')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $totalAppointments = Appointment::count();
        $pendingAppointments = Appointment::where('status', 'scheduled')->count();

        // Recent orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly revenue chart data
        $monthlyRevenue = Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Order status distribution
        $orderStatusData = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Top customers by order value
        $topCustomers = User::where('role', 'customer')
            ->withSum('orders', 'total_amount')
            ->orderBy('orders_sum_total_amount', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'inProgressOrders',
            'completedOrders',
            'totalAppointments',
            'pendingAppointments',
            'recentOrders',
            'monthlyRevenue',
            'orderStatusData',
            'topCustomers'
        ));
    }
}
