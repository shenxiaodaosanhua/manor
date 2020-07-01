<?php


namespace App\Services;


use App\Models\TaskState;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class TaskStateService
 * @package App\Services
 */
class TaskStateService
{
    /**
     * 获取指定任务名称
     * @param $taskName
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findTaskStatesByName($taskName)
    {
        return TaskState::with(['user', 'plant'])
            ->where('name', $taskName)
            ->get();
    }

}
