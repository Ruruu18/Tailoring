<?php

namespace App\Http\Controllers;

use App\Models\DesignBrochure;
use App\Models\Measurement;
use App\Models\Order;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::where('user_id', Auth::id());

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%");
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create(Request $request)
    {
        // Get selected design brochure if provided
        $selectedDesign = null;
        if ($request->filled('design_id')) {
            $selectedDesign = DesignBrochure::active()->find($request->design_id);
        }

        // Get all active design brochures for selection
        $designBrochures = DesignBrochure::active()->ordered()->get();

        // Get categories for filtering
        $categories = DesignBrochure::active()->distinct()->pluck('category')->filter()->sort();

        // Get user's latest measurement for default values
        $userMeasurement = Measurement::where('user_id', Auth::id())
            ->latest()
            ->first();

        return view('orders.create', compact('selectedDesign', 'designBrochures', 'categories', 'userMeasurement'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        // DEBUG: Log all incoming request data
        Log::info('=== ORDER CREATION DEBUG START ===');
        Log::info('Request method: ' . $request->method());
        Log::info('Request URL: ' . $request->fullUrl());
        Log::info('All request data:', $request->all());
        Log::info('Files in request:', $request->allFiles());
        Log::info('User ID: ' . Auth::id());

        // Handle items data - prioritize the JSON string from JavaScript if available
        $itemsData = [];
        
        // First check if we have JSON data from the hidden field
        if ($request->filled('items') && is_string($request->input('items'))) {
            Log::info('Found JSON items string: ' . $request->input('items'));
            $jsonItems = json_decode($request->input('items'), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($jsonItems)) {
                $itemsData = $jsonItems;
                Log::info('Successfully parsed JSON items:', $itemsData);
            } else {
                Log::error('JSON decode error: ' . json_last_error_msg());
            }
        }
        
        // If no valid JSON data, fall back to individual form fields
        if (empty($itemsData) && $request->has('items') && is_array($request->input('items'))) {
            $itemsData = $request->input('items');
            Log::info('Using array items from request:', $itemsData);
        }
        
        // If still no data, try to extract from individual fields (fallback)
        if (empty($itemsData)) {
            Log::info('Attempting to extract items from individual fields');
            $itemsData = [];
            $index = 0;
            while ($request->has("items.{$index}.name")) {
                $item = [
                    'name' => $request->input("items.{$index}.name"),
                    'quantity' => (int) $request->input("items.{$index}.quantity", 1),
                    'description' => $request->input("items.{$index}.description", '')
                ];
                if (!empty($item['name'])) {
                    $itemsData[] = $item;
                }
                $index++;
            }
            Log::info('Extracted items from individual fields:', $itemsData);
        }

        Log::info('Final items data before validation:', $itemsData);

        // Replace the items in the request for validation
        $request->merge(['items' => $itemsData]);

        try {
            Log::info('Starting validation...');
            $validated = $request->validate([
                'design_type' => 'required|in:custom,pre_made',
                'design_brochure_id' => 'nullable|exists:design_brochures,id|required_if:design_type,pre_made',
                'design_notes' => 'nullable|string|max:1000',
                'items' => 'required|array|min:1',
                'items.*.name' => 'required|string|max:255',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.description' => 'nullable|string',
                'notes' => 'nullable|string|max:1000',
                'delivery_date' => 'nullable|date|after:today',
                'design_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            Log::info('Validation passed. Validated data:', $validated);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            Log::info('=== ORDER CREATION DEBUG END (VALIDATION FAILED) ===');
            throw $e;
        }

        // Handle design_images
        $imagePaths = [];
        if ($request->hasFile('design_images')) {
            Log::info('Processing ' . count($request->file('design_images')) . ' design images');
            foreach ($request->file('design_images') as $index => $image) {
                try {
                    Log::info("Processing image {$index}: " . $image->getClientOriginalName());
                    $path = $image->store('design_images', 'public');
                    $imagePaths[] = $path;
                    Log::info("Image {$index} stored at: {$path}");
                } catch (\Exception $e) {
                     Log::error("Failed to upload design image {$index}: " . $e->getMessage());
                     Log::error("Image upload stack trace: " . $e->getTraceAsString());
                 }
            }
        } else {
            Log::info('No design images to process');
        }

        Log::info('Image paths after processing:', $imagePaths);

        try {
            Log::info('Creating order with data:', [
                'user_id' => Auth::id(),
                'design_brochure_id' => $validated['design_brochure_id'] ?? null,
                'design_type' => $validated['design_type'],
                'design_notes' => $validated['design_notes'] ?? null,
                'items' => $validated['items'],
                'status' => 'pending',
                'total_amount' => 0,
                'paid_amount' => 0,
                'notes' => $validated['notes'] ?? null,
                'delivery_date' => $validated['delivery_date'] ?? null,
                'design_images' => $imagePaths,
            ]);

            $order = Order::create([
                'user_id' => Auth::id(),
                'design_brochure_id' => $validated['design_brochure_id'] ?? null,
                'design_type' => $validated['design_type'],
                'design_notes' => $validated['design_notes'] ?? null,
                'order_number' => 'ORD-'.strtoupper(Str::random(8)),
                'items' => $validated['items'],
                'status' => 'pending',
                'total_amount' => $this->calculateOrderTotal($validated),
                'paid_amount' => 0,
                'payment_status' => 'pending',
                'notes' => $validated['notes'] ?? null,
                'delivery_date' => $validated['delivery_date'] ?? null,
                'design_images' => $imagePaths,
            ]);

            Log::info('Order created successfully with ID: ' . $order->id);
            Log::info('=== ORDER CREATION DEBUG END (SUCCESS) ===');

            // Send notification for new order
            if ($validated['design_type'] === 'custom') {
                NotificationService::orderQuoteRequested($order);
            } else {
                NotificationService::orderStatusUpdated($order, '', 'pending');
            }

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
             Log::error('Failed to create order: ' . $e->getMessage());
             Log::error('Order creation stack trace: ' . $e->getTraceAsString());
             Log::info('=== ORDER CREATION DEBUG END (CREATION FAILED) ===');
             return back()->withInput()
                 ->with('error', 'Failed to create order. Please try again. Error: ' . $e->getMessage());
         }
    }

    /**
     * Calculate the total amount for an order based on design type and selected design
     */
    private function calculateOrderTotal($validated)
    {
        $total = 0;

        // If pre-made design is selected, use its price multiplied by quantity
        if ($validated['design_type'] === 'pre_made' && isset($validated['design_brochure_id'])) {
            $designBrochure = DesignBrochure::find($validated['design_brochure_id']);
            if ($designBrochure && $designBrochure->price) {
                // Calculate total quantity from all items
                $totalQuantity = 0;
                foreach ($validated['items'] as $item) {
                    $totalQuantity += (int) $item['quantity'];
                }

                // Multiply price by total quantity
                $total = $designBrochure->price * $totalQuantity;

                Log::info('Order total calculation:', [
                    'design_price' => $designBrochure->price,
                    'total_quantity' => $totalQuantity,
                    'calculated_total' => $total
                ]);
            }
        }

        return $total;
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow editing if order is still pending
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Only pending orders can be edited.');
        }

        // Get all active design brochures for selection
        $designBrochures = DesignBrochure::active()->ordered()->get();

        // Get categories for filtering
        $categories = DesignBrochure::active()->distinct()->pluck('category')->filter()->sort();

        return view('orders.edit', compact('order', 'designBrochures', 'categories'));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow updating if order is still pending
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Only pending orders can be updated.');
        }

        // First decode the JSON items string
        $itemsData = $request->input('items');
        if (is_string($itemsData)) {
            $itemsData = json_decode($itemsData, true);
        }

        // Replace the items in the request for validation
        $request->merge(['items' => $itemsData]);

        $validated = $request->validate([
            'design_brochure_id' => 'nullable|exists:design_brochures,id',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.description' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
            'delivery_date' => 'nullable|date|after:today',
            'design_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle design_images
        $existingImages = $order->design_images ?? [];
        $newImagePaths = $existingImages;

        // Handle deleted images (kept for backward compatibility with form submission)
        if ($request->has('deleted_images') && ! empty($request->input('deleted_images'))) {
            $deletedImages = array_filter($request->input('deleted_images')); // Remove empty values
            foreach ($deletedImages as $deletedImage) {
                // Remove from storage
                if (Storage::disk('public')->exists($deletedImage)) {
                    Storage::disk('public')->delete($deletedImage);
                }
                // Remove from array
                $newImagePaths = array_filter($newImagePaths, function ($image) use ($deletedImage) {
                    return $image !== $deletedImage;
                });
            }
            // Re-index the array
            $newImagePaths = array_values($newImagePaths);
        }

        // Add new images
        if ($request->hasFile('design_images')) {
            foreach ($request->file('design_images') as $image) {
                $path = $image->store('design_images', 'public');
                $newImagePaths[] = $path;
            }
        }

        $order->update([
            'design_brochure_id' => $validated['design_brochure_id'] ?? null,
            'items' => $validated['items'],
            'notes' => $validated['notes'] ?? null,
            'delivery_date' => $validated['delivery_date'] ?? null,
            'design_images' => $newImagePaths,
        ]);

        // Update total amount if design brochure changed or items changed
        if (isset($validated['design_brochure_id']) && $order->design_type === 'pre_made') {
            $designBrochure = DesignBrochure::find($validated['design_brochure_id']);
            if ($designBrochure && $designBrochure->price) {
                // Calculate total quantity from all items
                $totalQuantity = 0;
                foreach ($validated['items'] as $item) {
                    $totalQuantity += (int) $item['quantity'];
                }

                // Multiply price by total quantity
                $newTotal = $designBrochure->price * $totalQuantity;
                $order->update(['total_amount' => $newTotal]);
            }
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow deletion if order is still pending
        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')
                ->with('error', 'Only pending orders can be deleted.');
        }

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully!');
    }

    /**
     * Track order status
     */
    public function track(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.track', compact('order'));
    }

    /**
     * Delete a specific image from an order
     */
    public function deleteImage(Request $request, Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Only allow deleting if order is still pending
        if ($order->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending orders can be modified'], 400);
        }

        $imagePath = $request->input('image_path');

        if (! $imagePath) {
            return response()->json(['success' => false, 'message' => 'Image path is required'], 400);
        }

        $currentImages = $order->design_images ?? [];

        // Check if image exists in order
        if (! in_array($imagePath, $currentImages)) {
            return response()->json(['success' => false, 'message' => 'Image not found in order'], 404);
        }

        // Remove from storage
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        // Remove from order
        $newImages = array_filter($currentImages, function ($image) use ($imagePath) {
            return $image !== $imagePath;
        });

        $order->update([
            'design_images' => array_values($newImages),
        ]);

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }

    /**
     * Process downpayment for an order
     */
    public function downpayment(Request $request, Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'downpayment_amount' => 'required|numeric|min:1|max:' . $order->total_amount,
        ]);

        // Update the order with downpayment
        $order->update([
            'paid_amount' => $request->downpayment_amount,
            'payment_status' => 'partial',
        ]);

        // Send payment received notification
        NotificationService::paymentReceived($order, $request->downpayment_amount);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Downpayment of ₱' . number_format($request->downpayment_amount, 2) . ' has been recorded successfully!');
    }

    /**
     * Get order status for real-time updates (API)
     */
    public function getOrderStatus(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json([
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'total_amount' => $order->total_amount,
            'paid_amount' => $order->paid_amount,
            'delivery_date' => $order->delivery_date ? $order->delivery_date->format('Y-m-d') : null,
            'updated_at' => $order->updated_at->toISOString(),
            'balance' => $order->total_amount - $order->paid_amount,
            'payment_available' => $order->total_amount > 0 && $order->status !== 'pending',
            'can_make_downpayment' => $order->total_amount > 0 && $order->payment_status === 'pending' && $order->paid_amount == 0 && $order->status !== 'pending'
        ]);
    }
}
