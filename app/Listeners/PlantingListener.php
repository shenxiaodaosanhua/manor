<?php

namespace App\Listeners;

use App\Events\Planting;
use App\Services\PlantLogService;
use App\Services\StageService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Class PlantingListener
 * @package App\Listeners
 */
class PlantingListener
{
    /**
     * @var PlantLogService
     */
    protected $plantLogService;

    /**
     * @var StageService
     */
    protected $stageService;

    /**
     * PlantingListener constructor.
     * @param PlantLogService $plantLogService
     * @param StageService $stageService
     */
    public function __construct(PlantLogService $plantLogService, StageService $stageService)
    {
        $this->plantLogService = $plantLogService;
        $this->stageService = $stageService;
    }

    /**
     * 首次领取水滴
     * @param Planting $event
     * @return mixed
     * @throws \Throwable
     * @author Jerry
     */
    public function handle(Planting $event)
    {
        try {
            $this->plantLogService->receiveFirstWater($event->plant);
            $this->stageService->initPlantStage($event->plant);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
