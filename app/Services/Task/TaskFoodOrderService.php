<?php


namespace App\Services\Task;


use App\Events\BehaviorEvent;
use App\Exceptions\TaskDriveException;
use App\Models\Behavior;
use App\Models\Plant;
use App\Models\TaskState;
use App\Traits\TaskStateTrait;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TaskFoodOrderService
 * @package App\Services\Task
 */
class TaskFoodOrderService implements TaskInterface
{
    use TaskStateTrait;

    /**
     * @var string
     */
    protected $taskName = 'food_order';

    /**
     * @param Plant $plant
     * @return array
     */
    public function getState(Plant $plant)
    {
        $state = $this->findState($plant);

        $config = $this->findConfig();

        $stateCount = $state->count();
        $taskCount = $config['today_number'];
        $isReceive = ($taskCount - $stateCount) ? 0 : 1;
        $result = [
            'is_receive' => $isReceive,
            'next_receive_text' => '',
            'last_text' => '',
        ];

        if ($config['today_number'] > 0) {
            $lastNumber = $taskCount - $stateCount;
            $result['last_text'] = "剩余{$lastNumber}次";
        }

        return $result;
    }

    /**
     * @param Plant $plant
     * @return mixed
     */
    public function findState(Plant $plant)
    {
        return TaskState::where('name', $this->taskName)
            ->where('plant_id', $plant->id)
            ->whereDate('created_at', today())
            ->get();
    }

    /**
     * 完成任务
     * @param Plant $plant
     * @return mixed
     * @throws TaskDriveException
     */
    public function achieve(Plant $plant)
    {
        $tasks = $this->getTasks();
        if ($tasks['state'] != 'on') {
            throw new HttpException(403, '任务已关闭');
        }

        $state = $this->findState($plant);
        $stateCount = $state->count();
        $config = $this->findConfig();
        $taskCount = $config['today_number'];
        $isTask = ($taskCount - $stateCount) ? 0 : 1;
        if ($isTask) {
            throw new HttpException(403, '任务今天已完成，请明天再来');
        }

        $behaviorData = [
            'user_id' => $plant->user_id,
            'bhv_type' => $this->taskName,
            'trace_id' => Behavior::TRACE_MANOR,
        ];
        event(new BehaviorEvent($behaviorData));

        return $this->createTaskState($plant, $tasks);
    }

    /**
     * 获取动态
     * @param array $data
     * @param Plant $plant
     * @return string
     */
    public function getDynamic(array $data, Plant $plant)
    {
        return "在美食中下单商品，水滴+{$data['water']}";
    }
}
