<?php


namespace App\Services;


use App\Events\BehaviorEvent;
use App\Models\BagNotify;
use App\Models\Behavior;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class BagNotifyService
 * @package App\Services
 */
class BagNotifyService
{

    /**
     * 设置用户通知
     * @param User $user
     * @param int $state
     * @return mixed
     */
    public function setUserNotify(User $user, $state = BagNotify::STATE_OFF)
    {
        try {
            $notify = $this->findUserNotify($user);
            $notify->state = $state;
            $notify->save();

            return $notify;
        } catch (ModelNotFoundException $exception) {
            return $this->createUserNotify($user, $state);
        } finally {

            $type = 'bag-sign-notify-on';
            if ($state == BagNotify::STATE_OFF) {
                $type = 'bag-sign-notify-off';
            }

            $data = [
                'user_id' => $user->id,
                'bhv_type' => $type,
                'trace_id' => Behavior::TRACE_BAG,
            ];
            event(new BehaviorEvent($data));
        }
    }

    /**
     * 创建用户通知
     * @param User $user
     * @param int $state
     * @return mixed
     */
    public function createUserNotify(User $user, $state = BagNotify::STATE_OFF)
    {
        return BagNotify::create([
            'user_id' => $user->id,
            'state' => $state,
        ]);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function findUserNotify(User $user)
    {
        return BagNotify::where('user_id', $user->id)
            ->firstOrFail();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findUsers()
    {
        return BagNotify::with('user')
            ->where('state', BagNotify::STATE_ON)
            ->get();
    }
}
