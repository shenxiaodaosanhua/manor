<?php


namespace App\Services;


use App\Facades\Logistics;
use App\Models\Goods;
use App\Models\ReceiveGoods;
use App\Models\ReceiveGoodsLogistics;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class GoodsService
 * @package App\Services
 */
class GoodsService
{
    /**
     * 获取商品列表
     * @return mixed
     */
    public function findGoodsList()
    {
        return Goods::where('state', Goods::STATE_ON)
            ->get();
    }

    /**
     * 获取指定id指定状态商品
     * @param int $goodsId
     * @param int $state
     * @return mixed
     */
    public function findGoodsByIdAndState($goodsId = 0, $state = Goods::STATE_ON)
    {
        return Goods::where('state', $state)
            ->where('id', $goodsId)
            ->firstOrFail();
    }

    /**
     * 获取我的礼品
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findMyGoods(User $user)
    {
        return ReceiveGoods::with('goods')
            ->where('user_id', $user->id)
            ->whereNotIn('state', [ReceiveGoods::STATE_NOT])
            ->get();
    }

    /**
     * @param int $receiveId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function findReceiveGoodsById($receiveId = 0)
    {
        return ReceiveGoods::with('logistics')
            ->where('id', $receiveId)
            ->firstOrFail();
    }

    /**
     * @param int $receiveId
     * @return ReceiveGoods|\Illuminate\Support\Collection
     */
    public function findMyGoodsLogistics($receiveId = 0)
    {
        $receive = $this->findReceiveGoodsById($receiveId);

        if ($receive->state != 3) {
            throw new HttpException(403, '暂无物流信息');
        }

        return $this->getLogistics($receive);
    }

    /**
     * @param ReceiveGoods $receiveGoods
     * @return ReceiveGoods|\Illuminate\Support\Collection
     */
    protected function getLogistics(ReceiveGoods $receiveGoods)
    {
        if (! $receiveGoods->tracking_number) {
            return collect();
        }

        $logisticsCollect = Logistics::getWorkLog($receiveGoods->tracking_number);

        if (! $receiveGoods->logistics) {
            $logistics = ReceiveGoodsLogistics::create([
                'content' => $logisticsCollect['result'],
                'status' => $logisticsCollect['status'],
            ]);

            $receiveGoods->logistics()->save($logistics);
            return $receiveGoods;
        }

        $logistics = $receiveGoods->logistics;
        $logistics->content = $logisticsCollect['result'];
        $logistics->status = $logisticsCollect['status'];
        $logistics->save();

        return $receiveGoods;
    }
}
