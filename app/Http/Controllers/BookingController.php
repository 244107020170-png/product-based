<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Field;
use App\Models\Booking;
use App\Models\Slot;
use App\Models\Matchs;
use App\Notifications\Owner\OwnerNewBooking;
use App\Notifications\Owner\OwnerPaymentReceived;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    private const VALID_TRANSITIONS = [
        'pending' => ['waiting_payment', 'waiting_confirmation', 'paid', 'cancelled'],
        'waiting_payment' => ['pending', 'waiting_confirmation', 'paid', 'cancelled', 'expired'],
        'waiting_confirmation' => ['pending', 'waiting_payment', 'paid', 'confirmed', 'rejected', 'cancelled'],
        'paid' => ['pending', 'waiting_payment', 'waiting_confirmation', 'confirmed'],
        'confirmed' => ['paid', 'waiting_confirmation', 'completed', 'cancelled'],
        'completed' => [],
        'cancelled' => [],
        'rejected' => [],
        'expired' => [],
    ];

    public static function isValidTransition(?string $from, string $to): bool
    {
        if (!$from) return true;
        $allowed = self::VALID_TRANSITIONS[$from] ?? [];
        return in_array($to, $allowed, true);
    }

    public function __construct(protected BookingService $bookingService)
    {
    }

    public function assignCourtAndConfirm(Booking $booking, bool $setPaidAt = true): void
    {
        $field = $booking->field;
        $numCourts = $field->number_of_courts ?? 1;
        $date = $booking->date instanceof Carbon ? $booking->date->toDateString() : $booking->date;
        $startHour = (int) Carbon::parse($booking->start_time)->format('G');
        $endHour = (int) Carbon::parse($booking->end_time)->format('G');

        $assignedCourt = null;

        for ($court = 1; $court <= $numCourts; $court++) {
            $allAvailable = true;
            for ($h = $startHour; $h < $endHour; $h++) {
                $existing = Slot::where('field_id', $field->id)
                    ->where('court_number', $court)
                    ->where('date', $date)
                    ->where('hour', $h)
                    ->first();

                if ($existing && $existing->status !== 'tersedia') {
                    $allAvailable = false;
                    break;
                }
            }

            if ($allAvailable) {
                $assignedCourt = $court;
                break;
            }
        }

        if (!$assignedCourt) {
            for ($court = 1; $court <= $numCourts; $court++) {
                $canAssign = true;
                for ($h = $startHour; $h < $endHour; $h++) {
                    $existing = Slot::where('field_id', $field->id)
                        ->where('court_number', $court)
                        ->where('date', $date)
                        ->where('hour', $h)
                        ->first();

                    if ($existing && in_array($existing->status, ['dibooking', 'tutup', 'perbaikan'])) {
                        $canAssign = false;
                        break;
                    }
                }

                if ($canAssign) {
                    $assignedCourt = $court;
                    break;
                }
            }
        }

        if (!$assignedCourt) {
            $assignedCourt = 1;
        }

        for ($h = $startHour; $h < $endHour; $h++) {
            Slot::updateOrCreate(
                [
                    'field_id'     => $field->id,
                    'court_number' => $assignedCourt,
                    'date'         => $date,
                    'hour'         => $h,
                ],
                ['status' => 'dibooking']
            );
        }

        $data = [
            'status' => BookingStatus::CONFIRMED,
            'court_number' => $assignedCourt,
            'confirmed_at' => now(),
        ];
        if ($setPaidAt) {
            $data['paid_at'] = now();
        }
        $booking->update($data);
    }
    /**
     * Show booking page for a specific field
     */
    public function show(Field $field, Request $request)
    {
        $allFields = Field::with('owner')->get();
        $date = $request->input('date', today()->toDateString());
        $availableTimes = $this->getAvailableTimes($field, $date);
        $privateSport = $request->input('sport');

        $slots = Slot::where('field_id', $field->id)
            ->where('date', Carbon::parse($date)->toDateString())
            ->get(['court_number', 'hour', 'status']);

        return view('booking.show', [
            'field' => $field,
            'allFields' => $allFields,
            'availableTimes' => $availableTimes,
            'privateSport' => $privateSport,
            'slots' => $slots,
        ]);
    }

    /**
     * Get available times (8 AM to 8 PM in 1-hour slots)
     */
    private function getAvailableTimes(Field $field, string $date): array
    {
        return $this->bookingService->getAvailableSlots($field, Carbon::parse($date));
    }

    /**
     * Store booking
     */
    public function store(Request $request)
    {
        Log::debug('Booking store payload', [
            'raw' => $request->all(),
            'server_now' => Carbon::now()->toDateTimeString(),
            'server_tz' => config('app.timezone'),
        ]);

        $validator = Validator::make($request->all(), [
            'field_id' => 'required|exists:fields,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
        ]);

        if ($validator->fails()) {
            if ($this->isJsonRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi booking gagal.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            throw new ValidationException($validator);
        }

        try {
            $booking = $this->bookingService->createBooking($validator->validated(), auth()->user());

            Booking::where('user_id', auth()->id())
                ->where(function ($q) {
                    $q->where('status', BookingStatus::EXPIRED)
                      ->orWhere(function ($q2) {
                          $q2->where('status', BookingStatus::WAITING_PAYMENT)
                             ->where('payment_deadline', '<', now());
                      });
                })->delete();

            $pendingMatch = session('pending_match');
            if ($pendingMatch && $pendingMatch['type'] === 'public') {
                session()->forget('pending_match');
                $exists = Matchs::where('title', $pendingMatch['title'])->where('type', 'public')->exists();
                if (!$exists) {
                    $match = Matchs::create($pendingMatch);
                }
            } elseif ($pendingMatch) {
                session()->forget('pending_match');
                $match = Matchs::create($pendingMatch);
            }

            // Private match creation from sport filter
            $privateSport = $request->input('sport');
            if ($privateSport && !$pendingMatch) {
                $field = Field::find($request->input('field_id'));
                if ($field) {
                    Matchs::create([
                        'title' => 'Pertandingan ' . $privateSport . ' Pribadi',
                        'sport' => $privateSport,
                        'field_id' => $field->id,
                        'date' => $request->input('date'),
                        'time' => $request->input('start_time'),
                        'max_player' => 10,
                        'created_by' => auth()->id(),
                        'type' => 'private',
                    ]);
                }
            }

            try {
                $owner = $booking->field->owner;
                if ($owner && $owner->id !== auth()->id()) {
                    $owner->notify(new OwnerNewBooking($booking));
                }
            } catch (\Throwable $e) {
                Log::warning('Gagal notifikasi owner: ' . $e->getMessage());
            }

            if ($this->isJsonRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Booking berhasil dibuat!',
                    'booking' => $booking,
                ], 201);
            }

            return redirect()->route('booking.detail', $booking->id)->with('success', 'Booking berhasil dibuat!');
        } catch (ValidationException $exception) {
            if ($this->isJsonRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage() ?: 'Validasi booking gagal.',
                    'errors' => $exception->errors(),
                ], 422);
            }
            throw $exception;
        } catch (\Throwable $exception) {
            if ($this->isJsonRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage() ?: 'Terjadi kesalahan saat membuat booking.',
                ], 500);
            }
            throw $exception;
        }
    }

    protected function isJsonRequest(Request $request): bool
    {
        return $request->expectsJson()
            || $request->ajax()
            || str_contains((string) $request->header('accept'), 'application/json')
            || str_contains((string) $request->header('content-type'), 'application/json');
    }

    /**
     * List all bookings
     */
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with('field')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('booking.index', ['bookings' => $bookings]);
    }

    /**
     * Show booking details
     */
    public function detail(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load('field');

        // Auto-confirm legacy waiting_confirmation bookings
        if ($booking->status === BookingStatus::WAITING_CONFIRMATION) {
            $this->assignCourtAndConfirm($booking, false);
            $booking->refresh();
        }

        return view('booking.detail', compact('booking'));
    }

    /**
     * Auto-process payment when QR code is scanned / clicked
     */
    public function paymentPage(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load('field', 'user');

        if ($booking->status !== BookingStatus::WAITING_PAYMENT) {
            return redirect()->route('booking.detail', $booking->id);
        }

        if ($booking->payment_deadline && now()->greaterThan($booking->payment_deadline)) {
            $booking->update(['status' => BookingStatus::EXPIRED]);
            return redirect()->route('booking.detail', $booking->id)->with('error', 'Waktu pembayaran telah kadaluarsa.');
        }

        $this->assignCourtAndConfirm($booking);

        $booking->user->notify(new \App\Notifications\BookingPaymentReceived($booking));

        try {
            $owner = $booking->field->owner;
            if ($owner) {
                $owner->notify(new OwnerPaymentReceived($booking));
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal notifikasi owner: ' . $e->getMessage());
        }

        return redirect()->route('booking.detail', $booking->id)->with('success', 'Pembayaran berhasil! Booking otomatis dikonfirmasi.');
    }

    public function pay(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== BookingStatus::WAITING_PAYMENT) {
            return back()->with('error', 'Booking ini tidak tersedia untuk pembayaran.');
        }

        if ($booking->payment_deadline && now()->greaterThan($booking->payment_deadline)) {
            $booking->update(['status' => BookingStatus::EXPIRED]);
            return back()->with('error', 'Waktu pembayaran telah kadaluarsa.');
        }

        $this->assignCourtAndConfirm($booking);

        try {
            $owner = $booking->field->owner;
            if ($owner) {
                $owner->notify(new OwnerPaymentReceived($booking));
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal notifikasi owner: ' . $e->getMessage());
        }

        return back()->with('success', 'Pembayaran berhasil! Booking otomatis dikonfirmasi.');
    }

    public function confirmPayment(Booking $booking)
    {
        if ($booking->field->owner_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== BookingStatus::WAITING_CONFIRMATION) {
            return back()->with('error', 'Booking tidak dalam status menunggu konfirmasi.');
        }

        // Auto-assign court and mark slots as dibooking
        $field = $booking->field;
        $numCourts = $field->number_of_courts ?? 1;
        $date = $booking->date instanceof Carbon ? $booking->date->toDateString() : $booking->date;
        $startHour = (int) Carbon::parse($booking->start_time)->format('G');
        $endHour = (int) Carbon::parse($booking->end_time)->format('G');

        $assignedCourt = null;

        // Try to find a court that has all requested hours available (tersedia)
        for ($court = 1; $court <= $numCourts; $court++) {
            $allAvailable = true;
            for ($h = $startHour; $h < $endHour; $h++) {
                $existing = Slot::where('field_id', $field->id)
                    ->where('court_number', $court)
                    ->where('date', $date)
                    ->where('hour', $h)
                    ->first();

                if ($existing && $existing->status !== 'tersedia') {
                    $allAvailable = false;
                    break;
                }
            }

            if ($allAvailable) {
                $assignedCourt = $court;
                break;
            }
        }

        // If no court fully available, find one with 'tersedia' or null slots
        if (!$assignedCourt) {
            for ($court = 1; $court <= $numCourts; $court++) {
                $canAssign = true;
                for ($h = $startHour; $h < $endHour; $h++) {
                    $existing = Slot::where('field_id', $field->id)
                        ->where('court_number', $court)
                        ->where('date', $date)
                        ->where('hour', $h)
                        ->first();

                    if ($existing && in_array($existing->status, ['dibooking', 'tutup', 'perbaikan'])) {
                        $canAssign = false;
                        break;
                    }
                }

                if ($canAssign) {
                    $assignedCourt = $court;
                    break;
                }
            }
        }

        // If still none, use first court
        if (!$assignedCourt) {
            $assignedCourt = 1;
        }

        // Update slots for the assigned court
        for ($h = $startHour; $h < $endHour; $h++) {
            Slot::updateOrCreate(
                [
                    'field_id'     => $field->id,
                    'court_number' => $assignedCourt,
                    'date'         => $date,
                    'hour'         => $h,
                ],
                ['status' => 'dibooking']
            );
        }

        $booking->update([
            'status' => BookingStatus::CONFIRMED,
            'court_number' => $assignedCourt,
            'confirmed_at' => now(),
        ]);

        $booking->load('user');
        $booking->user->notify(new \App\Notifications\BookingConfirmed($booking));

        return back()->with('success', 'Booking telah dikonfirmasi. Lapangan ' . $assignedCourt . ' otomatis dibooking.');
    }

    public function rejectPayment(Booking $booking)
    {
        if ($booking->field->owner_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== BookingStatus::WAITING_CONFIRMATION) {
            return back()->with('error', 'Booking tidak dalam status menunggu konfirmasi.');
        }

        // Validate status transition
        if (!self::isValidTransition($booking->status, BookingStatus::REJECTED)) {
            return back()->with('error', 'Tidak dapat menolak booking dengan status saat ini.');
        }

        // Revert dibooking slots back to tersedia (only for the assigned court)
        $field = $booking->field;
        $date = $booking->date instanceof Carbon ? $booking->date->toDateString() : $booking->date;
        $startHour = (int) Carbon::parse($booking->start_time)->format('G');
        $endHour = (int) Carbon::parse($booking->end_time)->format('G');

        $query = Slot::where('field_id', $field->id)
            ->where('date', $date)
            ->whereBetween('hour', [$startHour, $endHour - 1])
            ->where('status', 'dibooking');

        // Only revert the specific court if the booking has one assigned
        if ($booking->court_number) {
            $query->where('court_number', $booking->court_number);
        }

        $query->update(['status' => 'tersedia']);

        $booking->update([
            'status' => BookingStatus::REJECTED,
        ]);

        return back()->with('success', 'Booking pembayaran telah ditolak.');
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== BookingStatus::CONFIRMED) {
            return back()->with('error', 'Hanya booking dengan status Dikonfirmasi yang dapat dibatalkan.');
        }

        $bookingDate = $booking->date instanceof Carbon ? $booking->date->toDateString() : $booking->date;
        $today = now()->toDateString();
        $currentTime = now()->toTimeString();

        if ($bookingDate < $today || ($bookingDate === $today && $booking->start_time <= $currentTime)) {
            return back()->with('error', 'Tidak dapat membatalkan booking yang sudah atau sedang berlangsung.');
        }

        if (!self::isValidTransition($booking->status, BookingStatus::CANCELLED)) {
            return back()->with('error', 'Tidak dapat membatalkan booking dengan status saat ini.');
        }

        $field = $booking->field;
        $date = $bookingDate;
        $startHour = (int) Carbon::parse($booking->start_time)->format('G');
        $endHour = (int) Carbon::parse($booking->end_time)->format('G');

        $query = Slot::where('field_id', $field->id)
            ->where('date', $date)
            ->whereBetween('hour', [$startHour, $endHour - 1])
            ->where('status', 'dibooking');

        if ($booking->court_number) {
            $query->where('court_number', $booking->court_number);
        }

        $query->update(['status' => 'tersedia']);

        $booking->update(['status' => BookingStatus::CANCELLED]);

        try {
            $owner = $booking->field->owner;
            if ($owner) {
                $owner->notify(new \App\Notifications\Owner\OwnerBookingCancelled($booking));
            }
        } catch (\Throwable $e) {
            Log::warning('Gagal notifikasi owner: ' . $e->getMessage());
        }

        return redirect()->route('booking.detail', $booking->id)->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
