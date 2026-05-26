<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Field;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct(protected BookingService $bookingService)
    {
    }
    /**
     * Show booking page for a specific field
     */
    public function show(Field $field, Request $request)
    {
        $allFields = Field::with('owner')->get();
        $availableTimes = $this->getAvailableTimes($field, $request->input('date', today()->toDateString()));
        
        return view('booking.show', [
            'field' => $field,
            'allFields' => $allFields,
            'availableTimes' => $availableTimes,
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
        return view('booking.detail', compact('booking'));
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

        $booking->update([
            'status' => BookingStatus::WAITING_CONFIRMATION,
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Kami menerima notifikasi pembayaran Anda. Silakan tunggu konfirmasi owner.');
    }

    public function confirmPayment(Booking $booking)
    {
        if ($booking->field->owner_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== BookingStatus::WAITING_CONFIRMATION) {
            return back()->with('error', 'Booking tidak dalam status menunggu konfirmasi.');
        }

        $booking->update([
            'status' => BookingStatus::CONFIRMED,
            'confirmed_at' => now(),
        ]);

        return back()->with('success', 'Booking telah dikonfirmasi.');
    }

    public function rejectPayment(Booking $booking)
    {
        if ($booking->field->owner_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== BookingStatus::WAITING_CONFIRMATION) {
            return back()->with('error', 'Booking tidak dalam status menunggu konfirmasi.');
        }

        $booking->update([
            'status' => BookingStatus::REJECTED,
        ]);

        return back()->with('success', 'Booking pembayaran telah ditolak.');
    }
}
