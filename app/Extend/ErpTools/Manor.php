<?php


namespace App\Extend\ErpTools;


use App\Traits\ErpRequestTrait;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class Manor
 * @package App\Extend\ErpTools
 */
class Manor
{

    use ErpRequestTrait;


    public function getUser(string $authCode, string $channel = 'manor')
    {
        if (empty($authCode)) {
            throw new HttpException(403, 'code不存在');
        }

        $header = [
            'H-APPID' => config('erp.app_id'),
        ];
        $url = config('erp.host') . config('erp.api_urls.user_info');
        $data = [
            'code' => $authCode,
        ];

        return $this->channel($channel)->post($url, $data, $header);
    }
}
