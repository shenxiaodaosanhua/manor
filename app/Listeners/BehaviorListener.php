<?php

namespace App\Listeners;

use App\Events\BehaviorEvent;
use App\Services\BehaviorService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Class BehaviorListener
 * @package App\Listeners
 */
class BehaviorListener implements ShouldQueue
{

    /**
     * @var BehaviorService
     */
    protected $behaviorService;

    /**
     * BehaviorListener constructor.
     * @param BehaviorService $behaviorService
     */
    public function __construct(BehaviorService $behaviorService)
    {
        $this->behaviorService = $behaviorService;
    }

    /**
     * Handle the event.
     *
     * @param  BehaviorEvent  $event
     * @return void
     */
    public function handle(BehaviorEvent $event)
    {
        $this->behaviorService->behaviorStore($event->data);
    }

    /**
     * @param BehaviorEvent $event
     * @param $exception
     */
    public function failed(BehaviorEvent $event, $exception)
    {
        Log::error($exception->getMessage());
    }
}
