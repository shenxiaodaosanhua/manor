<?php

namespace App\Http\Controllers\V1;

use App\Extend\Erp\Crypt;
use App\Models\NotifyLog;
use App\Services\FoodValidOrderService;
use App\Services\PlantService;
use App\Services\TaskService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class NotifyController
 * @package App\Http\Controllers\V1
 */
class NotifyController extends ApiController
{
    /**
     * @param Request $request
     * @param UserService $userService
     * @param PlantService $plantService
     * @param TaskService $taskService
     * @param FoodValidOrderService $foodValidOrderService
     * @return string
     */
    public function order(Request $request, UserService $userService, PlantService $plantService, TaskService $taskService, FoodValidOrderService $foodValidOrderService)
    {
        try {
            $user_id = $request->get('erp_uid');
            $food_order_sn = $request->get('order_id');
            $openid = $request->get('open_id');

            //回调通知记录
            NotifyLog::create([
                'content' => [
                    'user_id'       => $user_id,
                    'food_order_sn' => $food_order_sn,
                    'openid'        => $openid,
                ],
            ]);

            //美食有效订单接口
            $foodValidOrderService->isValidOrder($openid, $food_order_sn);

            $user = $userService->findUserByUid($user_id);
            $plant = $plantService->findPlantByUserId($user->id);
            //完成任务
            $taskService->drive('food_order')->achieve($plant);

            return 'success';
        } catch (\Exception $exception) {
            Log::error("FOOD_API_NOTICE:".$exception->getMessage());
            return 'fail';
        }
    }
}
