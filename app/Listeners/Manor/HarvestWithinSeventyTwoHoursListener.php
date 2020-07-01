<?php

namespace App\Listeners\Manor;

use App\Events\Manor\HarvestWithinSeventyTwoHours;
use App\Services\PushServices;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HarvestWithinSeventyTwoHoursListener implements ShouldQueue
{
    protected $queue = '{manor}';

    protected $pushService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PushServices $pushService)
    {
        $this->pushService = $pushService;
    }

    /**
     * @param HarvestWithinSeventyTwoHours $event
     * @author sunshine
     */
    public function handle(HarvestWithinSeventyTwoHours $event)
    {
        try {
            $this->pushService->harvestWithinSeventyTwoHours($event->to);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
