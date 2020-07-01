<?php

namespace App\Listeners\Manor;

use App\Events\Manor\DailyWaterDropRemindDinner;
use App\Services\PushServices;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Class DailyWaterDropRemindDinnerListener
 * @package App\Listeners\Manor
 */
class DailyWaterDropRemindDinnerListener implements ShouldQueue
{

    /**
     * @var string
     */
    protected $queue = '{manor}';

    /**
     * @var PushServices
     */
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
     * @param DailyWaterDropRemindDinner $event
     * @author sunshine
     */
    public function handle(DailyWaterDropRemindDinner $event)
    {
        try {
            $this->pushService->dailyWaterDropRemindDinner($event->to);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}

