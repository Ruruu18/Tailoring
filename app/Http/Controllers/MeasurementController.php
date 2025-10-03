<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeasurementController extends Controller
{
    /**
     * Display the customer's measurements.
     */
    public function index()
    {
        $measurement = Measurement::where('user_id', Auth::id())->latest()->first();

        return view('measurements.index', compact('measurement'));
    }

    /**
     * Show the form for creating new measurements.
     */
    public function create()
    {
        return view('measurements.create');
    }

    /**
     * Store newly created measurements in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'size' => 'nullable|string|max:10',
            'is_custom' => 'required|boolean',
            'chest' => 'required|numeric|min:0|max:200',
            'waist' => 'required|numeric|min:0|max:200',
            'hip' => 'required|numeric|min:0|max:200',
            'shoulder' => 'required|numeric|min:0|max:100',
            'sleeve_length' => 'required|numeric|min:0|max:100',
            'shirt_length' => 'nullable|numeric|min:0|max:100',
            'short_waist' => 'nullable|numeric|min:0|max:200',
            'inseam' => 'required|numeric|min:0|max:150',
            'notes' => 'nullable|string|max:1000',
        ]);

        Measurement::create([
            'user_id' => Auth::id(),
            ...$validated,
        ]);

        return redirect()->route('measurements.index')
            ->with('success', 'Measurements saved successfully!');
    }

    /**
     * Show the form for editing measurements.
     */
    public function edit(Measurement $measurement)
    {
        // Ensure the measurement belongs to the authenticated user
        if ($measurement->user_id !== Auth::id()) {
            abort(403);
        }

        return view('measurements.edit', compact('measurement'));
    }

    /**
     * Update the specified measurements in storage.
     */
    public function update(Request $request, Measurement $measurement)
    {
        // Ensure the measurement belongs to the authenticated user
        if ($measurement->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'size' => 'nullable|string|max:10',
            'is_custom' => 'required|boolean',
            'chest' => 'required|numeric|min:0|max:200',
            'waist' => 'required|numeric|min:0|max:200',
            'hip' => 'required|numeric|min:0|max:200',
            'shoulder' => 'required|numeric|min:0|max:100',
            'sleeve_length' => 'required|numeric|min:0|max:100',
            'shirt_length' => 'nullable|numeric|min:0|max:100',
            'short_waist' => 'nullable|numeric|min:0|max:200',
            'inseam' => 'required|numeric|min:0|max:150',
            'notes' => 'nullable|string|max:1000',
        ]);

        $measurement->update($validated);

        return redirect()->route('measurements.index')
            ->with('success', 'Measurements updated successfully!');
    }

    /**
     * Remove the specified measurements from storage.
     */
    public function destroy(Measurement $measurement)
    {
        // Ensure the measurement belongs to the authenticated user
        if ($measurement->user_id !== Auth::id()) {
            abort(403);
        }

        $measurement->delete();

        return redirect()->route('measurements.index')
            ->with('success', 'Measurements deleted successfully!');
    }

    /**
     * Show measurement history for the customer.
     */
    public function history()
    {
        $measurements = Measurement::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('measurements.history', compact('measurements'));
    }
}
