<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Redis;

class Limiter extends Model
{
    public function paginate()
    {
        $params = \Request::all();
        $ip = $params['__search__'] ?? '';

        $redis  = Redis::getFacadeRoot();
        $keys   = $redis->keys(sprintf('%s%s*', config('limiter.disabled_key'), $ip));
        $data   = [];

        foreach ($keys as $k => $v) {
            $key = substr($v, 17);
            $ip  = substr($v, 34);
            $ttl = $redis->ttl($key);
            $data[] = [
                'id'    => ip2long($ip),
                'key'   => $key,
                'ip'    => $ip,
                'expire'=> $this->formatTtl($ttl),
                'cnt'   => $redis->get($key),
            ];
        }
        //  返回一个对象
        $collection = static::hydrate($data);
        $paginator = new LengthAwarePaginator($collection, count($data), 100);

        $paginator->setPath(url()->current());

        return $paginator;
    }

    /**
     * 必须存在此方法
     *
     * @param array|string $relations
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function with($relations)
    {
        return new static;
    }

    protected function formatTtl($ttl)
    {
        $house  = floor($ttl / 3600);
        $min    = floor(($ttl - $house * 3600) / 60);
        return "{$house} 小时 {$min} 分钟后自动解禁";
    }
}
