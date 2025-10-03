<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderMaterial;
use App\Models\InventoryItem;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Date filtering
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Set price for an order
     */
    public function setPrice(Request $request, Order $order)
    {
        $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $order->status;

        $order->update([
            'total_amount' => $request->total_amount,
            'notes' => $request->notes,
            'status' => 'quoted', // Change status to quoted when price is set
        ]);

        // Send notification to customer about the quote
        NotificationService::orderStatusUpdated($order, $oldStatus, 'quoted');

        return redirect()->back()->with('success', 'Price set successfully. Order status updated to quoted.');
    }

    /**
     * Confirm order (accept the tailoring job)
     */
    public function confirmOrder(Order $order)
    {
        $oldStatus = $order->status;

        $order->update([
            'status' => 'confirmed',
        ]);

        // Send notification to customer about order confirmation
        NotificationService::orderStatusUpdated($order, $oldStatus, 'confirmed');

        return redirect()->back()->with('success', 'Order confirmed successfully. Work can now begin.');
    }

    /**
     * Cancel order with reason
     */
    public function cancelOrder(Request $request, Order $order)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:1000',
        ]);

        $oldStatus = $order->status;

        $order->update([
            'status' => 'cancelled',
            'notes' => ($order->notes ? $order->notes."\n\n" : '').'CANCELLED: '.$request->cancellation_reason,
        ]);

        // Send notification to customer about order cancellation
        NotificationService::orderStatusUpdated($order, $oldStatus, 'cancelled');

        return redirect()->back()->with('success', 'Order has been cancelled successfully.');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'appointments', 'designBrochure.materials', 'materials.inventoryItem']);

        // Get all inventory items for adding materials
        $inventoryItems = InventoryItem::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        // Calculate material cost analytics
        $materialCosts = $this->getMaterialCostAnalytics($order);

        return view('admin.orders.show', compact('order', 'inventoryItems', 'materialCosts'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();

        return view('admin.orders.edit', compact('order', 'customers'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.description' => 'nullable|string',
            'status' => 'required|in:pending,quoted,confirmed,in_progress,ready,completed,cancelled',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            // Removed design_images validation - images are now read-only
        ]);

        // Design images are preserved as-is (read-only for admin)
        $order->update([
            'user_id' => $request->user_id,
            'items' => $request->items,
            'status' => $request->status,
            'total_amount' => $request->total_amount,
            'paid_amount' => $request->paid_amount ?? 0,
            'delivery_date' => $request->delivery_date,
            'notes' => $request->notes,
            // design_images field is intentionally omitted to preserve existing images
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        // Delete associated design images
        if ($order->design_images) {
            foreach ($order->design_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,quoted,confirmed,in_progress,ready,completed,cancelled',
            'status_reason' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Handle inventory deduction when order moves to in_progress
        if ($newStatus === 'in_progress' && $oldStatus !== 'in_progress') {
            $this->handleInventoryDeduction($order);
        }

        // Update the order status
        $order->update(['status' => $newStatus]);

        // Send notification to customer about status change
        NotificationService::orderStatusUpdated($order, $oldStatus, $newStatus);

        // Log the status change in notes if reason is provided
        if ($request->status_reason) {
            $statusChangeLog = "\n\n--- Status Change Log ---\n";
            $statusChangeLog .= "Changed from '{$oldStatus}' to '{$newStatus}' on ".now()->format('Y-m-d H:i:s')."\n";
            $statusChangeLog .= 'Reason: '.$request->status_reason."\n";
            $statusChangeLog .= 'Changed by: '.Auth::user()->name.' (Admin)';

            $order->update([
                'notes' => ($order->notes ?? '').$statusChangeLog,
            ]);
        }

        return redirect()->back()->with('success', "Order status updated from '{$oldStatus}' to '{$newStatus}' successfully.");
    }

    /**
     * Add material to order
     */
    public function addMaterial(Request $request, Order $order)
    {
        $request->validate([
            'inventory_id' => 'required|exists:inventory_items,id',
            'quantity_used' => 'required|numeric|min:0.001',
            'notes' => 'nullable|string|max:500',
        ]);

        $inventoryItem = InventoryItem::findOrFail($request->inventory_id);

        // Check if material already exists for this order
        $existingMaterial = OrderMaterial::where('order_id', $order->id)
            ->where('inventory_id', $request->inventory_id)
            ->first();

        if ($existingMaterial) {
            return response()->json([
                'success' => false,
                'message' => 'This material is already added to the order. Please edit the existing entry.'
            ], 400);
        }

        // Create order material
        $orderMaterial = OrderMaterial::create([
            'order_id' => $order->id,
            'inventory_id' => $request->inventory_id,
            'quantity_used' => $request->quantity_used,
            'unit' => $inventoryItem->unit,
            'unit_price_at_time' => $inventoryItem->unit_price,
            'notes' => $request->notes,
        ]);

        $orderMaterial->load('inventoryItem');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Material added successfully.',
                'material' => $orderMaterial
            ]);
        }

        return redirect()->back()->with('success', 'Material added successfully.');
    }

    /**
     * Remove material from order
     */
    public function removeMaterial(Order $order, OrderMaterial $orderMaterial)
    {
        // Check if material belongs to this order
        if ($orderMaterial->order_id !== $order->id) {
            abort(404);
        }

        // If material was deducted, add quantity back to inventory
        if ($orderMaterial->isDeducted()) {
            $orderMaterial->inventoryItem->addQuantity($orderMaterial->quantity_used);
        }

        $orderMaterial->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Material removed successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'Material removed successfully.');
    }

    /**
     * Deduct single material from inventory
     */
    public function deductMaterial(Order $order, OrderMaterial $orderMaterial)
    {
        // Check if material belongs to this order
        if ($orderMaterial->order_id !== $order->id) {
            abort(404);
        }

        // Check if already deducted
        if ($orderMaterial->isDeducted()) {
            return response()->json([
                'success' => false,
                'message' => 'Material has already been deducted from inventory.'
            ], 400);
        }

        // Check if sufficient quantity in inventory
        if (!$orderMaterial->inventoryItem->hasQuantity($orderMaterial->quantity_used)) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient quantity in inventory. Available: ' . $orderMaterial->inventoryItem->quantity . ' ' . $orderMaterial->inventoryItem->unit
            ], 400);
        }

        DB::transaction(function () use ($orderMaterial) {
            // Deduct from inventory
            $orderMaterial->inventoryItem->deductQuantity($orderMaterial->quantity_used);

            // Mark as deducted
            $orderMaterial->markAsDeducted();
        });

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Material deducted from inventory successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'Material deducted from inventory successfully.');
    }

    /**
     * Deduct all materials from inventory
     */
    public function deductAllMaterials(Order $order)
    {
        $pendingMaterials = $order->pendingMaterials;

        if ($pendingMaterials->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No pending materials to deduct.'
            ], 400);
        }

        $errors = [];
        $successCount = 0;

        DB::transaction(function () use ($pendingMaterials, &$errors, &$successCount) {
            foreach ($pendingMaterials as $material) {
                // Check if sufficient quantity in inventory
                if (!$material->inventoryItem->hasQuantity($material->quantity_used)) {
                    $errors[] = $material->inventoryItem->name . ' - Insufficient quantity (Available: ' . $material->inventoryItem->quantity . ' ' . $material->inventoryItem->unit . ')';
                    continue;
                }

                // Deduct from inventory
                $material->inventoryItem->deductQuantity($material->quantity_used);

                // Mark as deducted
                $material->markAsDeducted();

                $successCount++;
            }
        });

        $message = $successCount > 0 ? "Successfully deducted {$successCount} materials from inventory." : '';

        if (!empty($errors)) {
            $errorMessage = 'Some materials could not be deducted: ' . implode(', ', $errors);
            $message = $message ? $message . ' ' . $errorMessage : $errorMessage;
        }

        if ($successCount == 0) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 400);
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'has_errors' => !empty($errors)
            ]);
        }

        $alertType = !empty($errors) ? 'warning' : 'success';
        return redirect()->back()->with($alertType, $message);
    }

    /**
     * Handle automatic inventory deduction when order starts production
     */
    private function handleInventoryDeduction(Order $order)
    {
        $pendingMaterials = $order->pendingMaterials;

        if ($pendingMaterials->isEmpty()) {
            return;
        }

        $deductedCount = 0;
        $errors = [];

        DB::transaction(function () use ($pendingMaterials, &$deductedCount, &$errors) {
            foreach ($pendingMaterials as $material) {
                // Check if sufficient quantity in inventory
                if (!$material->inventoryItem->hasQuantity($material->quantity_used)) {
                    $errors[] = $material->inventoryItem->name . ' (Need: ' . $material->quantity_used . ' ' . $material->unit . ', Available: ' . $material->inventoryItem->quantity . ' ' . $material->inventoryItem->unit . ')';
                    continue;
                }

                // Deduct from inventory
                $material->inventoryItem->deductQuantity($material->quantity_used);

                // Mark as deducted
                $material->markAsDeducted();

                $deductedCount++;
            }
        });

        // Log inventory deduction in order notes
        $inventoryLog = "\n\n--- Automatic Inventory Deduction ---\n";
        $inventoryLog .= "Triggered by status change to 'In Progress' on " . now()->format('Y-m-d H:i:s') . "\n";

        if ($deductedCount > 0) {
            $inventoryLog .= "Successfully deducted {$deductedCount} materials from inventory\n";
        }

        if (!empty($errors)) {
            $inventoryLog .= "Failed to deduct materials (insufficient stock): " . implode(', ', $errors) . "\n";
        }

        $inventoryLog .= "Performed by: " . Auth::user()->name . " (Admin)";

        $order->update([
            'notes' => ($order->notes ?? '') . $inventoryLog,
        ]);

        // Store flash message for user feedback
        if ($deductedCount > 0 && empty($errors)) {
            session()->flash('inventory_success', "Successfully deducted {$deductedCount} materials from inventory.");
        } elseif ($deductedCount > 0 && !empty($errors)) {
            session()->flash('inventory_warning', "Deducted {$deductedCount} materials. Failed to deduct some materials due to insufficient stock.");
        } elseif (!empty($errors)) {
            session()->flash('inventory_error', "Failed to deduct materials from inventory due to insufficient stock.");
        }
    }

    /**
     * Get material cost analytics for an order
     */
    private function getMaterialCostAnalytics(Order $order)
    {
        $materials = $order->materials;

        return [
            'total_cost' => $materials->sum('total_cost'),
            'deducted_cost' => $materials->where('deducted_at', '!=', null)->sum('total_cost'),
            'pending_cost' => $materials->where('deducted_at', null)->sum('total_cost'),
            'material_count' => $materials->count(),
            'deducted_count' => $materials->where('deducted_at', '!=', null)->count(),
            'pending_count' => $materials->where('deducted_at', null)->count(),
            'cost_by_category' => $materials->groupBy('inventoryItem.category')
                ->map(function ($categoryMaterials) {
                    return [
                        'total_cost' => $categoryMaterials->sum('total_cost'),
                        'count' => $categoryMaterials->count(),
                        'materials' => $categoryMaterials->pluck('inventoryItem.name')->toArray()
                    ];
                }),
            'most_expensive_material' => $materials->sortByDesc('total_cost')->first(),
            'percentage_of_order_value' => $order->total_amount > 0
                ? round(($materials->sum('total_cost') / $order->total_amount) * 100, 2)
                : 0,
        ];
    }
}
