<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Show booking page for a specific field
     */
    public function show(Field $field, Request $request)
    {
        // Get all fields for browsing
        $allFields = Field::with('owner')->get();
        
        // Get available times
        $availableTimes = $this->getAvailableTimes();
        
        return view('booking.show', [
            'field' => $field,
            'allFields' => $allFields,
            'availableTimes' => $availableTimes,
        ]);
    }

    /**
     * Get available times (8 AM to 8 PM in 1-hour slots)
     */
    private function getAvailableTimes(): array
    {
        $times = [];
        for ($hour = 8; $hour < 20; $hour++) {
            $start = sprintf('%02d:00', $hour);
            $end = sprintf('%02d:00', $hour + 1);
            $times[] = [
                'start' => $start,
                'end' => $end,
                'display' => $start . ' - ' . $end,
            ];
        }
        return $times;
    }

    /**
     * Store booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'date' => 'required|date|after:today',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
        ]);

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'field_id' => $validated['field_id'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'pending',
        ]);

        return redirect()->route('booking.index')->with('success', 'Booking berhasil dibuat!');
    }

    /**
     * List all bookings
     */
    public function index()
    {
        $bookings = auth()->user()->bookings()->with('field')->get();
        return view('booking.index', ['bookings' => $bookings]);
    }
}
