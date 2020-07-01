<?php

namespace App\Notifications;

use App\Channels\PushChannel;
use App\Channels\SiteChannel;
use App\Models\PushTemplates;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

/**
 * Class BagReceivePaid
 * @package App\Notifications
 */
class BagReceivePaid extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var
     */
    protected $users;

    /**
     * @var \string[][]
     */
    protected $channel = [
        PushTemplates::PUSH_TYPE_SITE_VALUE => [
            SiteChannel::class,
        ],
        PushTemplates::PUSH_TYPE_PUSH_VALUE => [
            PushChannel::class,
        ],
        PushTemplates::PUSH_TYPE_SITE_PUSH_VALUE => [
            SiteChannel::class,
            PushChannel::class,
        ],
    ];

    /**
     * BagReceivePaid constructor.
     * @param $users
     */
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
        return $this->channel[$notifiable->push_type];
    }


    /**
     * @param $notifiable
     * @return array
     */
    public function toSend($notifiable)
    {
        return [
            'to' => $this->users->toArray(),
            'content' => $notifiable->content,
            'title' => $notifiable->title,
            'jump_url' => $notifiable->jump_url,
        ];
    }
}
