<?php

namespace App\Http\Controllers\V1;

use App\Events\BehaviorEvent;
use App\Exceptions\TaskDateBetweenException;
use App\Exceptions\TaskDriveException;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Behavior;
use App\Services\PlantService;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TaskController
 * @package App\Http\Controllers\V1
 */
class TaskController extends ApiController
{
    /**
     * 获取用户任务列表
     * @param Request $request
     * @param PlantService $plantService
     * @param TaskService $taskService
     * @return \Illuminate\Http\Response
     * @throws TaskDriveException
     */
    public function index(Request $request, PlantService $plantService, TaskService $taskService)
    {
        try {
            $plant = $plantService->findPlantByUserId($request->user->id);
            $tasks = $taskService->findTasks($plant);
            return $this->json($tasks);
        } catch (ModelNotFoundException $exception) {
            return $this->error('还未种植植物', 403);
        } finally {
            $data = [
                'user_id' => $request->user->id,
                'bhv_type' => 'task',
                'trace_id' => Behavior::TRACE_MANOR,
            ];
            event(new BehaviorEvent($data));
        }
    }

    /**
     * 完成任务
     * @param Request $request
     * @param TaskService $taskService
     * @param PlantService $plantService
     * @return \Illuminate\Http\Response|mixed|void
     */
    public function receive(Request $request, TaskService $taskService, PlantService $plantService)
    {
        $taskName = $request->get('task_name');

        try {
            $plant = $plantService->findPlantByUserId($request->user->id);
            $task = $taskService->filterTask($taskName)->drive($taskName)->achieve($plant);
            return $this->item($task, TaskResource::class);
        } catch (TaskDriveException $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        } catch (TaskDateBetweenException $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        } catch (HttpException $exception) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        }
    }

    /**
     * 获取签到列表
     * @param Request $request
     * @param TaskService $taskService
     * @param PlantService $plantService
     * @return \Illuminate\Http\Response|void
     * @throws TaskDriveException
     */
    public function signInList(Request $request, TaskService $taskService, PlantService $plantService)
    {
        try {
            $plant = $plantService->findPlantByUserId($request->user->id);
            $data = $taskService->drive('today_continuous')->getState($plant);
            return $this->json($data);
        } catch (HttpException $exception) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        } catch (ModelNotFoundException $exception) {
            return $this->error('还未种植植物', 403);
        }
    }

    /**
     * 获取任务是否可领取状态
     * @param Request $request
     * @param TaskService $taskService
     * @param PlantService $plantService
     * @return \Illuminate\Http\Response|void
     * @throws TaskDriveException
     */
    public function getTaskIsReceive(Request $request, TaskService $taskService, PlantService $plantService)
    {
        try {
            $plant = $plantService->findPlantByUserId($request->user->id);
            $status = $taskService->getTaskIsReceive($plant);
            return $this->json($status);
        } catch (HttpException $exception) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        }
    }
}
