<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\InventoryItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Display the main reports dashboard
     */
    public function index(Request $request)
    {
        $period = $request->get('period', '30days');
        $dateFrom = $this->getDateFrom($period);

        // Overview stats
        $stats = [
            'total_revenue' => Order::where('created_at', '>=', $dateFrom)
                ->sum('paid_amount'),
            'total_orders' => Order::where('created_at', '>=', $dateFrom)->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')
                ->where('created_at', '>=', $dateFrom)->count(),
            'total_customers' => User::where('role', 'customer')
                ->where('created_at', '>=', $dateFrom)->count(),
            'low_stock_items' => InventoryItem::where('quantity', '<=', DB::raw('min_stock_level'))->count(),
        ];

        // Revenue chart data (last 30 days)
        $revenueChart = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(paid_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->where('paid_amount', '>', 0)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $dateFrom)
            ->groupBy('status')
            ->get();

        // Payment status breakdown
        $paymentsByStatus = Order::select('payment_status', DB::raw('SUM(paid_amount) as amount'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $dateFrom)
            ->groupBy('payment_status')
            ->get();

        // Top selling design types
        $topDesignTypes = Order::select('design_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(paid_amount) as revenue'))
            ->where('created_at', '>=', $dateFrom)
            ->groupBy('design_type')
            ->orderBy('count', 'desc')
            ->get();

        return view('admin.reports.index', compact(
            'stats', 'revenueChart', 'ordersByStatus',
            'paymentsByStatus', 'topDesignTypes', 'period'
        ));
    }

    /**
     * Revenue analytics
     */
    public function revenue(Request $request)
    {
        $period = $request->get('period', '30days');
        $dateFrom = $this->getDateFrom($period);

        // Daily revenue for chart
        $dailyRevenue = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(paid_amount) as revenue'),
                DB::raw('SUM(total_amount) as total_ordered'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('created_at', '>=', $dateFrom)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly revenue comparison
        $monthlyRevenue = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(paid_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Revenue by payment method
        $revenueByPayment = DB::table('orders')
            ->select(
                DB::raw('CASE WHEN paid_amount > 0 THEN "Online Payment" ELSE "Pending Payment" END as method'),
                DB::raw('SUM(paid_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('created_at', '>=', $dateFrom)
            ->groupBy('method')
            ->get();

        // Average order value
        $avgOrderValue = Order::where('created_at', '>=', $dateFrom)
            ->where('paid_amount', '>', 0)
            ->avg('paid_amount');

        return view('admin.reports.revenue', compact(
            'dailyRevenue', 'monthlyRevenue', 'revenueByPayment',
            'avgOrderValue', 'period'
        ));
    }

    /**
     * Orders analytics
     */
    public function orders(Request $request)
    {
        $period = $request->get('period', '30days');
        $dateFrom = $this->getDateFrom($period);

        // Orders by status over time
        $ordersByStatus = DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) as date'),
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $dateFrom)
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get();

        // Recent orders for display
        $processingTimes = Order::select(
                'order_number',
                'created_at',
                'updated_at',
                'status',
                'total_amount',
                DB::raw('DATEDIFF(COALESCE(updated_at, NOW()), created_at) as processing_days')
            )
            ->where('created_at', '>=', $dateFrom)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Design type popularity
        $designTypeStats = Order::select(
                'design_type',
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG(total_amount) as avg_amount'),
                DB::raw('SUM(paid_amount) as total_revenue')
            )
            ->where('created_at', '>=', $dateFrom)
            ->groupBy('design_type')
            ->orderBy('count', 'desc')
            ->get();

        // Order fulfillment rate
        $fulfillmentRate = [
            'total_orders' => Order::where('created_at', '>=', $dateFrom)->count(),
            'completed_orders' => Order::whereIn('status', ['completed', 'ready'])
                ->where('created_at', '>=', $dateFrom)->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')
                ->where('created_at', '>=', $dateFrom)->count(),
        ];

        return view('admin.reports.orders', compact(
            'ordersByStatus', 'processingTimes', 'designTypeStats',
            'fulfillmentRate', 'period'
        ));
    }

    /**
     * Inventory analytics
     */
    public function inventory(Request $request)
    {
        // Current inventory status
        $inventoryOverview = [
            'total_items' => InventoryItem::count(),
            'total_value' => InventoryItem::sum(DB::raw('quantity * COALESCE(NULLIF(cost_per_unit, 0), unit_price)')),
            'low_stock_items' => InventoryItem::where('quantity', '<=', DB::raw('min_stock_level'))->count(),
            'out_of_stock_items' => InventoryItem::where('quantity', 0)->count(),
        ];

        // Low stock items
        $lowStockItems = InventoryItem::select('name', 'quantity', 'min_stock_level', 'cost_per_unit')
            ->where('quantity', '<=', DB::raw('min_stock_level'))
            ->orderBy('quantity', 'asc')
            ->get();

        // Most used materials (from order_materials)
        $mostUsedMaterials = DB::table('order_materials')
            ->join('inventory_items', 'order_materials.inventory_id', '=', 'inventory_items.id')
            ->select(
                'inventory_items.name',
                DB::raw('SUM(order_materials.quantity_used) as total_used'),
                DB::raw('COUNT(DISTINCT order_materials.order_id) as orders_count'),
                'inventory_items.quantity as current_stock'
            )
            ->groupBy('inventory_items.id', 'inventory_items.name', 'inventory_items.quantity')
            ->orderBy('total_used', 'desc')
            ->limit(10)
            ->get();

        // Inventory value by category
        $inventoryByCategory = InventoryItem::select(
                'category',
                DB::raw('COUNT(*) as items_count'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(quantity * COALESCE(NULLIF(cost_per_unit, 0), unit_price)) as total_value')
            )
            ->groupBy('category')
            ->orderBy('total_value', 'desc')
            ->get();

        // Stock movement (recent activity)
        $recentStockMovements = DB::table('orders')
            ->join('order_materials', 'orders.id', '=', 'order_materials.order_id')
            ->join('inventory_items', 'order_materials.inventory_id', '=', 'inventory_items.id')
            ->select(
                'orders.order_number',
                'inventory_items.name as material_name',
                'order_materials.quantity_used',
                'orders.created_at',
                'orders.status'
            )
            ->where('orders.created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('orders.created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.reports.inventory', compact(
            'inventoryOverview', 'lowStockItems', 'mostUsedMaterials',
            'inventoryByCategory', 'recentStockMovements'
        ));
    }

    /**
     * Customer analytics
     */
    public function customers(Request $request)
    {
        $period = $request->get('period', '30days');
        $dateFrom = $this->getDateFrom($period);

        // Customer overview stats
        $customerStats = [
            'total_customers' => User::where('role', 'customer')->count(),
            'new_customers' => User::where('role', 'customer')
                ->where('created_at', '>=', $dateFrom)->count(),
            'active_customers' => User::where('role', 'customer')
                ->whereHas('orders', function($query) use ($dateFrom) {
                    $query->where('created_at', '>=', $dateFrom);
                })->count(),
        ];

        // Customer registration trend
        $customerRegistrations = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('role', 'customer')
            ->where('created_at', '>=', $dateFrom)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top customers by orders
        $topCustomers = User::where('role', 'customer')
            ->withCount(['orders' => function($query) use ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            }])
            ->with(['orders' => function($query) use ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            }])
            ->having('orders_count', '>', 0)
            ->orderBy('orders_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports.customers', compact(
            'customerStats', 'customerRegistrations', 'topCustomers', 'period'
        ));
    }

    /**
     * Payment transactions report
     */
    public function payments(Request $request)
    {
        $period = $request->get('period', '30days');
        $dateFrom = $this->getDateFrom($period);

        // Payment overview stats
        $paymentStats = [
            'total_payments' => Order::where('created_at', '>=', $dateFrom)
                ->where('paid_amount', '>', 0)->sum('paid_amount'),
            'total_transactions' => Order::where('created_at', '>=', $dateFrom)
                ->where('paid_amount', '>', 0)->count(),
            'pending_payments' => Order::where('created_at', '>=', $dateFrom)
                ->where('payment_status', 'pending')->sum('total_amount'),
            'average_payment' => Order::where('created_at', '>=', $dateFrom)
                ->where('paid_amount', '>', 0)->avg('paid_amount'),
        ];

        // Recent payment transactions
        $recentPayments = Order::select('order_number', 'created_at', 'paid_amount', 'total_amount', 'payment_status')
            ->where('created_at', '>=', $dateFrom)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        // Daily payment totals
        $dailyPayments = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(paid_amount) as total_paid'),
                DB::raw('COUNT(*) as transactions')
            )
            ->where('created_at', '>=', $dateFrom)
            ->where('paid_amount', '>', 0)
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.reports.payments', compact(
            'paymentStats', 'recentPayments', 'dailyPayments', 'period'
        ));
    }

    /**
     * Export reports
     */
    public function export($type, Request $request)
    {
        $period = $request->get('period', '30days');
        $dateFrom = $this->getDateFrom($period);

        switch ($type) {
            case 'orders':
                return $this->exportOrders($dateFrom);
            case 'payments':
                return $this->exportPayments($dateFrom);
            case 'inventory':
                return $this->exportInventory();
            default:
                return response()->json(['error' => 'Invalid export type'], 404);
        }
    }

    private function getDateFrom($period)
    {
        switch ($period) {
            case 'today':
                return Carbon::today();
            case '7days':
                return Carbon::now()->subDays(7);
            case '30days':
                return Carbon::now()->subDays(30);
            case '90days':
                return Carbon::now()->subDays(90);
            case '1year':
                return Carbon::now()->subYear();
            case 'all':
                return Carbon::create(2000, 1, 1); // Very old date for "all time"
            default:
                return Carbon::now()->subDays(30);
        }
    }

    private function exportRevenue($dateFrom)
    {
        $revenue = Order::select(
                'order_number',
                'created_at',
                'total_amount',
                'paid_amount',
                'payment_status',
                'status'
            )
            ->where('created_at', '>=', $dateFrom)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'revenue_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($revenue) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order Number', 'Date', 'Total Amount', 'Paid Amount', 'Payment Status', 'Order Status']);

            foreach ($revenue as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i:s'),
                    number_format($order->total_amount, 2),
                    number_format($order->paid_amount, 2),
                    $order->payment_status,
                    $order->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportOrders($dateFrom)
    {
        $orders = Order::with('user')
            ->where('created_at', '>=', $dateFrom)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'orders_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order Number', 'Customer', 'Date', 'Design Type', 'Status', 'Total Amount', 'Paid Amount']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name ?? 'N/A',
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->design_type,
                    $order->status,
                    number_format($order->total_amount, 2),
                    number_format($order->paid_amount, 2)
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPayments($dateFrom)
    {
        $payments = Order::with('user')
            ->where('created_at', '>=', $dateFrom)
            ->where('paid_amount', '>', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'payments_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Order Number', 'Customer', 'Date', 'Total Amount', 'Paid Amount', 'Balance', 'Payment Status']);

            foreach ($payments as $payment) {
                $balance = $payment->total_amount - $payment->paid_amount;
                $paymentStatus = 'Pending';

                if ($payment->paid_amount >= $payment->total_amount && $payment->paid_amount > 0) {
                    $paymentStatus = 'Paid';
                } elseif ($payment->paid_amount > 0 && $payment->paid_amount < $payment->total_amount) {
                    $paymentStatus = 'Partial';
                }

                fputcsv($file, [
                    $payment->order_number,
                    $payment->user->name ?? 'N/A',
                    $payment->created_at->format('Y-m-d H:i:s'),
                    number_format($payment->total_amount, 2),
                    number_format($payment->paid_amount, 2),
                    number_format($balance, 2),
                    $paymentStatus
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportInventory()
    {
        $inventory = InventoryItem::orderBy('name')->get();

        $filename = 'inventory_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($inventory) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Category', 'Quantity', 'Min Stock Level', 'Unit Price', 'Cost Per Unit', 'Total Value']);

            foreach ($inventory as $item) {
                $price = $item->cost_per_unit > 0 ? $item->cost_per_unit : $item->unit_price;
                $totalValue = $item->quantity * $price;

                fputcsv($file, [
                    $item->name,
                    $item->category ?: 'Uncategorized',
                    $item->quantity,
                    $item->min_stock_level,
                    number_format($item->unit_price, 2),
                    number_format($item->cost_per_unit, 2),
                    number_format($totalValue, 2)
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportCustomers($dateFrom)
    {
        $customers = User::where('role', 'customer')
            ->withCount(['orders' => function($query) use ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            }])
            ->with(['orders' => function($query) use ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'customers_report_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Registration Date', 'Total Orders', 'Total Revenue']);

            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->name,
                    $customer->email,
                    $customer->created_at->format('Y-m-d H:i:s'),
                    $customer->orders_count,
                    number_format($customer->orders->sum('paid_amount'), 2)
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
