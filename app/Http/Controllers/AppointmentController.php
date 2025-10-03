<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the customer's appointments.
     */
    public function index()
    {
        $appointments = Appointment::where('user_id', Auth::id())
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        $upcomingAppointments = Appointment::where('user_id', Auth::id())
            ->where('appointment_date', '>', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_date')
            ->get();

        return view('appointments.index', compact('appointments', 'upcomingAppointments'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        // Get available time slots (9 AM to 5 PM, excluding weekends)
        $availableSlots = $this->getAvailableTimeSlots();

        return view('appointments.create', compact('availableSlots'));
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'service_type' => 'required|in:consultation,fitting,measurement,pickup,delivery',
            'notes' => 'nullable|string|max:1000',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        // Combine date and time
        $appointmentDateTime = Carbon::parse($validated['appointment_date'].' '.$validated['appointment_time']);

        // Check if the time slot is available
        if (! $this->isTimeSlotAvailable($appointmentDateTime)) {
            return back()->withErrors(['appointment_time' => 'This time slot is not available.'])->withInput();
        }

        // Verify order belongs to user if provided
        if ($validated['order_id']) {
            $order = \App\Models\Order::find($validated['order_id']);
            if (! $order || $order->user_id !== Auth::id()) {
                return back()->withErrors(['order_id' => 'Invalid order selected.'])->withInput();
            }
        }

        $appointment = Appointment::create([
            'user_id' => Auth::id(),
            'order_id' => $validated['order_id'] ?? null,
            'appointment_date' => $appointmentDateTime,
            'service_type' => $validated['service_type'],
            'status' => 'scheduled',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Send appointment confirmation notification
        NotificationService::appointmentCreated($appointment);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment scheduled successfully!');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        // Ensure the appointment belongs to the authenticated user
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        // Ensure the appointment belongs to the authenticated user
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow editing if appointment is scheduled and in the future
        if ($appointment->status !== 'scheduled' || $appointment->appointment_date <= now()) {
            return redirect()->route('appointments.show', $appointment)
                ->with('error', 'Only future scheduled appointments can be edited.');
        }

        $availableSlots = $this->getAvailableTimeSlots();

        return view('appointments.edit', compact('appointment', 'availableSlots'));
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Ensure the appointment belongs to the authenticated user
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow updating if appointment is scheduled and in the future
        if ($appointment->status !== 'scheduled' || $appointment->appointment_date <= now()) {
            return redirect()->route('appointments.show', $appointment)
                ->with('error', 'Only future scheduled appointments can be updated.');
        }

        $validated = $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'service_type' => 'required|in:consultation,fitting,measurement,pickup,delivery',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Combine date and time
        $appointmentDateTime = Carbon::parse($validated['appointment_date'].' '.$validated['appointment_time']);

        // Check if the time slot is available (excluding current appointment)
        if (! $this->isTimeSlotAvailable($appointmentDateTime, $appointment->id)) {
            return back()->withErrors(['appointment_time' => 'This time slot is not available.'])->withInput();
        }

        $appointment->update([
            'appointment_date' => $appointmentDateTime,
            'service_type' => $validated['service_type'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully!');
    }

    /**
     * Cancel the specified appointment.
     */
    public function cancel(Appointment $appointment)
    {
        // Ensure the appointment belongs to the authenticated user
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow cancellation if appointment is scheduled and in the future
        if ($appointment->status !== 'scheduled' || $appointment->appointment_date <= now()) {
            return redirect()->route('appointments.show', $appointment)
                ->with('error', 'Only future scheduled appointments can be cancelled.');
        }

        $appointment->update(['status' => 'cancelled']);

        // Send appointment cancellation notification
        NotificationService::appointmentCancelled($appointment);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully!');
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
        // Check if it's a weekend
        if ($dateTime->isWeekend()) {
            return false;
        }

        // Check if it's within business hours (9 AM to 5 PM)
        if ($dateTime->hour < 9 || $dateTime->hour >= 17) {
            return false;
        }

        // Check if there's already an appointment at this time
        $query = Appointment::where('appointment_date', $dateTime)
            ->where('status', '!=', 'cancelled');

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return ! $query->exists();
    }
}
