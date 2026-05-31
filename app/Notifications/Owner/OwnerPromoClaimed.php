<?php

namespace App\Notifications\Owner;

use App\Models\User;
use App\Models\Discount;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OwnerPromoClaimed extends Notification
{
    use Queueable;

    public function __construct(
        public Discount $discount,
        public User $user
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'owner_promo_claimed',
            'discount_id' => $this->discount->id,
            'promo_name'  => $this->discount->name,
            'promo_code'  => $this->discount->code,
            'user_name'   => $this->user->name,
            'user_id'     => $this->user->id,
        ];
    }
}
