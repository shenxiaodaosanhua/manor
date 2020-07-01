<?php


namespace App\Services;


use App\Exceptions\AuthNotException;
use App\Exceptions\ErpRequestException;
use App\Facades\ErpApi;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{
    /**
     * @param int $uid
     * @return mixed
     * @throws AuthNotException
     */
    public function getUserByUid($uid = 0)
    {
        return $this->findUserByUid($uid);
    }

    /**
     * 通过openid获取用户信息
     * @param string $openId
     * @return mixed
     * @throws AuthNotException
     */
    public function findUserByOpenId($openId = '')
    {
        $user = User::where('openid', $openId)
                    ->first();

        if (! $user) {
            throw new AuthNotException('请先授权', 402);
        }

        return $user;
    }

    /**
     * @param int $uid
     * @return mixed
     * @throws AuthNotException
     */
    public function findUserByUid($uid = 0)
    {
        $user = User::where('uid', $uid)->first();
        if (! $user) {
            throw new AuthNotException('请先授权', 402);
        }

        return  $user;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createUser(array $data)
    {
        return User::create($data);
    }

    /**
     * 授权code获取用户信息
     *
     * @param string $authCode
     * @param string $channel
     * @return mixed
     * @throws ErpRequestException
     * @throws \App\Exceptions\CryptMessageException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author sunshine
     */
    public function userInfo(string $authCode,string $channel = 'manor')
    {

        if (empty($authCode)) {
            throw new HttpException(403, '请求错误');
        }

        $data = ErpApi::userInfo($authCode,$channel);

        try {
            $user = $this->findUserByUid($data['user_id']);
        } catch (AuthNotException $exception) {
            $user = $this->createUser([
                'uid' => $data['user_id'],
                'openid' => $data['openid'],
                'avatar' => $data['avatar'],
                'nickname' => $data['nick'],
            ]);
        }

        return $user;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findUsers()
    {
        return User::with([
            'bag' => function($query) {
                $query->where('type', 1);
            }
        ])->get();
    }

}
