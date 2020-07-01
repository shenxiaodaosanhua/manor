<?php


namespace App\Http\Middleware;


use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

class LimiterMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        try {
            $ip = !empty($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] :
                (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '');
//            $ip = $ip ? $ip : $request->ip();
            if ($ip) {
                $key = sprintf('%s%s', config('limiter.init_key'), $ip);
                $disabled_key = sprintf('%s%s', config('limiter.disabled_key'), $ip);
                if (Redis::exists($disabled_key)) {
                    $cnt    = Redis::incr($disabled_key);
                    return (new Response([
                        'message' => '访问次数过多，请稍后再试', 'statusCode' => 401, 'data' => ['cnt' => $cnt, 'ttl' => Redis::ttl($disabled_key)]
                    ], 401));
                }
                $cnt = Redis::incr($key);
                if ($cnt == 1) {
                    Redis::expire($key, config('limiter.init_sec'));
                }

                if ($cnt >= config('limiter.max')) {
                    Redis::setex($disabled_key, config('limiter.disabled_sec'), $cnt);
                }
            }
        } catch (\Exception $exception) {
//            var_dump($exception->__toString());
        }



        return $next($request);
    }
}
