<?php


namespace App\Extend;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class Logistics
 * @package App\Extend
 */
class Logistics
{

    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $host;

    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $path;

    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $appCode;

    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $ttl;

    /**
     * Logistics constructor.
     */
    public function __construct()
    {
        $this->host = config('logistics.host');
        $this->path = config('logistics.path');
        $this->appCode = config('logistics.app_code');
        $this->ttl = config('logistics.ttl');
    }

    /**
     * @param string $code
     * @return mixed
     */
    public function getWorkLog(string $code)
    {
        if (empty($code)) {
            throw new HttpException(403, '请输入快递单号');
        }

        if (config('logistics.cache')) {
            return $this->getCache($code);
        }

        return $this->requestLogistics($code);
    }

    /**
     * @param string $code
     * @return mixed
     */
    protected function getCache(string $code)
    {
        return Cache::remember($code, $this->ttl, function () use ($code) {
            return $this->requestLogistics($code);
        });
    }

    /**
     * @param string $code
     * @return mixed
     */
    public function requestLogistics(string $code)
    {
        $response = $this->makeClient();
        $result = $response->request('get', $this->path, [
            'query' => [
                'no' => $code,
            ],
        ]);

        $statusCode = $result->getStatusCode();
        if ($statusCode != 200) {
            throw new HttpException($result->getStatusCode(), '请求失败');
        }

        $content = $result->getBody()->getContents();
        $data = json_decode($content, true);

        if ($data['status'] != 0) {
            throw new HttpException(203, '查询失败');
        }

        return $data;
    }

    /**
     * @return Client
     */
    protected function makeClient()
    {
        return new Client([
            'base_uri' => $this->host,
            'headers' => [
                'Authorization' => 'APPCODE ' . $this->appCode,
            ],
        ]);
    }
}
