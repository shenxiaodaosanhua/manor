<?php

namespace App\Http\Middleware;

use App\Services\UserService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class UidToUser
 * @package App\Http\Middleware
 */
class UidToUser
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return mixed|void
     * @author Jerry
     */
    public function handle(Request $request, Closure $next)
    {
        $openId = $request->header('authorization');

        if (! $openId) {
            throw new HttpException(401, '非法请求');
        }

//        缓存会员信息1小时
        if (config('app.debug')) {
            $user = $this->userService->findUserByOpenId($openId);
        } else {
            $user = Cache::remember("user:{$openId}", Carbon::now()->addHours(1), function () use ($openId) {
                return $this->userService->findUserByOpenId($openId);
            });
        }

        $request->offsetSet('user', $user);

        return $next($request);
    }
}
