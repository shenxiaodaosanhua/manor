<?php


namespace App\Services\Task;


use App\Events\BehaviorEvent;
use App\Exceptions\TaskDateBetweenException;
use App\Exceptions\TaskDriveException;
use App\Models\Behavior;
use App\Models\Plant;
use App\Models\Setting;
use App\Models\TaskState;
use App\Traits\TaskStateTrait;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TaskTodayThreeMealsService
 * @package App\Services\Task
 */
class TaskTodayThreeMealsService implements TaskInterface
{
    use TaskStateTrait;

    /**
     * @var string
     */
    protected $taskName = 'today_three_meals';

    /**
     * 获取状态
     * @param Plant $plant
     * @return array
     * @throws \Exception
     */
    public function getState(Plant $plant)
    {

        try {
            $state = $this->findState($plant);
            $isReceive = $state->count();

            return [
                'is_receive' => $isReceive,
                'next_receive_text' => '',
                'last_text' => '',
            ];

        } catch (TaskDateBetweenException $exception) {
            $next = $this->getNextBetween(now());

            if (! count($next)) {
                return [
                    'is_receive' => 1,
                    'next_receive_text' => '明日开启',
                    'last_text' => '',
                ];
            }

            return  [
                'is_receive' => 1,
                'next_receive_text' => "{$next['start']}开启",
                'last_text' => '',
            ];
        }
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
        if ($state->count()) {
            throw new HttpException(403, '当前时间段已领取水滴，请勿重复领取');
        }

        $waters = $this->getRandomWaters($tasks['value']);
        $tasks['value'] = $waters;

        $behaviorData = [
            'user_id' => $plant->user_id,
            'bhv_type' => $this->taskName,
            'trace_id' => Behavior::TRACE_MANOR,
        ];
        event(new BehaviorEvent($behaviorData));

        return $this->createTaskState($plant, $tasks);
    }

    /**
     * 获取当前任务领取状态
     * @param Plant $plant
     * @return mixed
     * @throws TaskDateBetweenException
     */
    public function findState(Plant $plant)
    {
        $now = now();
        $between = $this->getNowBetween($now);

        if (! count($between)) {
            throw new TaskDateBetweenException('当前不在可领取时间段', 403);
        }

        return TaskState::where('name', $this->taskName)
            ->where('plant_id', $plant->id)
            ->whereBetween('created_at', [Carbon::parse($between['start']), Carbon::parse($between['end'])])
            ->get();
    }

    /**
     * 获取下一个可领时间
     * @param Carbon $date
     * @return array|string[]
     * @throws \Exception
     */
    protected function getNextBetween(Carbon $date)
    {
        $data = [];
        foreach (Setting::$dateBetween as $item) {
            if (! Carbon::parse($date)->lt(Carbon::parse($item['end']))) {
                continue;
            }

            $data = $item;
        }

        return $data;
    }

    /**
     * 获取当前时间是否在时间段内
     * @param Carbon $date
     * @return array
     * @throws \Exception
     */
    protected function getNowBetween(Carbon $date)
    {
        $data = [];
        foreach (Setting::$dateBetween as $item) {
            if (! Carbon::parse($date)->between(Carbon::parse($item['start']), Carbon::parse($item['end']))) {
                continue;
            }

            $data = $item;
        }

        return $data;
    }

    /**
     * @param array $data
     * @param Plant $plant
     * @return string
     */
    public function getDynamic(array $data, Plant $plant)
    {
        return "领取每日三餐福袋，水滴+{$data['water']}";
    }
}
