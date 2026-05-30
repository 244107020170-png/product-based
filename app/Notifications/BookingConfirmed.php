<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingConfirmed extends Notification
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
            'booking_id'  => $this->booking->id,
            'field_name'  => $this->booking->field?->name ?? 'Lapangan',
            'maps_link'   => $this->booking->field?->maps_link,
            'date'        => $this->booking->date,
            'start_time'  => $this->booking->start_time,
            'end_time'    => $this->booking->end_time,
            'type'        => 'booking_confirmed',
        ];
    }
}
