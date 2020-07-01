<?php

namespace App\Http\Controllers\V1;

use App\Events\BehaviorEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\BagNotifyRequest;
use App\Http\Resources\BagLogResource;
use App\Http\Resources\BagNotifyResource;
use App\Http\Resources\BagUserResource;
use App\Models\Behavior;
use App\Services\BagLogService;
use App\Services\BagNotifyService;
use App\Services\BagService;
use App\Services\WithdrawalService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class BagController
 * @package App\Http\Controllers\V1
 */
class BagController extends ApiController
{

    /**
     * 获取用户账户金额信息
     * @param Request $request
     * @param BagService $bagService
     * @return \Illuminate\Http\Response|mixed|void
     */
    public function user(Request $request, BagService $bagService)
    {
        try {
            $user = $bagService->findUserInfoAndUpdateSignNumber($request->user);
            return $this->item($user, BagUserResource::class);
        } catch (ModelNotFoundException $exception) {
            return $this->error('请先授权', 402);
        } finally {
            $data = [
                'user_id' => $request->user->id,
                'bhv_type' => 'bag-home',
                'trace_id' => Behavior::TRACE_BAG,
            ];
            event(new BehaviorEvent($data));
        }
    }

    /**
     * 获取签到列表
     * @param Request $request
     * @param BagLogService $bagLogService
     * @return \Illuminate\Http\Response|void
     */
    public function index(Request $request, BagLogService $bagLogService)
    {
        try {
            $data = $bagLogService->findBagLogs($request->user);
            return $this->json($data);
        } catch (HttpException $exception) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        }
    }

    /**
     * 签到
     * @param Request $request
     * @param BagService $bagService
     * @return \Illuminate\Http\Response|mixed|void
     * @throws \Throwable
     */
    public function signIn(Request $request, BagService $bagService)
    {
        try {
            $result = $bagService->signIn($request->user);
            return $this->item($result, BagLogResource::class);
        } catch (HttpException $exception) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        } finally {
            $data = [
                'user_id' => $request->user->id,
                'bhv_type' => 'bag-sign-in',
                'trace_id' => Behavior::TRACE_BAG,
            ];
            event(new BehaviorEvent($data));
        }
    }

    /**
     * 红包金额提现
     * @param Request $request
     * @param BagService $bagService
     * @return \Illuminate\Http\Response|mixed|void
     * @throws \Throwable
     */
    public function withdraw(Request $request, BagService $bagService)
    {
        try {
            $user = $bagService->withdraw($request->user);
            return $this->item($user, BagUserResource::class);
        } catch (HttpException $exception) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        }
    }

    /**
     * 设置用户通知
     * @param BagNotifyRequest $request
     * @param BagNotifyService $bagNotifyService
     * @return \Illuminate\Http\Response|mixed|void
     */
    public function openNotify(BagNotifyRequest $request, BagNotifyService $bagNotifyService)
    {
        try {
            $state = $request->get('state');
            $notify = $bagNotifyService->setUserNotify($request->user, $state);
            return $this->item($notify, BagNotifyResource::class);
        } catch (HttpException $exception) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        } finally {
            $data = [
                'user_id' => $request->user->id,
                'bhv_type' => 'bag-sign-in',
                'trace_id' => Behavior::TRACE_BAG,
            ];
            event(new BehaviorEvent($data));
        }
    }

    /**
     * 获取通知状态
     * @param Request $request
     * @param BagNotifyService $bagNotifyService
     * @return \Illuminate\Http\Response|mixed|void
     */
    public function getNotify(Request $request, BagNotifyService $bagNotifyService)
    {
        try {
            $notify = $bagNotifyService->findUserNotify($request->user);
            return $this->item($notify, BagNotifyResource::class);
        } catch (ModelNotFoundException $exception) {
            return $this->json([
                'user_id' => $request->user->id,
                'state' => 0,
            ]);
        }
    }
}
