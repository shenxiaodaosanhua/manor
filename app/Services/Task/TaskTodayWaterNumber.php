<?php


namespace App\Services\Task;


use App\Events\BehaviorEvent;
use App\Models\Behavior;
use App\Models\Plant;
use App\Models\TaskState;
use App\Traits\TaskStateTrait;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TaskTodayWaterNumber
 * @package App\Services\Task
 */
class TaskTodayWaterNumber implements TaskInterface
{
    use TaskStateTrait;

    /**
     * @var string
     */
    protected $taskName = 'today_waters';

    /**
     * 完成任务
     * @param Plant $plant
     * @return mixed
     * @throws \App\Exceptions\TaskDriveException
     */
    public function achieve(Plant $plant)
    {
        $tasks = $this->getTasks();
        if ($tasks['state'] != 'on') {
            throw new HttpException(403, '任务已关闭');
        }

        $state = $this->findState($plant);
        if ($state->count()) {
            throw new HttpException(403, '任务已完成');
        }

        $waters = $this->getRandomWaters($tasks['value']);
        $tasks['value'] = $waters;

        $result = $this->createTaskState($plant, $tasks);

        $behaviorData = [
            'user_id' => $plant->user_id,
            'bhv_type' => $this->taskName,
            'trace_id' => Behavior::TRACE_MANOR,
        ];
        event(new BehaviorEvent($behaviorData));

        return $result;
    }

    /**
     * @param array $data
     * @param Plant $plant
     * @return string
     */
    public function getDynamic(array $data, Plant $plant)
    {
        return "每日免费领水滴，水滴+{$data['water']}";
    }

}
