<?php

namespace App\Http\Controllers;

use App\Models\DesignBrochure;
use Illuminate\Http\Request;

class DesignBrochureController extends Controller
{
    /**
     * Display a listing of active design brochures for customers.
     */
    public function index(Request $request)
    {
        $query = DesignBrochure::active();

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
            $query->byCategory($request->category);
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->byGender($request->gender);
        }

        // Filter by style type
        if ($request->filled('style_type')) {
            $query->where('style_type', $request->style_type);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort options
        $sortBy = $request->get('sort', 'sort_order');
        $sortDirection = $request->get('direction', 'asc');

        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->featured()->ordered();
                break;
            default:
                $query->ordered();
        }

        $designBrochures = $query->paginate(12)->appends($request->query());

        // Get filter options
        $categories = DesignBrochure::active()->distinct()->pluck('category')->filter()->sort();
        $genders = DesignBrochure::active()->distinct()->pluck('gender')->filter()->sort();
        $styleTypes = DesignBrochure::active()->distinct()->pluck('style_type')->filter()->sort();
        $priceRange = [
            'min' => DesignBrochure::active()->min('price') ?? 0,
            'max' => DesignBrochure::active()->max('price') ?? 1000
        ];

        // Get featured designs for hero section
        $featuredDesigns = DesignBrochure::active()->featured()->ordered()->take(6)->get();

        return view('design-brochures.index', compact(
            'designBrochures',
            'categories',
            'genders',
            'styleTypes',
            'priceRange',
            'featuredDesigns'
        ));
    }

    /**
     * Display the specified design brochure.
     */
    public function show(DesignBrochure $designBrochure)
    {
        // Ensure the design brochure is active
        if (!$designBrochure->is_active) {
            abort(404);
        }

        // Get related designs
        $relatedDesigns = DesignBrochure::active()
            ->where('id', '!=', $designBrochure->id)
            ->where(function($query) use ($designBrochure) {
                $query->where('category', $designBrochure->category)
                      ->orWhere('gender', $designBrochure->gender)
                      ->orWhere('style_type', $designBrochure->style_type);
            })
            ->ordered()
            ->take(6)
            ->get();

        return view('design-brochures.show', compact('designBrochure', 'relatedDesigns'));
    }

    /**
     * API endpoint to get design brochure data for AJAX requests
     */
    public function api(Request $request)
    {
        $query = DesignBrochure::active();

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('gender')) {
            $query->byGender($request->gender);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $designs = $query->ordered()->get()->map(function($design) {
            return [
                'id' => $design->id,
                'title' => $design->title,
                'description' => $design->description,
                'category' => $design->category,
                'gender' => $design->gender,
                'price' => $design->price,
                'featured_image_url' => $design->featured_image_url,
                'tags_array' => $design->tags_array,
            ];
        });

        return response()->json($designs);
    }
}