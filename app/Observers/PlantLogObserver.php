<?php

namespace App\Observers;



use App\Models\PlantLog;
use App\Services\StageService;

/**
 * Class PlantLogObserver
 * @package App\Observers
 */
class PlantLogObserver
{

    /**
     * @var StageService
     */
    protected $stageService;

    /**
     * PlantLogObserver constructor.
     * @param StageService $stageService
     */
    public function __construct(StageService $stageService)
    {
        $this->stageService = $stageService;
    }

    /**
     * 判断浇水日志插入触发事件
     * @param PlantLog $plantLog
     * @throws \Throwable
     */
    public function created(PlantLog $plantLog)
    {
        if ($plantLog->type == PlantLog::LOG_WATERING) {
            $this->stageService->updatePlantStage($plantLog->plant);
        }

        return;
    }

    /**
     * Handle the plant log "updated" event.
     *
     * @param  \App\PlantLog  $plantLog
     * @return void
     */
    public function updated(PlantLog $plantLog)
    {
    }

    /**
     * Handle the plant log "deleted" event.
     *
     * @param  \App\PlantLog  $plantLog
     * @return void
     */
    public function deleted(PlantLog $plantLog)
    {
        //
    }

    /**
     * Handle the plant log "restored" event.
     *
     * @param  \App\PlantLog  $plantLog
     * @return void
     */
    public function restored(PlantLog $plantLog)
    {
        //
    }

    /**
     * Handle the plant log "force deleted" event.
     *
     * @param  \App\PlantLog  $plantLog
     * @return void
     */
    public function forceDeleted(PlantLog $plantLog)
    {
        //
    }
}
