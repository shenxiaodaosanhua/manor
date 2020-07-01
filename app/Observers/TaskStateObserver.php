<?php

namespace App\Observers;

use App\Models\TaskState;
use App\Services\PlantService;
use App\Services\TaskService;
use Illuminate\Support\Facades\Log;

/**
 * Class TaskStateObserver
 * @package App\Observers
 */
class TaskStateObserver
{

    /**
     * @var PlantService
     */
    protected $plantService;

    protected $taskService;


    public function __construct(PlantService $plantService, TaskService $taskService)
    {
        $this->plantService = $plantService;
        $this->taskService = $taskService;
    }

    /**
     * 完成任务更新水量
     * @param TaskState $taskState
     * @throws \Throwable
     */
    public function created(TaskState $taskState)
    {
        try {
            $plant = $this->plantService->findPlantByUserId($taskState->user_id);
            $this->taskService->taskFulfil($plant, $taskState);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    /**
     * Handle the task state "updated" event.
     *
     * @param  \App\TaskState  $taskState
     * @return void
     */
    public function updated(TaskState $taskState)
    {
        //
    }

    /**
     * Handle the task state "deleted" event.
     *
     * @param  \App\TaskState  $taskState
     * @return void
     */
    public function deleted(TaskState $taskState)
    {
        //
    }

    /**
     * Handle the task state "restored" event.
     *
     * @param  \App\TaskState  $taskState
     * @return void
     */
    public function restored(TaskState $taskState)
    {
        //
    }

    /**
     * Handle the task state "force deleted" event.
     *
     * @param  \App\TaskState  $taskState
     * @return void
     */
    public function forceDeleted(TaskState $taskState)
    {
        //
    }
}
