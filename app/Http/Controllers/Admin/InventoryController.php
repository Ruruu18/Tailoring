<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    /**
     * Display a listing of inventory items.
     */
    public function index(Request $request)
    {
        $query = InventoryItem::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('supplier', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filter by design type
        if ($request->has('design_type') && $request->design_type) {
            $query->designType($request->design_type);
        }

        // Filter by stock status
        if ($request->has('stock_status') && $request->stock_status) {
            switch ($request->stock_status) {
                case 'low_stock':
                    $query->whereRaw('quantity <= min_quantity');
                    break;
                case 'out_of_stock':
                    $query->where('quantity', '<=', 0);
                    break;
                case 'in_stock':
                    $query->whereRaw('quantity > min_quantity');
                    break;
            }
        }

        // Determine view type (gallery or table)
        $viewType = $request->get('view', 'gallery'); // default to gallery

        // Order by sort_order for gallery, by name for table
        if ($viewType === 'gallery') {
            $items = $query->orderBy('featured', 'desc')
                          ->orderBy('sort_order')
                          ->orderBy('name')
                          ->paginate(12);
        } else {
            $items = $query->orderBy('name')->paginate(15);
        }

        $categories = InventoryItem::distinct()->pluck('category')->filter();
        $featuredItems = InventoryItem::featured()->take(6)->get();

        return view('admin.inventory.index', compact('items', 'categories', 'featuredItems', 'viewType'));
    }

    /**
     * Show the form for creating a new inventory item.
     */
    public function create()
    {
        $categories = InventoryItem::distinct()->pluck('category')->filter();

        return view('admin.inventory.create', compact('categories'));
    }

    /**
     * Store a newly created inventory item in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:inventory_items',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'supplier' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = $request->all();

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('inventory', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = $imagePaths;
            $data['primary_image'] = $imagePaths[0] ?? null;
        }

        InventoryItem::create($data);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Display the specified inventory item.
     */
    public function show(InventoryItem $inventory)
    {
        return view('admin.inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified inventory item.
     */
    public function edit(InventoryItem $inventory)
    {
        $categories = InventoryItem::distinct()->pluck('category')->filter();

        return view('admin.inventory.edit', compact('inventory', 'categories'));
    }

    /**
     * Update the specified inventory item in storage.
     */
    public function update(Request $request, InventoryItem $inventory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'sku' => ['required', 'string', 'max:255', Rule::unique('inventory_items')->ignore($inventory->id)],
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'supplier' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'remove_images.*' => 'nullable|integer',
        ]);

        $data = $request->except(['images', 'remove_images']);

        // Handle existing images
        $currentImages = $inventory->images ?? [];

        // Remove selected images
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $index) {
                if (isset($currentImages[$index])) {
                    // Delete file from storage
                    \Storage::disk('public')->delete($currentImages[$index]);
                    unset($currentImages[$index]);
                }
            }
            $currentImages = array_values($currentImages); // Re-index array
        }

        // Also clean up any images that no longer exist in storage
        $currentImages = array_filter($currentImages, function($image) {
            return is_string($image) && \Storage::disk('public')->exists($image);
        });

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('inventory', 'public');
                $currentImages[] = $path;
            }
        }

        // Filter out empty values and re-index
        $currentImages = array_values(array_filter($currentImages, function($image) {
            return !empty($image) && is_string($image);
        }));

        $data['images'] = $currentImages;
        $data['primary_image'] = !empty($currentImages) ? $currentImages[0] : null;

        $inventory->update($data);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified inventory item from storage.
     */
    public function destroy(InventoryItem $inventory)
    {
        // Delete associated images from storage
        if ($inventory->images) {
            foreach ($inventory->images as $image) {
                \Storage::disk('public')->delete($image);
            }
        }

        $inventory->delete();

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Show low stock items
     */
    public function lowStock()
    {
        $items = InventoryItem::whereRaw('quantity <= min_quantity')
            ->where('is_active', true)
            ->orderBy('quantity', 'asc')
            ->paginate(15);

        return view('admin.inventory.low-stock', compact('items'));
    }

    /**
     * Update stock quantity
     */
    public function updateStock(Request $request, InventoryItem $inventory)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'action' => 'required|in:add,subtract,set',
        ]);

        switch ($request->action) {
            case 'add':
                $inventory->quantity += $request->quantity;
                break;
            case 'subtract':
                $inventory->quantity = max(0, $inventory->quantity - $request->quantity);
                break;
            case 'set':
                $inventory->quantity = $request->quantity;
                break;
        }

        $inventory->save();

        return redirect()->back()->with('success', 'Stock quantity updated successfully.');
    }

    /**
     * Delete a specific image from inventory item
     */
    public function deleteImage(Request $request, InventoryItem $inventory)
    {
        $request->validate([
            'image_index' => 'required|integer|min:0',
        ]);

        $images = $inventory->images ?? [];
        $imageIndex = $request->image_index;

        if (isset($images[$imageIndex])) {
            // Delete file from storage
            \Storage::disk('public')->delete($images[$imageIndex]);

            // Remove from array
            unset($images[$imageIndex]);
            $images = array_values($images); // Re-index array

            // Update images and primary_image
            $inventory->images = $images;
            $inventory->primary_image = !empty($images) ? $images[0] : null;
            $inventory->save();

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Image not found'
        ], 404);
    }
}
