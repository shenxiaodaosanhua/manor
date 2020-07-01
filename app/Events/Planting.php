<?php

namespace App\Events;

use App\Models\Plant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class Planting
 * @package App\Events
 */
class Planting
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Plant
     */
    public $plant;

    /**
     * Planting constructor.
     * @param Plant $plant
     */
    public function __construct(Plant $plant)
    {
        $this->plant = $plant;
    }
}
