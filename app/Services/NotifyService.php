<?php


namespace App\Services;



use App\Models\Plant;
use App\Notifications\BagReceivePaid;
use App\Notifications\MatureWatersPaid;
use App\Notifications\TaskWatersPaid;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * Class NotifyService
 * @package App\Services
 */
class NotifyService
{
    /**
     * @var TaskStateService
     */
    protected $taskStateService;

    /**
     * @var TaskService
     */
    protected $taskService;

    /**
     * @var PlantService
     */
    protected $plantService;

    /**
     * @var BagNotifyService
     */
    protected $bagNotifyService;

    /**
     * @var BagLogService
     */
    protected $bagLogService;

    /**
     * @var PushServices
     */
    protected $pushService;


    /**
     * NotifyService constructor.
     * @param TaskStateService $taskStateService
     * @param TaskService $taskService
     * @param PlantService $plantService
     * @param BagNotifyService $bagNotifyService
     * @param BagLogService $bagLogService
     * @param PushServices $pushServices
     */
    public function __construct(TaskStateService $taskStateService,
                                TaskService $taskService,
                                PlantService $plantService,
                                BagNotifyService $bagNotifyService,
                                BagLogService $bagLogService,
                                PushServices $pushServices
    )
    {
        $this->taskStateService = $taskStateService;
        $this->taskService = $taskService;
        $this->plantService = $plantService;
        $this->bagNotifyService = $bagNotifyService;
        $this->bagLogService = $bagLogService;
        $this->pushService = $pushServices;
    }

    /**
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function findTaskStatesByName($name = 'today_bag_waters')
    {
        return $this->taskStateService->findTaskStatesByName($name);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getThreeUsers()
    {
        $onTasks = $this->findTaskStatesByName();

        $users = collect();
        $onTasks->each(function ($task) use ($users) {
            $result = $this->taskService->getTaskIsReceive($task->plant);

            if ($result['today_three_meals']) {
                $users->add($task->user);
            }
        });

        return $users;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getMature()
    {
        $onTasks = $this->findTaskStatesByName();

        $users = collect();
        $onTasks->each(function ($task) use ($users) {
            $status = $this->plantService->getPlantStatus($task->user);
            if (in_array($status['plant_state'], [
                Plant::STATE_SUCCESS,
                Plant::STATE_SELECT,
            ])) {
                $users->add($task->user);
            }
        });

        return $users;
    }

    /**
     * 午餐提醒
     */
    public function handleLunchWatersNotify()
    {
        $to = $this->getThreeUsers();
        $template = $this->pushService->findTemplateByCode('daily_water_drop_remind_lunch');

        if ($to->isNotEmpty()) {
            Notification::send($template, new TaskWatersPaid($to));
            exit;
        }

        Log::info('推送用户为空');
    }

    /**
     * 晚餐提醒
     */
    public function handleDinnerWatersNotify()
    {
        $to = $this->getThreeUsers();
        $template = $this->pushService->findTemplateByCode('daily_water_drop_remind_dinner');

        if ($to->isNotEmpty()) {
            Notification::send($template, new TaskWatersPaid($to));
            exit;
        }

        Log::info('推送用户为空');
    }

    /**
     * 果树成熟通知
     */
    public function handleMatureWatersNotify()
    {
        $to = $this->getMature();
        $template = $this->pushService->findTemplateByCode('harvest_within_seventy_two_hours');

        if ($to->isNotEmpty()) {
            Notification::send($template, new MatureWatersPaid($to));
            exit;
        }

        Log::info('推送用户为空');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getBagUsers()
    {
        $notify = $this->bagNotifyService->findUsers();
        $users = collect();
        $notify->each(function ($task) use ($users) {
            $result = $this->bagLogService->findBagLogByToday($task->user);
            if ($result['is_sign'] == 0) {
                $users->add($task->user);
            }
        });

        return $users;
    }

    /**
     * 发送红包推送
     */
    public function handleBagNotify()
    {
        $to = $this->getBagUsers();
        $template = $this->pushService->findTemplateByCode('sign_in_tip');

        if ($to->isNotEmpty()) {
            Notification::send($template, new BagReceivePaid($to));
            exit;
        }

        Log::info('推送用户为空');
    }
}
