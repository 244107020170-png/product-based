<?php

namespace App\Notifications\Owner;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OwnerNewBooking extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'owner_new_booking',
            'booking_id'  => $this->booking->id,
            'field_name'  => $this->booking->field?->name ?? 'Lapangan',
            'user_name'   => $this->booking->user?->name ?? 'Pemain',
            'user_id'     => $this->booking->user?->id,
            'date'        => $this->booking->date,
            'start_time'  => $this->booking->start_time,
            'end_time'    => $this->booking->end_time,
        ];
    }
}
