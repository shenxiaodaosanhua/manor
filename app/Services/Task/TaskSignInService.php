<?php


namespace App\Services\Task;


use App\Events\BehaviorEvent;
use App\Exceptions\TaskDriveException;
use App\Models\Behavior;
use App\Models\Plant;
use App\Models\PlantLog;
use App\Models\Setting;
use App\Models\TaskState;
use App\Services\PlantService;
use App\Traits\TaskStateTrait;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TaskSignInService
 * @package App\Services\Task
 */
class TaskSignInService implements TaskInterface
{
    use TaskStateTrait;

    /**
     * @var string
     */
    protected $taskName = 'today_continuous';

    /**
     * @var array
     */
    protected $config;

    /**
     * TaskSignInService constructor.
     */
    public function __construct()
    {
        $this->config = $this->findConfig();
    }

    /**
     * @param Plant $plant
     * @return array
     */
    public function getState(Plant $plant)
    {
        return $this->getSignInDays($plant);
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

        $count = $this->findStateByPlantIdAndDay($plant, today());

        if ($count) {
            throw new HttpException(403, '今天已签到，请明天再来');
        }
        $days = count($this->getContinuousSignInDays($plant));
        $days = $days >= 5 ? 1 : $days + 1;


        $tasks['value'] = $tasks['value'][$days];
        $isLast = TaskState::LAST_NOT;
        if ($days == 5) {
            $isLast = TaskState::LAST_YES;
            $behaviorData = [
                'user_id' => $plant->user_id,
                'bhv_type' => 'sign_in_success',
                'trace_id' => Behavior::TRACE_MANOR,
            ];
            event(new BehaviorEvent($behaviorData));
        }

        $behaviorData = [
            'user_id' => $plant->user_id,
            'bhv_type' => $this->taskName,
            'trace_id' => Behavior::TRACE_MANOR,
        ];
        event(new BehaviorEvent($behaviorData));

        return $this->createTaskState($plant, $tasks, $isLast);
    }


    /**
     * 获取
     * @param Plant $plant
     * @return array
     */
    protected function getSignInDays(Plant $plant)
    {
        $signDaysData = $this->getContinuousSignInDays($plant);

        if (count($signDaysData) == 5) {
            $signDaysData = [];
        }

        $todayData = $this->getTodaySignIn($plant, today());
        array_unshift($signDaysData, $todayData);

        $number = 1;
        for ($i = count($signDaysData); $i < 5; $i++) {
            $data = [
                'is_receive' => 0,
                'date_at' => today()->addDays($number)->toDateString(),
                'is_today' => 0,
            ];
            array_unshift($signDaysData, $data);
            $number++;
        }
        $result = $this->arraySort($signDaysData, 'date_at', SORT_ASC);

        $dayNumber = 1;
        foreach ($result as $key => $value) {
            $result[$key]['waters'] = $this->config['value'][$dayNumber];
            $dayNumber++;
        }

        return $result;
    }

    /**
     * 数组排序
     * @param $array
     * @param $keys
     * @param int $sort
     * @return mixed
     */
    protected function arraySort($array, $keys, $sort = SORT_DESC) {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }

    /**
     * 获取今天签到情况
     * @param Plant $plant
     * @param Carbon $carbon
     * @return array
     */
    public function getTodaySignIn(Plant $plant, Carbon $carbon): array
    {
        $count = $this->findStateByPlantIdAndDay($plant, $carbon);

        return [
            'is_receive' => $count,
            'date_at' => today()->toDateString(),
            'is_today' => 1,
        ];
    }

    /**
     * 获取连续签到数据
     * @param Plant $plant
     * @return array
     */
    protected function getContinuousSignInDays(Plant $plant)
    {
        $data = [];
        for ($i = 1; $i <= 5; $i++) {
            $day = today()->subDays($i);
            $count = $this->findStateByPlantIdAndDay($plant, $day);
            if (! $count) {
                break;
            }
            $data[] = [
                'is_receive' => 1,
                'date_at' => $day->toDateString(),
                'is_today' => 0,
            ];
        }

        return $data;
    }

    /**
     * @param Plant $plant
     * @param Carbon $carbon
     * @return mixed
     */
    protected function findStateByPlantIdAndDay(Plant $plant, Carbon $carbon)
    {
        return TaskState::where('name', $this->taskName)
                    ->where('plant_id', $plant->id)
//                    ->where('is_last', TaskState::LAST_NOT)
                    ->whereDate('created_at', $carbon)
                    ->count();
    }

    /**
     * @param array $data
     * @param Plant $plant
     * @return string
     */
    public function getDynamic(array $data, Plant $plant)
    {
        $date = Carbon::parse($data['created_at']);
        $days = $this->getContinuousSignInDaysByDate($plant, $date);
        $today = $this->getTodaySignIn($plant, $date);

        if ($today['is_receive'] == 0) {
            array_unshift($days, $today);
        }
        $daysCount = count($days);

        return "连续签到第{$daysCount}天，水滴+{$data['water']}";
    }


    /**
     * @param Plant $plant
     * @param Carbon $carbon
     * @return array
     */
    protected function getContinuousSignInDaysByDate(Plant $plant, Carbon $carbon)
    {
        $data = [];
        for ($i = 1; $i <= 5; $i++) {
            $day = $carbon->subDays($i);
            $count = $this->findStateByPlantIdAndDayAndLast($plant, $day);
            if (! $count) {
                break;
            }
            $data[] = [
                'is_receive' => 1,
                'date_at' => $day->toDateString(),
                'is_today' => 0,
            ];
        }

        return $data;
    }

    /**
     * @param Plant $plant
     * @param Carbon $carbon
     * @return mixed
     */
    protected function findStateByPlantIdAndDayAndLast(Plant $plant, Carbon $carbon)
    {
        return TaskState::where('name', $this->taskName)
            ->where('plant_id', $plant->id)
            ->where('is_last', TaskState::LAST_NOT)
            ->whereDate('created_at', $carbon)
            ->count();
    }
}
