<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of all appointments.
     */
    public function index()
    {
        $appointments = Appointment::with(['user', 'order'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(15);

        // Get today's date in Philippines timezone (Asia/Manila)
        $todayInPhilippines = Carbon::now('Asia/Manila')->format('Y-m-d');

        $todayAppointments = Appointment::with(['user', 'order'])
            ->whereDate('appointment_date', $todayInPhilippines)
            ->whereIn('status', ['scheduled', 'confirmed', 'completed'])
            ->orderBy('appointment_date')
            ->get();

        return view('admin.appointments.index', compact('appointments', 'todayAppointments'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        $availableSlots = $this->getAvailableTimeSlots();

        return view('admin.appointments.create', compact('users', 'orders', 'availableSlots'));
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'order_id' => 'nullable|exists:orders,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'service_type' => 'required|in:consultation,fitting,measurement,pickup,delivery',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled,no_show',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Combine date and time
        $appointmentDateTime = Carbon::parse($validated['appointment_date'].' '.$validated['appointment_time']);

        // Check if the time slot is available (admin can override)
        if (!$this->isTimeSlotAvailable($appointmentDateTime)) {
            return back()->withErrors(['appointment_time' => 'This time slot is already booked.'])->withInput();
        }

        Appointment::create([
            'user_id' => $validated['user_id'],
            'order_id' => $validated['order_id'],
            'appointment_date' => $appointmentDateTime,
            'service_type' => $validated['service_type'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment created successfully!');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['user', 'order']);
        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        $users = User::orderBy('name')->get();
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        $availableSlots = $this->getAvailableTimeSlots();
        $appointment->load(['user', 'order']);

        return view('admin.appointments.edit', compact('appointment', 'users', 'orders', 'availableSlots'));
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'order_id' => 'nullable|exists:orders,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'service_type' => 'required|in:consultation,fitting,measurement,pickup,delivery',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled,no_show',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Combine date and time
        $appointmentDateTime = Carbon::parse($validated['appointment_date'].' '.$validated['appointment_time']);

        // Check if the time slot is available (excluding current appointment)
        if (!$this->isTimeSlotAvailable($appointmentDateTime, $appointment->id)) {
            return back()->withErrors(['appointment_time' => 'This time slot is already booked.'])->withInput();
        }

        $appointment->update([
            'user_id' => $validated['user_id'],
            'order_id' => $validated['order_id'],
            'appointment_date' => $appointmentDateTime,
            'service_type' => $validated['service_type'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment updated successfully!');
    }

    /**
     * Remove the specified appointment from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment deleted successfully!');
    }

    /**
     * Get available time slots for appointments.
     */
    private function getAvailableTimeSlots()
    {
        $slots = [];
        $startHour = 9; // 9 AM
        $endHour = 17; // 5 PM

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            $slots[] = sprintf('%02d:00', $hour);
            $slots[] = sprintf('%02d:30', $hour);
        }

        return $slots;
    }

    /**
     * Check if a time slot is available.
     */
    private function isTimeSlotAvailable(Carbon $dateTime, $excludeAppointmentId = null)
    {
        // Check if there's already an appointment at this time
        $query = Appointment::where('appointment_date', $dateTime)
            ->where('status', '!=', 'cancelled');

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return !$query->exists();
    }
}
