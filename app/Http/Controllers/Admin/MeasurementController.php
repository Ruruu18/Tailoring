<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\User;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    /**
     * Display a listing of all measurements.
     */
    public function index()
    {
        $measurements = Measurement::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.measurements.index', compact('measurements'));
    }

    /**
     * Show the form for creating a new measurement.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.measurements.create', compact('users'));
    }

    /**
     * Store a newly created measurement in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
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

        Measurement::create($validated);

        return redirect()->route('admin.measurements.index')
            ->with('success', 'Measurement created successfully!');
    }

    /**
     * Display the specified measurement.
     */
    public function show(Measurement $measurement)
    {
        $measurement->load('user');
        return view('admin.measurements.show', compact('measurement'));
    }

    /**
     * Show the form for editing the specified measurement.
     */
    public function edit(Measurement $measurement)
    {
        $users = User::orderBy('name')->get();
        $measurement->load('user');
        return view('admin.measurements.edit', compact('measurement', 'users'));
    }

    /**
     * Update the specified measurement in storage.
     */
    public function update(Request $request, Measurement $measurement)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
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

        return redirect()->route('admin.measurements.index')
            ->with('success', 'Measurement updated successfully!');
    }

    /**
     * Remove the specified measurement from storage.
     */
    public function destroy(Measurement $measurement)
    {
        $measurement->delete();

        return redirect()->route('admin.measurements.index')
            ->with('success', 'Measurement deleted successfully!');
    }
}
