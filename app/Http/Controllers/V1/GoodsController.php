<?php

namespace App\Http\Controllers\V1;


use App\Events\BehaviorEvent;
use App\Exceptions\GoodsStockException;
use App\Exceptions\PlantStateException;
use App\Http\Requests\ReceiveGoodsAddressRequest;
use App\Http\Requests\ReceiveGoodsRequest;
use App\Http\Resources\GoodsResource;
use App\Http\Resources\ReceiveGoodsResource;
use App\Models\Behavior;
use App\Models\Plant;
use App\Services\GoodsService;
use App\Services\PlantService;
use App\Services\ReceiveGoodsService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class GoodsController
 * @package App\Http\Controllers\V1
 */
class GoodsController extends ApiController
{
    /**
     * 获取礼品列表
     * @param Request $request
     * @param GoodsService $goodsService
     * @return \Illuminate\Http\Response|void
     */
    public function index(Request $request, GoodsService $goodsService)
    {
        try {
            $goods = $goodsService->findGoodsList();
            return $this->collection($goods, GoodsResource::class);
        } catch (HttpException $exception) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        }
    }

    /**
     * 领取奖品
     * @param ReceiveGoodsRequest $request
     * @param ReceiveGoodsService $receiveGoodsService
     * @param PlantService $plantService
     * @return \Illuminate\Http\Response|mixed|void
     * @throws \Throwable
     */
    public function receive(ReceiveGoodsRequest $request, ReceiveGoodsService $receiveGoodsService, PlantService $plantService)
    {
        try {
            $userId = $request->user->id;
            $goodsId = $request->get('goods_id', 0);

            $plant = $plantService->findPlantByUserId($userId, Plant::STATE_SUCCESS);
            $receiveGoods = $receiveGoodsService->receiveGoods($plant, $goodsId);
            return $this->item($receiveGoods, ReceiveGoodsResource::class);
        } catch (GoodsStockException $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        } catch (ModelNotFoundException $modelNotFoundException) {
            return $this->error('植物还不能领取礼品', 403);
        } catch (PlantStateException $plantStateException) {
            return $this->error($plantStateException->getMessage(), $plantStateException->getCode());
        } catch (HttpException $httpException) {
            return $this->error($httpException->getMessage(), $httpException->getStatusCode());
        }
    }

    /**
     * 更新收货地址
     * @param ReceiveGoodsAddressRequest $request
     * @param ReceiveGoodsService $receiveGoodsService
     * @param PlantService $plantService
     * @return \Illuminate\Http\Response|mixed|void
     */
    public function receiveAddress(ReceiveGoodsAddressRequest $request, ReceiveGoodsService $receiveGoodsService, PlantService $plantService)
    {
        try {
            $params = $request->only([
                'goods_id',
                'name',
                'mobile',
                'address',
            ]);
            $receiveGoods = $receiveGoodsService->receiveGoodsAddress($request->user, $params);
            return $this->item($receiveGoods, ReceiveGoodsResource::class);

        } catch (ModelNotFoundException $modelNotFoundException) {
            return $this->error('植物还不能领取礼品', 403);
        } catch (PlantStateException $plantStateException) {
            return $this->error($plantStateException->getMessage(), $plantStateException->getCode());
        } catch (HttpException $httpException) {
            return $this->error($httpException->getMessage(), $httpException->getStatusCode());
        } finally {
            $behaviorData = [
                'user_id' => $request->user->id,
                'bhv_type' => 'receive_goods',
                'trace_id' => Behavior::TRACE_MANOR,
            ];
            event(new BehaviorEvent($behaviorData));
        }
    }
}
