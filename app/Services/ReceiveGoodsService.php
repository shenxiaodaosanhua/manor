<?php


namespace App\Services;


use App\Events\BehaviorEvent;
use App\Exceptions\GoodsStockException;
use App\Exceptions\PlantStateException;
use App\Models\Behavior;
use App\Models\Goods;
use App\Models\Plant;
use App\Models\PlantLog;
use App\Models\ReceiveGoods;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ReceiveGoodsService
 * @package App\Services
 */
class ReceiveGoodsService
{

    /**
     * @var GoodsService
     */
    protected $goodsService;

    protected $plantLogService;

    /**
     * ReceiveGoodsService constructor.
     * @param GoodsService $goodsService
     * @param PlantLogService $plantLogService
     */
    public function __construct(GoodsService $goodsService, PlantLogService $plantLogService)
    {
        $this->goodsService = $goodsService;
        $this->plantLogService = $plantLogService;
    }

    /**
     * 选择礼品
     * @param Plant $plant
     * @param int $goodsId
     * @return mixed
     * @throws \Throwable
     */
    public function receiveGoods(Plant $plant, int $goodsId)
    {
        return DB::transaction(function () use ($goodsId, $plant) {
            if ($plant->state != Plant::STATE_SUCCESS) {
                throw new PlantStateException('当前植物还未能领取礼品', 403);
            }

            $goods = $this->goodsService->findGoodsByIdAndState($goodsId, Goods::STATE_ON);
            if ($goods->stock == 0) {
                throw new GoodsStockException('库存不足', 422);
            }

            $receiveGoods = ReceiveGoods::create([
                'user_id' => $plant->user_id,
                'plant_id' => $plant->id,
                'goods_id' => $goods->id,
                'state' => ReceiveGoods::STATE_SELECT,
            ]);

            $plant->state = Plant::STATE_SELECT;
            $plant->save();

            $goods->decrement('stock', 1);

            return $receiveGoods;
        });

    }

    /**
     * 更新收货地址
     * @param User $user
     * @param array $params
     * @return mixed
     */
    public function receiveGoodsAddress(User $user, array $params)
    {
        $receive = ReceiveGoods::with(['goods', 'plant'])
            ->where('user_id', $user->id)
            ->where('goods_id', $params['goods_id'])
            ->orderByDesc('id')
            ->firstOrFail();

        if ($receive->state == ReceiveGoods::STATE_ORDER) {
            throw new HttpException(403, '已填写地址');
        }

        $receive->name = $params['name'];
        $receive->mobile = $params['mobile'];
        $receive->address = $params['address'];
        $receive->state = ReceiveGoods::STATE_ORDER;
        $receive->save();

        $receive->plant->state = Plant::STATE_RECEIVE;
        $receive->plant->save();

        $this->plantLogService->createPlantLog([
            'user_id' => $receive->user_id,
            'plant_id' => $receive->plant_id,
            'type' => PlantLog::LOG_RECEIVE,
            'water' => 0,
        ]);

        $bhvValue = $receive->created_at->diffInDays($receive->plant->created_at);
        $behaviorData = [
            'user_id' => $receive->user_id,
            'bhv_type' => 'receive_goods_time',
            'bhv_value' => $bhvValue,
            'trace_id' => Behavior::TRACE_MANOR,
        ];
        event(new BehaviorEvent($behaviorData));

        return $receive;
    }
}
