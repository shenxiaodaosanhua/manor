<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class WaterMiddleware
 * @package App\Http\Middleware
 */
class WaterMiddleware
{
    use ApiResponse;

    /**
     * 请求频率限流
     * @param $request
     * @param Closure $next
     * @return mixed|void
     * @author Jerry
     */
    public function handle($request, Closure $next)
    {
        $userId = $request->user->id;

        $lock = Cache::lock($userId, 1);
        if (! $lock->get()) {
            throw new HttpException(403, '浇水速度太快了，请稍等再来');
        }

        return $next($request);
    }
}
