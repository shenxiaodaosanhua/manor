<?php


namespace App\Services;


use App\Models\Plant;
use App\Models\PlantTask;

/**
 * Class PlantTaskService
 * @package App\Services
 */
class PlantTaskService
{

    public function getMyTasks(Plant $plant)
    {
        $tasks = $this->findTasksBySuccess();

    }

    /**
     * 获取已启用任务
     * @return mixed
     * @author Jerry
     */
    public function findTasksBySuccess()
    {
        return PlantTask::with('progress')->where('state', PlantTask::STATE_SUCCESS)->get();
    }
}
