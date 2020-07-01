<?php


namespace App\Traits;


use App\Events\BehaviorEvent;
use App\Exceptions\TaskDriveException;
use App\Models\Behavior;
use App\Models\Plant;
use App\Models\Setting;
use App\Models\TaskState;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Trait TaskStateTrait
 * @package App\Traits
 */
trait TaskStateTrait
{

    /**
     * 获取当前任务配置
     * @return array
     */
    protected function findConfig()
    {
        $setting = Setting::orderBy('id', 'desc')->first();

        $config = [];
        foreach($setting->content as $key => $value) {
            if ($key == $this->taskName) {
                $config = $value;
                break;
            }
        }

        return $config;
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
     * @param Plant $plant
     * @return array
     */
    public function getState(Plant $plant)
    {
        $state = $this->findState($plant);
        $config = $this->findConfig();

        $result = [
            'is_receive' => $state->count(),
            'next_receive_text' => '',
            'last_text' => '',
        ];

        if ($config['today_number'] > 0) {
            $lastNumber = $config['today_number'] - $state->count();
            $result['last_text'] = "剩余{$lastNumber}次";
        }

        return $result;
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
     * 获取当前任务配置
     * @return array
     * @throws TaskDriveException
     */
    protected function getTasks() : array
    {
        $setting = Setting::orderBy('id', 'desc')
            ->first();

        $tasks = $setting->content;

        if (! array_key_exists($this->taskName, $tasks)) {
            throw new TaskDriveException('任务异常，请联系管理员', 501);
        }

        return  $tasks[$this->taskName];
    }

    /**
     * @param Plant $plant
     * @param array $task
     * @param int $isLast
     * @return mixed
     */
    protected function createTaskState(Plant $plant, array $task, $isLast = TaskState::LAST_NOT)
    {
        return TaskState::create([
            'plant_id' => $plant->id,
            'user_id' => $plant->user_id,
            'waters' => $task['value'],
            'name' => $this->taskName,
            'is_last' => $isLast,
        ]);
    }

    /**
     * 根据权重获取水滴数
     * @param array $value
     * @return int|string
     */
    public function getRandomWaters($value = [])
    {
        $result = collect($value)->map(function ($item) {
            return $item * 100;
        });


        $resultKey = Arr::random(array_keys($value));
        $sum = array_sum($result->toArray());
        foreach($result->toArray() as $key => $value) {
            $random = mt_rand(1, $sum);
            if($random <= $key){
                $resultKey = $key;
                break;
            }

            $sum = max(0, $sum - $value);
        }

        if (! $resultKey) {
            return 0;
        }

        return $resultKey;
    }
}
