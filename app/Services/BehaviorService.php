<?php


namespace App\Services;


use App\Models\Behavior;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class BehaviorService
 * @package App\Services
 */
class BehaviorService
{
    /**
     * @param array $data
     * @return mixed
     */
    public function behaviorStore(array $data)
    {
        return Behavior::create($data);
    }

    /**
     * @param $name
     * @return $this
     */
    public function isApiStore($name)
    {
        $bhvType = [
            'stay',
            'sig-goods-redirect',
            'bag-stay',
        ];

        if (! in_array($name, $bhvType)) {
            throw new HttpException(403, '非法请求');
        }

        return $this;
    }

    /**
     * @param User $user
     * @param string $name
     * @param string $value
     * @return mixed
     */
    public function apiInsert(User $user, string $name, string $value)
    {
        $data = [
            'user_id' => $user->id,
            'bhv_type' => $name,
            'bhv_value' => $value,
            'trace_id' => Behavior::TRACE_MANOR,
        ];
        return $this->behaviorStore($data);
    }
}
