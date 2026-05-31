<?php

namespace App\Notifications\Owner;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OwnerNewReview extends Notification
{
    use Queueable;

    public function __construct(
        public Review $review
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'owner_new_review',
            'review_id'  => $this->review->id,
            'field_id'   => $this->review->field_id,
            'field_name' => $this->review->field?->name ?? 'Lapangan',
            'user_name'  => $this->review->user?->name ?? 'Pemain',
            'user_id'    => $this->review->user?->id,
            'rating'     => $this->review->rating,
        ];
    }
}
