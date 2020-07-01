<?php


namespace App\Traits;


use App\Facades\Crypt;
use GuzzleHttp\Client;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Str;

/**
 * Trait ErpRequestTrait
 * @package App\Traits
 */
trait ErpRequestTrait
{
    /**
     * 发送erp POST请求
     * @param string $uri
     * @param array $data
     * @param array $header
     * @return mixed
     */
    public function post(string $uri, array $data, array $header = [])
    {
        $time = time();

        $commonData = [
            'lang'         => 'zh-cn',
            'rand_str'     => md5(Str::random(32)),
            'request_time' => $time,
        ];

        $data = array_merge($data, $commonData);
        $data['sign'] = Crypt::signData($data);

        $headerOrigin = [
            'H-REQUEST-TIME' => $time,
            'H-APPKEY' => config('erp.app_key'),
            'H-APPID' => config('erp.app_id'),
            'H-VERSION' => '1.0.0',
            'H-SIGN' => $data['sign'],
        ];
        $headers = array_merge($headerOrigin, $header);

        $client = new Client();
        $response = $client->request('post', $uri, [
            'headers' => $headers,
            'form_params' => $data,
        ]);

        if ($response->getStatusCode() != 200) {
            throw new HttpException($response->getBody()->getContents(), $response->getStatusCode());
        }

        $content = $response->getBody()->getContents();
        $json = Crypt::decrypt($content);
        $data = json_decode($json, true);

        if ($data['code'] != 20000) {
            throw new HttpException($data['msg'], 502);
        }

        return $data;
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $header
     * @return mixed
     */
    public function get(string $uri, array $data, array $header = [])
    {
        $time = time();

        $commonData = [
            'lang'         => 'zh-cn',
            'rand_str'     => md5(Str::random(32)),
            'request_time' => $time,
        ];

        $data = array_merge($data, $commonData);
        $data['sign'] = Crypt::signData($data);

        $headerOrigin = [
            'H-REQUEST-TIME' => $time,
            'H-APPKEY' => config('erp.app_key'),
            'H-APPID' => config('erp.app_id'),
            'H-VERSION' => '1.0.0',
            'H-SIGN' => $data['sign'],
        ];
        $headers = array_merge($headerOrigin, $header);

        $client = new Client();
        $response = $client->request('get', $uri, [
            'headers' => $headers,
            'query' => $data,
        ]);

        if ($response->getStatusCode() != 200) {
            throw new HttpException($response->getBody()->getContents(), $response->getStatusCode());
        }

        $content = $response->getBody()->getContents();
        $json = Crypt::decrypt($content);
        $data = json_decode($json, true);

        if ($data['code'] != 20000) {
            throw new HttpException($data['msg'], 502);
        }

        return $data;
    }
}
