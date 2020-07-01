<?php

namespace App\Listeners\Manor;

use App\Events\Manor\DailyWaterDropRemindLunch;
use App\Services\PushServices;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class DailyWaterDropRemindLunchListener implements ShouldQueue
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
     * @param DailyWaterDropRemindLunch $event
     * @author sunshine
     */
    public function handle(DailyWaterDropRemindLunch $event)
    {
        try {
            $this->pushService->dailyWaterDropRemindLunch($event->to);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}

