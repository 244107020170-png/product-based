<?php

namespace App\Notifications;

use App\Models\Community;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommunityJoined extends Notification
{
    use Queueable;

    public function __construct(
        public Community $community,
        public User $joiner,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'community_id' => $this->community->id,
            'community_name' => $this->community->name,
            'user_id' => $this->joiner->id,
            'user_name' => $this->joiner->name,
            'type' => 'community_joined',
        ];
    }
}
