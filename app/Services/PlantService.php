<?php


namespace App\Services;


use App\Exceptions\PlantTimeOutException;
use App\Jobs\PlantPodcast;
use App\Models\Plant;
use App\Models\PlantLog;
use App\Models\ReceiveGoods;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class PlantService
 * @package App\Services
 */
class PlantService
{

    /**
     * 获取我的植物
     * @param User $user
     * @return mixed
     */
    public function getMyPlant(User $user)
    {
        try {
            $plant = $this->findPlantInType($user, [
                Plant::STATE_NOT,
                Plant::STATE_SUCCESS,
                Plant::STATE_SELECT,
            ]);
        } catch (ModelNotFoundException $exception) {
            $plant = $this->createPlant($user->id);
        }

        return $plant;
    }

    /**
     * 获取用户植物状态
     * @param User $user
     * @return int[]
     */
    public function getPlantStatus(User $user)
    {
        $status = [
            'is_new' => 1,
            'plant_state' => Plant::STATE_NOT,
            'waters' => config('first-water-number'),
        ];

        $plantCount = $this->findPlantCount($user);
        if (! $plantCount) {
            return $status;
        }

        $plant = $this->findPlantNewStatus($user);
        if (! $plant) {
            return $status;
        }

        $status['is_new'] = 0;
        $status['plant_state'] = $plant->state;
        $status['waters'] = config('new-receive-waters');

        if ($plant->state >= Plant::STATE_RECEIVE) {
            $status['plant_state'] = -1;
        }

        return $status;
    }

    /**
     * 获取指定用户最新植物状态
     * @param User $user
     * @return mixed
     */
    public function findPlantNewStatus(User $user)
    {
        return Plant::with('receive')
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * 获取指定用户种树总数
     * @param User $user
     * @return mixed
     */
    public function findPlantCount(User $user)
    {
        return Plant::where('user_id', $user->id)
            ->count();
    }

    /**
     * @param int $userId
     * @param int $state
     * @return mixed
     * @author Jerry
     */
    public function findPlantByUserId(int $userId, $state = Plant::STATE_NOT)
    {
        return Plant::where('user_id', $userId)
            ->where('state', $state)
            ->firstOrFail();
    }

    /**
     * 获取多状态一条数据
     * @param User $user
     * @param array $state
     * @return mixed
     */
    public function findPlantInType(User $user, array $state)
    {
        return Plant::where('user_id', $user->id)
            ->whereIn('state', $state)
            ->orderBy('id', 'desc')
            ->firstOrFail();
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function createPlant(int $userId)
    {
        return Plant::create([
            'user_id' => $userId,
        ]);
    }

    /**
     * @param Plant $plant
     * @return Plant
     * @throws PlantTimeOutException
     */
    public function handlePlantTimeOut(Plant $plant)
    {
        if (! in_array($plant->state, [
            Plant::STATE_SUCCESS,
            Plant::STATE_SELECT,
        ])) {
            throw new PlantTimeOutException('当前状态不可设置超时');
        }

        //更新领取状态
        $receive = $plant->receive;
        $receive->state = ReceiveGoods::STATE_TIME_OUT;
        $receive->save();

        //加回库存
        $goods = $plant->receive->goods;
        $goods->increment('stock', 1);
        $goods->save();

        $plant->state = Plant::STATE_TIMEOUT;
        $plant->save();

        PlantLog::create([
            'user_id' => $plant->user_id,
            'plant_id' => $plant->id,
            'water' => 0,
            'type' => PlantLog::LOG_TIME_OUT,
        ]);

        return $plant;
    }

    /**
     * 处理植物成熟
     * @param Plant $plant
     */
    public function plantSuccess(Plant $plant)
    {
        if ($plant->state != Plant::STATE_SUCCESS) {
            throw new HttpException(403, '植物未成熟');
        }

        PlantPodcast::dispatch($plant->id)->delay($plant->time_out_at);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function findPlantById(int $id)
    {
        return Plant::where('id', $id)
            ->first();
    }
}
