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
     * @var string[]
     */
    protected $config = [
        'app_id' => '',
        'app_key' => '',
    ];

    /**
     * @param string $uri
     * @param array $data
     * @param array $header
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post(string $uri, array $data, array $header = [])
    {
        $requestData = $this->handleRequestData($data, $header);

        $client = new Client();
        $response = $client->request('post', $uri, [
            'headers' => $requestData['header'],
            'form_params' => $requestData['data'],
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(string $uri, array $data, array $header = [])
    {

        $requestData = $this->handleRequestData($data, $header);

        $client = new Client();
        $response = $client->request('get', $uri, [
            'headers' => $requestData['header'],
            'query' => $requestData['data'],
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
     * @param array $data
     * @param array $header
     * @return array
     */
    protected function handleRequestData(array $data, array $header)
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
            'H-APPKEY' => $this->config['app_key'],
            'H-APPID' => $this->config['app_id'],
            'H-VERSION' => '1.0.0',
            'H-SIGN' => $data['sign'],
        ];
        $headers = array_merge($headerOrigin, $header);

        return [
            'data' => $data,
            'header' => $headers,
        ];
    }

    /**
     * 频道选择
     * @param string $channel
     * @return ErpRequestTrait|void
     */
    public function channel(string $channel)
    {
        switch ($channel) {
            case 'manor':
                return $this->handleManorConfig();
            break;
            case 'bag' :
                return $this->handleBagConfig();
            break;
            default:
                throw new HttpException(404, '初始化失败');
        }
    }

    /**
     * 初始化庄园配置
     * @return $this
     */
    protected function handleManorConfig()
    {
        $this->config['app_id'] = config('erp.app_id');
        $this->config['app_key'] = config('erp.app_key');
        return $this;
    }

    /**
     * 初始化红包配置
     * @return $this
     */
    protected function handleBagConfig()
    {
        $this->config['app_id'] = config('erp.hb_app_id');
        $this->config['app_key'] = config('erp.app_key');
        return $this;
    }
}
