<?php

namespace App\Http\Controllers\appointment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\tbl_appointment;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('appointment.appointment');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:20',
            'reason' => 'required|string|max:500',
            'appointment_datetime' => 'required|date|after:now'
        ]);

        try {
            // Parse the datetime
            $appointmentDateTime = Carbon::parse($request->appointment_datetime);
            
            // Validate clinic hours (9 AM to 4 PM)
            $hour = $appointmentDateTime->hour;
            if ($hour < 9 || $hour >= 16) {
                return back()->withErrors(['appointment_datetime' => 'Please select a time between 9:00 AM and 4:00 PM.'])->withInput();
            }
            
            // Validate weekdays only (Monday to Saturday)
            $dayOfWeek = $appointmentDateTime->dayOfWeek;
            if ($dayOfWeek === 0) { // Sunday = 0
                return back()->withErrors(['appointment_datetime' => 'Please select a weekday (Monday to Saturday).'])->withInput();
            }

            tbl_appointment::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'reason' => $request->reason,
                'appointment_datetime' => $appointmentDateTime,
                'status' => 'pending'
            ]);

            return redirect()->back()->with('success', 'Appointment booked successfully! We will contact you soon to confirm your appointment.');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to book appointment. Please try again. Error: ' . $e->getMessage()]);
        }
    }
}
