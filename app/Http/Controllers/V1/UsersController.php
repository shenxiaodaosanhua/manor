<?php
declare (strict_types=1);


namespace App\Http\Controllers\V1;


use App\Http\Requests\LogisticsRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\LogisticsResource;
use App\Http\Resources\MyGoodsResource;
use App\Http\Resources\UserResource;
use App\Services\GoodsService;
use App\Services\UserService;
use Illuminate\Http\Request;

/**
 * Class UsersController
 * @package App\Http\Controllers\V1
 */
class UsersController extends ApiController
{
    /**
     * 获取用户信息
     *
     * @param UserRequest $request
     * @param UserService $UserService
     * @return mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author sunshine
     */
    public function userInfo(UserRequest $request, UserService $UserService)
    {
        try {
            $authCode = $request->get('auth_code');
            $authCode = rawurldecode($authCode);

            if (strpos($authCode, ' ')) {
                $authCode = str_replace(' ', '+', $authCode);
            }

            $user = $UserService->userInfo($authCode);
            return $this->item($user, UserResource::class);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 401);
        } catch(\GuzzleHttp\Exception\GuzzleException $exception){
            return $this->error($exception->getMessage(), 401);
        }
    }

    /**
     * 获取用户信息
     *
     * @param UserRequest $request
     * @param UserService $UserService
     * @return mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author sunshine
     */
    public function hbUserInfo(UserRequest $request, UserService $UserService)
    {
        try {
            $authCode = $request->get('auth_code');
            $authCode = rawurldecode($authCode);

            if (strpos($authCode, ' ')) {
                $authCode = str_replace(' ', '+', $authCode);
            }

            $user = $UserService->userInfo($authCode,'hb');
            return $this->item($user, UserResource::class);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 401);
        } catch(\GuzzleHttp\Exception\GuzzleException $exception){
            return $this->error($exception->getMessage(), 401);
        }
    }

    /**
     * @param Request $request
     * @param GoodsService $goodsService
     * @return \Illuminate\Http\Response
     */
    public function myGift(Request $request, GoodsService $goodsService)
    {
        $data = $goodsService->findMyGoods($request->user);
        return $this->collection($data, MyGoodsResource::class);
    }

    /**
     * @param LogisticsRequest $request
     * @param GoodsService $goodsService
     * @return mixed
     */
    public function myGiftLogistics(LogisticsRequest $request, GoodsService $goodsService)
    {
        $receive = $goodsService->findMyGoodsLogistics($request->get('receive_id'));
        return $this->item($receive, LogisticsResource::class);
    }
}
