<?php

namespace App\Events\RedEnvelopeSignIn;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SignInTip
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $to;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $to)
    {
        $this->to = $to;
    }
}
