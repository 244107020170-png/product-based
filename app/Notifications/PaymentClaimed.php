<?php

namespace App\Notifications;

use App\Models\MatchPlayer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentClaimed extends Notification
{
    use Queueable;

    public function __construct(
        public MatchPlayer $participant
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'match_id' => $this->participant->match_id,
            'match_title' => $this->participant->match->title,
            'user_id' => $this->participant->user_id,
            'user_name' => $this->participant->user->name,
            'amount' => $this->participant->contribution_amount,
            'type' => 'payment_claimed',
        ];
    }
}
