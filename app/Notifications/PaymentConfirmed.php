<?php

namespace App\Notifications;

use App\Models\Matchs;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentConfirmed extends Notification
{
    use Queueable;

    public function __construct(
        public Matchs $match
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'match_id' => $this->match->id,
            'match_title' => $this->match->title,
            'field_name' => $this->match->field?->name ?? 'Lapangan',
            'match_date' => $this->match->date,
            'match_time' => $this->match->time,
            'type' => 'payment_confirmed',
        ];
    }
}
