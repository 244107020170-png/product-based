<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Field;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function __construct(protected DatabaseManager $db)
    {
    }

    public function createBooking(array $data, User $user): Booking
    {
        $field = Field::findOrFail($data['field_id']);

        if ($field->owner_id === $user->id) {
            throw ValidationException::withMessages(['field_id' => 'Anda tidak dapat memesan lapangan milik sendiri.']);
        }

        if (! $field->is_available) {
            throw ValidationException::withMessages(['field_id' => 'Lapangan ini tidak tersedia untuk dipesan.']);
        }

        $timezone = config('app.timezone');
        $date = Carbon::parse($data['date'], $timezone)->startOfDay();
        $start = Carbon::createFromFormat('H:i', $data['start_time'], $timezone);
        $end = Carbon::createFromFormat('H:i', $data['end_time'], $timezone);

        Log::debug('BookingService parsed date/time', [
            'raw_date' => $data['date'],
            'parsed_date' => $date->toDateString(),
            'raw_start_time' => $data['start_time'],
            'raw_end_time' => $data['end_time'],
            'parsed_start' => $start ? $start->toTimeString() : null,
            'parsed_end' => $end ? $end->toTimeString() : null,
        ]);

        if (! $start || ! $end) {
            throw ValidationException::withMessages(['start_time' => 'Format waktu tidak valid. Gunakan format HH:MM.']);
        }

        $durationMinutes = $end->diffInMinutes($start, true);
        Log::debug('BookingService duration debug', [
            'duration_minutes' => $durationMinutes,
            'start_time' => $start->format('H:i'),
            'end_time' => $end->format('H:i'),
        ]);

        $this->validateDate($date, $start);
        $this->validateDuration($start, $end);

        return $this->db->transaction(function () use ($field, $user, $date, $start, $end, $data) {
            $this->ensureSlotIsAvailable($field, $date, $start, $end);

            return Booking::create([
                'user_id' => $user->id,
                'field_id' => $field->id,
                'date' => $date->toDateString(),
                'start_time' => $start->format('H:i:s'),
                'end_time' => $end->format('H:i:s'),
                'status' => BookingStatus::WAITING_PAYMENT,
                'payment_deadline' => Carbon::now()->addMinutes(15),
                'expired_at' => Carbon::now()->addHours(4),
            ]);
        });
    }

    protected function validateDate(Carbon $date, Carbon $start): void
    {
        if ($date->isPast()) {
            throw ValidationException::withMessages(['date' => 'Booking tanggal lalu tidak diperbolehkan.']);
        }

        $bookingStart = $date->copy()->setTimeFrom($start);

        if ($bookingStart->lessThanOrEqualTo(Carbon::now())) {
            throw ValidationException::withMessages(['start_time' => 'Waktu mulai harus di masa depan untuk booking hari ini.']);
        }

        if ($bookingStart->lessThan(Carbon::now()->addHours(3))) {
            throw ValidationException::withMessages(['start_time' => 'Pemesanan tidak dapat dilakukan karena jadwal bermain kurang dari 3 jam dari waktu saat ini.']);
        }
    }

    protected function validateDuration(Carbon $start, Carbon $end): void
    {
        if (! $start->lt($end)) {
            throw ValidationException::withMessages(['end_time' => 'Waktu selesai harus setelah waktu mulai.']);
        }

        $duration = $end->diffInMinutes($start, true);

        if ($duration < 60) {
            throw ValidationException::withMessages(['end_time' => 'Durasi booking minimal 1 jam.']);
        }

        if ($duration % 60 !== 0) {
            throw ValidationException::withMessages(['end_time' => 'Durasi booking harus kelipatan 1 jam.']);
        }

        $opening = Carbon::createFromTime(8, 0);
        $closing = Carbon::createFromTime(20, 0);

        if ($start->lt($opening) || $end->gt($closing)) {
            throw ValidationException::withMessages(['start_time' => 'Slot booking hanya tersedia antara 08:00 dan 20:00.']);
        }
    }

    protected function ensureSlotIsAvailable(Field $field, Carbon $date, Carbon $start, Carbon $end): void
    {
        $overlap = Booking::where('field_id', $field->id)
            ->whereDate('date', $date->toDateString())
            ->whereIn('status', BookingStatus::activeStatuses())
            ->where(function (Builder $query) use ($start, $end) {
                $query->where(function (Builder $query) use ($start, $end) {
                    $query->where('start_time', '<', $end->format('H:i:s'))
                        ->where('end_time', '>', $start->format('H:i:s'));
                });
            })
            ->lockForUpdate()
            ->exists();

        if ($overlap) {
            throw ValidationException::withMessages(['start_time' => 'Slot ini sudah dibooking atau bertabrakan dengan booking lain.']);
        }
    }

    public function getAvailableSlots(Field $field, Carbon $date): array
    {
        $bookings = Booking::where('field_id', $field->id)
            ->whereDate('date', $date->toDateString())
            ->whereIn('status', BookingStatus::activeStatuses())
            ->orderBy('start_time')
            ->get();

        $times = [];

        for ($hour = 8; $hour < 20; $hour++) {
            $startTime = Carbon::createFromTimeString(sprintf('%02d:00', $hour));
            $endTime = Carbon::createFromTimeString(sprintf('%02d:00', $hour + 1));
            $available = true;

            foreach ($bookings as $booking) {
                $bookingStart = Carbon::parse($booking->start_time);
                $bookingEnd = Carbon::parse($booking->end_time);

                if ($bookingStart->lt($endTime) && $bookingEnd->gt($startTime)) {
                    $available = false;
                    break;
                }
            }

            $times[] = [
                'start' => $startTime->format('H:i'),
                'end' => $endTime->format('H:i'),
                'display' => $startTime->format('H:i') . ' - ' . $endTime->format('H:i'),
                'available' => $available,
            ];
        }

        return $times;
    }
}
