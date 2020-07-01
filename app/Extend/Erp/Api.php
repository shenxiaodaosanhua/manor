<?php
declare (strict_types=1);


namespace App\Extend\Erp;

use App\Exceptions\ErpRequestException;
use Illuminate\Support\Facades\Log;

class Api extends ApiAbstract
{
    /**
     * @param $code
     * @return array|bool
     * @throws ErpRequestException
     * @throws \App\Exceptions\CryptMessageException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author sunshine
     */
    private function getUserInfoByCode($code, $channer = 'manor')
    {
        $data = [
            'code' => $code
        ];

        $uri = $this->getConfigValue('host') . $this->getApiUrl('user_info');

        if ($channer == 'hb') {
            $app_id = $this->getConfigValue('hb_app_id');
        } else {
            $app_id = $this->getConfigValue('app_id');
        }

        $header = [
            'H-APPID' => $app_id,
        ];
        return $this->post($uri, $data, $header);
    }

    /**
     * 授权code获取用户信息
     *
     * @param string $authCode
     * @return mixed
     * @throws ErpRequestException
     * @throws \App\Exceptions\CryptMessageException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author sunshine
     */
    public function userInfo(string $authCode, $channel = 'manor')
    {
        if (config('app.debug') && ($authCode == '8WOgty+4OjuxdKlZycsUpQ==')) {
            return [
                'openid'  => 'dzn3cri5gzOYnA8Wy5B15m0vVtHSiU/kKm+n+0qz7oZstYzAkvwVxB8Fxpi7AZsi',
                'nick'    => 'jerry',
                'user_id' => 469,
                'avatar'  => '',
            ];
        }

        $originCode = Crypt::decrypt($authCode);

        $data = $this->getUserInfoByCode($originCode, $channel);

        return [
            'openid'  => Crypt::encrypt($data['openid']),
            'nick'    => $data['nick'],
            'user_id' => $data['userid'],
            'avatar'  => $data['avatar'],
        ];
    }

    /**
     * 发送站内信
     * @param array $to
     * @param string $title
     * @param string $content
     * @param $url_type
     * @param int $news_type
     * @return bool
     * @throws ErpRequestException
     * @throws \App\Exceptions\CryptMessageException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author sunshine
     */
    public function sendSiteMsg(array $to, string $title, string $content, $url_type, $news_type = 81)
    {
        $link = [
            'appid'    => $this->getConfigValue('app_id'),
            'url_type' => $url_type
        ];

        $to_users = array_map(function ($val) {
            return Crypt::decrypt($val);
        }, array_unique($to));
        $requestParams = [
            'uid'     => implode($to_users, ','),
            'title'   => $title,
            'type'    => $news_type,
            'content' => $content,
            'link'    => json_encode($link)
        ];
        Log::info('erp-push:'.var_export($requestParams,true));
        $uri = $this->getConfigValue('host') . $this->getApiUrl('msg_site');

        return $this->post($uri, $requestParams);
    }

    /**
     * push推送
     *
     * @param array $to
     * @param string $content
     * @param string $title
     * @param  $url_type
     * @param  $news_type
     * @param array $data
     * @return bool
     * @throws ErpRequestException
     * @throws \App\Exceptions\CryptMessageException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author sunshine
     */
    public function sendPush(array $to, string $title, string $content, $url_type = '', $news_type = 81, $data = [])
    {
        $behavior = [1, $url_type];
        $custom_data = [
            'appid'    => $this->getConfigValue('app_id'),
            'url_type' => $url_type
        ];

        Log::info('erp-push:'.var_export($to,true));

        $to_users = array_map(function ($val) {
            return Crypt::decrypt($val);
        }, array_unique($to));

        $requestParams = [
            'title'      => $title,
            'uid'        => implode($to_users, ','),
            'content'    => $content,
            'behavior'   => json_encode($behavior),
            'newsType'   => $news_type,
            'customData' => json_encode($custom_data),
            'isDialog'   => $data['is_dialog'] ?? 0,
            'isShortMes' => $data['is_short_mes'] ?? 0,
        ];

        Log::info('erp-push:'.var_export($requestParams,true));

        $uri = $this->getConfigValue('host') . $this->getApiUrl('msg_push');

        return $this->post($uri, $requestParams);
    }

    /**
     * 提现
     *
     * @param string $user_id
     * @param float $money
     * @param string $order_sn
     * @param string $remark
     * @param string $type
     * @return bool
     * @throws ErpRequestException
     * @throws \App\Exceptions\CryptMessageException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author sunshine
     */
    public function withdrawal(string $user_id, float $money, string $order_sn, string $remark = '红包签到提现', string $type = 'score')
    {
        $requestParams = [
            'channel'    => 'manor',
            'user_id'    => Crypt::decrypt($user_id),
            'money'      => $money,
            'remark'     => $remark,
            'type'       => $type,
            'trade_type' => 214,
            'order_sn'   => $order_sn
        ];

        $uri = $this->getConfigValue('host') . $this->getApiUrl('withdrawal');

        //红包
        $header = [
            'H-APPID' => $this->getConfigValue('hb_app_id'),
        ];

        return $this->post($uri, $requestParams,$header);
    }
}
