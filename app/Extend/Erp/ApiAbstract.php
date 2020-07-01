<?php
declare (strict_types=1);

namespace App\Extend\Erp;

use App\Exceptions\CryptMessageException;
use App\Exceptions\ErpRequestException;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

abstract class ApiAbstract implements ApiInterface
{
    /**
     * 配置参数
     *
     * @var array
     */
    protected $config = [
        'version'    => '1.0.0',
        'app_id'     => '',
        'app_key'    => '',
        'secret_key' => '',
        'host'       => '',
        'api_urls'   => [
            'user_info'  => '/open/v1/user',
            'msg_push'   => '/user/v1/messagePush',
            'msg_site'   => '/user/v1/messageSite',
            'withdrawal' => '/withdrawal/v1/externalWithdrawal'
        ],
        'openssl'    => [
            'key'    => '',
            'iv'     => '',
            'method' => 'AES-128-CBC'
        ]
    ];

    public function __construct()
    {
        $this->config = array_merge($this->config, config('erp'));
    }

    /**
     * 获取配置值
     *
     * @param string $key
     * @return mixed|string
     * @author sunshine
     */
    protected function getConfigValue(string $key)
    {
        return $this->config[$key] ?? '';
    }

    /**
     * 获取api url
     *
     * @param string $key
     * @return mixed|string
     * @author sunshine
     */
    protected function getApiUrl(string $key)
    {
        return $this->config['api_urls'][$key] ?? '';
    }

    /**
     * 生成签名
     *
     * @param array $params
     * @return string
     * @author sunshine
     */
    final public function getSign(array $params): string
    {
        $data = array_filter($params, function ($v) {
            return $v !== '';
        });

        ksort($data);

        $data['secret_key'] = $this->getConfigValue('secret_key');
        $query_tmp = '';

        foreach ($data as $k => $v) {
            $query_tmp .= "{$k}={$v}&";
        }

        return md5(rtrim($query_tmp, '&'));
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $header
     * @param int $timeout
     * @return bool
     * @throws CryptMessageException
     * @throws ErpRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author sunshine
     */
    public function post(string $uri, array $data, array $header = [], int $timeout = 30)
    {
        $time = time();

        $commonData = [
            'lang'         => 'zh-cn',
            'rand_str'     => md5(Str::random(32)),
            'request_time' => $time,
        ];

        $data = array_merge($data, $commonData);

        $sign = $this->getSign($data);

        $data['sign'] = $sign;

        $headerOrigin = [
            'H-REQUEST-TIME' => $time,
            'H-APPKEY'       => $this->getConfigValue('app_key'),
            'H-APPID'        => $this->getConfigValue('app_id'),
            'H-VERSION'      => $this->getConfigValue('version'),
            'H-SIGN'         => $data['sign'],
        ];

        $headers = array_merge($headerOrigin, $header);

        $client = new Client();

        $response = $client->request('post', $uri, [
            'headers'     => $headers,
            'form_params' => $data,
            'timeout'     => $timeout
        ]);

        if ($response->getStatusCode() != 200) {
            throw new ErpRequestException($response->getBody()->getContents(), $response->getStatusCode());
        }

        $result = $response->getBody()->getContents();

        return $this->output($result);
    }

    /**
     * 结果输出
     *
     * @param string $result
     * @return bool
     * @throws ErpRequestException
     * @throws \App\Exceptions\CryptMessageException
     * @author sunshine
     */
    public function output(string $result)
    {
        try{
            $result = Crypt::decrypt($result);
        }catch (CryptMessageException $exception){
            Log::error($exception->getMessage());
            Log::error("epr api request error. return result :".$result);
        }

        $data = json_decode($result, true);

        if ($data['code'] == 20000 && $data['msg'] == '成功') {
            return $data['data'] ?? true;
        }
        throw new ErpRequestException($data['msg'] ?? '');
    }

    public function setAppId($driver='app_id')
    {
        $this->config['app_id'] = config('erp.'.$driver);
    }
}
