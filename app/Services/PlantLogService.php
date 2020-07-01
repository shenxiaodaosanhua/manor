<?php


namespace App\Services;


use App\Models\Plant;
use App\Models\PlantLog;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class PlantLogService
 * @package App\Services
 */
class PlantLogService
{

    /**
     * @var TaskService
     */
    protected $taskService;

    /**
     * @var PlantService
     */
    protected $plantService;

    /**
     * PlantLogService constructor.
     * @param TaskService $taskService
     * @param PlantService $plantService
     */
    public function __construct(TaskService $taskService, PlantService $plantService)
    {
        $this->taskService = $taskService;
        $this->plantService = $plantService;
    }

    /**
     * 领取每次种植水滴
     * @param Plant $plant
     * @return mixed
     * @throws \Throwable
     */
    public function receiveFirstWater(Plant $plant)
    {
        $waters = config('first-water-number');

        if (! $this->checkPlantFirst($plant)) {
            $waters = config('new-receive-waters');
        }

        return $this->firstWater($plant, $waters);
    }

    /**
     * 初次种植领取水滴
     * @param Plant $plant
     * @param int $waters
     * @return mixed
     * @throws \Throwable
     */
    public function firstWater(Plant $plant, $waters = 0)
    {
        $count = PlantLog::where('plant_id', $plant->id)
            ->where('type', PlantLog::LOG_FIRST)
            ->count();
        if ($count) {
            throw new HttpException(402, '已经领取过了，请勿重复领取');
        }

        return DB::transaction(function () use ($waters, $plant) {
            $createData = [
                'type' => PlantLog::LOG_FIRST,
                'water' => $waters,
                'user_id' => $plant->user_id,
                'plant_id' => $plant->id,
            ];

            if (! $this->checkPlantFirst($plant)) {
                $createData['type'] = PlantLog::LOG_NEW_RECEIVE;
            }

            $this->createPlantLog($createData);

            $plant->increment('waters', $waters);

            return $plant;
        });
    }

    /**
     * 检查用户是否第一颗植物
     * @param Plant $plant
     * @return mixed
     */
    public function checkPlantFirst(Plant $plant)
    {
        $count = $plant->where('user_id', $plant->user_id)->count();

        if ($count > 1) {
            return false;
        }

        return true;
    }

    /**
     * 创建一条记录
     * @param array $data
     * @return mixed
     * @author Jerry
     */
    public function createPlantLog($data = [])
    {
        return PlantLog::create($data);
    }

    /**
     * @param User $user
     * @return \Illuminate\Support\Collection
     */
    public function getMyLog(User $user)
    {
        $plantLog = $this->findPlantLogDynamic($user);

        if ($plantLog->isEmpty()) {
            return collect();
        }

        $logs = collect($plantLog->toArray())->map(function ($log) {
            $log['group_date'] = Carbon::parse($log['created_at'])->toDateString();
            $log['text'] = $this->getPlantLogText($log);
            $log['time'] = Carbon::parse($log['created_at'])->toTimeString();
            return $log;
        })->map(function ($log) {
            return [
                'time' => $log['time'],
                'text' => $log['text'],
                'group_date' => $log['group_date'],
            ];
        })->groupBy('group_date');

        return $logs;
    }

    /**
     * @param array $log
     * @return string
     * @throws \App\Exceptions\TaskDriveException
     */
    public function getPlantLogText(array $log)
    {
        switch ($log['type']) {
            case PlantLog::LOG_TASK:
                $plant = $this->getPlantById($log['plant_id']);
                $text = $this->taskService->drive($log['task_name'])->getDynamic($log, $plant);
                break;
            case PlantLog::LOG_FIRST:
                $text = "新园主专享水滴礼包，水滴+{$log['water']}";
                break;
            case PlantLog::LOG_UPGRADE:
                $text = "恭喜，果树升级啦~水滴+{$log['water']}";
                break;
            case PlantLog::LOG_SUCCESS:
                $text = '恭喜！经过辛勤的浇灌，果树收获了！可以去换取奖品啦！';
                break;
            case PlantLog::LOG_RECEIVE:
                $text = '您已换取奖品，可在庄园首页的奖品中查看物流状态。想要获得更多奖品，快来开启新一轮种植吧~';
                break;
            case PlantLog::LOG_TIME_OUT:
                $text = '很遗憾，您在果树收获后72小时内未换取奖品，奖品已失效。再开启一轮种植领取奖品吧~';
                break;
            case PlantLog::LOG_NEW_RECEIVE:
                $text = "水滴礼包，水滴+{$log['water']}";
                break;
            default:
                $text = "水滴+{$log['water']}";
        }

        return $text;
    }

    /**
     * @param int $plantId
     * @return mixed
     */
    public function getPlantById(int $plantId)
    {
        return $this->plantService->findPlantById($plantId);
    }

    /**
     * @param User $user
     * @param int $type
     * @return mixed
     */
    public function findPlantLogDynamic(User $user, $type = PlantLog::LOG_WATERING)
    {
        return PlantLog::without('plant')
            ->where('user_id', $user->id)
            ->where('type', '!=', $type)
            ->where('created_at', '>', today()->subDays(5))
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
