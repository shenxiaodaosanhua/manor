<?php

namespace App\Notifications;

use App\Channels\SiteChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class SendSitePaid
 * @package App\Notifications
 */
class SendSitePaid extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var
     */
    protected $users;

    /**
     * SendSitePaid constructor.
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
        return [SiteChannel::class];
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toSite($notifiable)
    {
        return [
            'to' => $this->users->toArray(),
            'content' => $notifiable->content,
            'title' => $notifiable->title,
            'jump_url' => $notifiable->jump_url,
        ];
    }
}
