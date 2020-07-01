<?php
declare (strict_types=1);


namespace App\Services;


use App\Facades\ErpApi;

class WithdrawalService
{

    /**
     * 提现
     * @param string $user_id
     * @param float $money
     * @param string $order_sn
     * @param string $remark
     * @param string $type
     * @return bool
     * @throws \App\Exceptions\CryptMessageException
     * @throws \App\Exceptions\ErpRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author sunshine
     */
    public function withdrawal(string $user_id, float $money, string $order_sn, string $remark = '红包签到提现', string $type = 'score')
    {
        return ErpApi::withdrawal($user_id, $money, $order_sn, $remark, $type);
    }
}
