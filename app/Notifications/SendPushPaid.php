<?php

namespace App\Notifications;

use App\Channels\PushChannel;
use App\Models\PushTemplates;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendPushPaid extends Notification implements ShouldQueue
{
    use Queueable;

    public $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {

        return [PushChannel::class];
    }


    public function toPush(PushTemplates $notifiable)
    {
        return [
            'to' => $this->users->toArray(),
            'content' => $notifiable->content,
            'title' => $notifiable->title,
            'jump_url' => $notifiable->jump_url,
        ];
    }

}
