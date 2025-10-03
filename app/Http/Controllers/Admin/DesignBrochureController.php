<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesignBrochure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignBrochureController extends Controller
{
    /**
     * Display a listing of the design brochures.
     */
    public function index(Request $request)
    {
        $query = DesignBrochure::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $designBrochures = $query->ordered()->paginate(12)->appends($request->query());

        // Get unique categories and genders for filters
        $categories = DesignBrochure::distinct()->pluck('category')->filter()->sort();
        $genders = DesignBrochure::distinct()->pluck('gender')->filter()->sort();

        return view('admin.design-brochures.index', compact('designBrochures', 'categories', 'genders'));
    }

    /**
     * Show the form for creating a new design brochure.
     */
    public function create()
    {
        return view('admin.design-brochures.create');
    }

    /**
     * Store a newly created design brochure.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|max:255',
            'style_type' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:men,women,unisex',
            'tags' => 'nullable|string|max:500',
            'fabric_suggestions' => 'nullable|string|max:1000',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('design_brochures', 'public');
                $imagePaths[] = $path;
            }
        }


        // Get next sort order if not provided
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = DesignBrochure::max('sort_order') + 1;
        }

        DesignBrochure::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'style_type' => $validated['style_type'],
            'gender' => $validated['gender'],
            'tags' => $validated['tags'],
            'fabric_suggestions' => $validated['fabric_suggestions'],
            'price' => $validated['price'],
            'is_active' => $validated['is_active'] ?? true,
            'is_featured' => $validated['is_featured'] ?? false,
            'sort_order' => $validated['sort_order'],
            'images' => $imagePaths,
        ]);

        return redirect()->route('admin.design-brochures.index')
            ->with('success', 'Design brochure created successfully!');
    }

    /**
     * Display the specified design brochure.
     */
    public function show(DesignBrochure $designBrochure)
    {
        return view('admin.design-brochures.show', compact('designBrochure'));
    }

    /**
     * Show the form for editing the specified design brochure.
     */
    public function edit(DesignBrochure $designBrochure)
    {
        return view('admin.design-brochures.edit', compact('designBrochure'));
    }

    /**
     * Update the specified design brochure.
     */
    public function update(Request $request, DesignBrochure $designBrochure)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|max:255',
            'style_type' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:men,women,unisex',
            'tags' => 'nullable|string|max:500',
            'fabric_suggestions' => 'nullable|string|max:1000',
            'price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'remove_images' => 'nullable|string',
        ]);

        // Handle existing images removal
        $currentImages = $designBrochure->images ?? [];
        if ($request->filled('remove_images') && !empty($request->remove_images)) {
            $imagesToDelete = json_decode($request->remove_images, true);

            if (is_array($imagesToDelete)) {
                foreach ($imagesToDelete as $imageToRemove) {
                    if (!empty($imageToRemove) && in_array($imageToRemove, $currentImages)) {
                        // Delete from storage
                        Storage::disk('public')->delete($imageToRemove);
                        // Remove from array
                        $currentImages = array_diff($currentImages, [$imageToRemove]);
                    }
                }
                $currentImages = array_values($currentImages);
            }
        }

        // Handle new image uploads
        $newImagePaths = $currentImages;
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('design_brochures', 'public');
                $newImagePaths[] = $path;
            }
        }

        // Ensure array is properly formatted (empty array if no images)
        $newImagePaths = !empty($newImagePaths) ? array_values($newImagePaths) : [];


        $designBrochure->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'style_type' => $validated['style_type'],
            'gender' => $validated['gender'],
            'tags' => $validated['tags'],
            'fabric_suggestions' => $validated['fabric_suggestions'],
            'price' => $validated['price'],
            'is_active' => $validated['is_active'] ?? $designBrochure->is_active,
            'is_featured' => $validated['is_featured'] ?? $designBrochure->is_featured,
            'sort_order' => $validated['sort_order'] ?? $designBrochure->sort_order,
            'images' => $newImagePaths,
        ]);

        return redirect()->route('admin.design-brochures.index')
            ->with('success', 'Design brochure updated successfully!');
    }

    /**
     * Remove the specified design brochure from storage.
     */
    public function destroy(DesignBrochure $designBrochure)
    {
        // Check if design brochure is used in any orders
        if ($designBrochure->orders()->count() > 0) {
            return redirect()->route('admin.design-brochures.index')
                ->with('error', 'Cannot delete design brochure that is used in orders.');
        }

        // Delete associated images
        if ($designBrochure->images) {
            foreach ($designBrochure->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        if ($designBrochure->featured_image) {
            Storage::disk('public')->delete($designBrochure->featured_image);
        }

        $designBrochure->delete();

        return redirect()->route('admin.design-brochures.index')
            ->with('success', 'Design brochure deleted successfully!');
    }

    /**
     * Delete a specific image from design brochure.
     */
    public function deleteImage(Request $request, DesignBrochure $designBrochure)
    {
        $imagePath = $request->input('image_path');

        if (!$imagePath) {
            return response()->json(['success' => false, 'message' => 'Image path is required'], 400);
        }

        $currentImages = $designBrochure->images ?? [];

        // Check if image exists in design brochure
        if (!in_array($imagePath, $currentImages)) {
            return response()->json(['success' => false, 'message' => 'Image not found in design brochure'], 404);
        }

        // Remove from storage
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        // Remove from design brochure
        $newImages = array_filter($currentImages, function ($image) use ($imagePath) {
            return $image !== $imagePath;
        });

        $designBrochure->update([
            'images' => array_values($newImages),
        ]);

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }

    /**
     * Bulk update sort order
     */
    public function updateSortOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:design_brochures,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            DesignBrochure::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(DesignBrochure $designBrochure)
    {
        $designBrochure->update(['is_featured' => !$designBrochure->is_featured]);

        return response()->json([
            'success' => true,
            'is_featured' => $designBrochure->is_featured
        ]);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(DesignBrochure $designBrochure)
    {
        $designBrochure->update(['is_active' => !$designBrochure->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $designBrochure->is_active
        ]);
    }
}
