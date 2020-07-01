<?php


namespace App\Services;


use App\Exceptions\TaskDriveException;
use App\Models\Plant;
use App\Models\PlantLog;
use App\Models\Setting;
use App\Models\TaskState;
use App\Services\Task\TaskFoodOrderService;
use App\Services\Task\TaskSeeShopService;
use App\Services\Task\TaskShopOrderService;
use App\Services\Task\TaskSignInService;
use App\Services\Task\TaskTodayBagService;
use App\Services\Task\TaskTodayThreeMealsService;
use App\Services\Task\TaskTodayWaterNumber;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TaskService
 * @package App\Services
 */
class TaskService
{

    /**
     * @var string[]
     */
    protected $drive = [
        'today_bag_waters' => TaskTodayBagService::class,
        'today_waters' => TaskTodayWaterNumber::class,
        'see_shop_waters' => TaskSeeShopService::class,
        'shop_order' => TaskShopOrderService::class,
        'food_order' => TaskFoodOrderService::class,
        'today_three_meals' => TaskTodayThreeMealsService::class,
        'today_continuous' => TaskSignInService::class,
    ];

    /**
     * @param Plant $plant
     * @return array
     * @throws TaskDriveException
     */
    public function findTasks(Plant $plant)
    {
        $setting = Setting::orderBy('id', 'desc')->first();

        $data = [];
        foreach($setting->content as $key => $value) {
            if ((isset($value['state'])) && ($value['state'] != 'on')) {
                continue;
            }

            if ($key == 'today_continuous') {
                continue;
            }

            if (! array_key_exists($key, $this->drive)) {
                continue;
            }

            $state = $this->drive($key)->getState($plant);
            $data[] = [
                'title' => $value['title'] ?? '',
                'desc' => $value['desc'] ?? '',
                'name' => $key,
                'image' => $value['image'],
                'number' => isset($value['today_number']) ? $value['today_number'] : 0,
                'uri' => $value['uri'] ?? '',
                'task_state' => $state,
                'app_id' => isset($value['app_id']) ? $value['app_id'] : '',
            ];
        }

        return $data;
    }

    /**
     * 获取各种类型任务驱动
     * @param string $taskName
     * @return mixed
     * @throws TaskDriveException
     */
    public function drive($taskName = '')
    {
        if (empty($taskName)) {
            throw new HttpException(403, '请输入任务名称');
        }

        if (! array_key_exists($taskName, $this->drive)) {
            throw new TaskDriveException('不存在的任务');
        }

        return new $this->drive[$taskName];
    }

    /**
     * 过滤不能直接完成的任务
     * @param string $taskName
     * @return $this
     */
    public function filterTask($taskName = '')
    {
        $task = [
            'food_order',
            'shop_order'
        ];

        if (in_array($taskName, $task)) {
            throw new HttpException(403, '非法操作');
        }

        return $this;
    }

    /**
     * 完成任务插入水量日志并增加水量
     * @param Plant $plant
     * @param TaskState $taskState
     * @return mixed
     * @throws \Throwable
     */
    public function taskFulfil(Plant $plant, TaskState $taskState)
    {
        return DB::transaction(function () use ($taskState, $plant) {
            PlantLog::create([
                'plant_id' => $plant->id,
                'user_id' => $plant->user_id,
                'type' => PlantLog::LOG_TASK,
                'water' => $taskState->waters,
                'task_name' => $taskState->name,
            ]);

            $plant->increment('waters', $taskState->waters);

            return $plant;
        });
    }

    /**
     * 获取领取水滴是否可领取
     * @param Plant $plant
     * @return int[]
     * @throws TaskDriveException
     */
    public function getTaskIsReceive(Plant $plant)
    {
        $today = $this->drive('today_waters')->getState($plant);
        $three = $this->drive('today_three_meals')->getState($plant);

        $data = [
            'today_waters' => 1,
            'today_three_meals' => 1,
        ];
        if ($today['is_receive']) {
            $data['today_waters'] = 0;
        }

        if ($three['is_receive']) {
            $data['today_three_meals'] = 0;
        }

        return $data;
    }
}
