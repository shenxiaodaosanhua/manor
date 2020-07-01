<?php


namespace App\Services\Task;


use App\Models\Plant;
use App\Models\TaskState;
use App\Traits\TaskStateTrait;

/**
 * Class TaskTodayBagService
 * @package App\Services\Task
 */
class TaskTodayBagService implements TaskInterface
{
    use TaskStateTrait;

    /**
     * @var string
     */
    protected $taskName = 'today_bag_waters';

    /**
     * @param Plant $plant
     * @return mixed
     */
    public function findState(Plant $plant)
    {
        return TaskState::where('name', $this->taskName)
            ->where('user_id', $plant->user_id)
            ->get();
    }

    /**
     * @param array $data
     * @param Plant $plant
     * @return string
     */
    public function getDynamic(array $data, Plant $plant)
    {
        return "开启每日免费领水滴提醒，水滴+{$data['water']}";
    }
}
