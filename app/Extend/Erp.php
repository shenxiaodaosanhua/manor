<?php


namespace App\Extend;


use App\Exceptions\CryptMessageException;

class Erp
{

    /**
     * 加密
     * @param $data
     * @return string
     * @throws CryptMessageException
     * @author Jerry
     * @date 2020/4/22
     */
    public function encrypt($data)
    {
        if (! $data) {
            throw new CryptMessageException('加密失败', 403);
        }

        if (is_array($data)) {
            $data = json_encode($data);
        }

        $encrypt = openssl_encrypt(
            $data,
            config('erp.openssl.method'),
            config('erp.openssl.key'),
            OPENSSL_PKCS1_PADDING,
            config('erp.openssl.iv')
        );

        return base64_encode($encrypt);
    }

    /**
     * 解密
     * @param $data
     * @return false|string
     * @throws CryptMessageException
     * @throws CryptMessageException
     * @author Jerry
     * @date 2020/4/22
     */
    public function decrypt($data)
    {
        if (! $data) {
            throw new CryptMessageException('解密数据不存在', 403);
        }
        $decode = base64_decode($data);

        if (! $decode) {
            throw new CryptMessageException('解密失败', 403);
        }

        $decryptData = openssl_decrypt(
            $decode,
            config('erp.openssl.method'),
            config('erp.openssl.key'),
            OPENSSL_PKCS1_PADDING,
            config('erp.openssl.iv')
        );

        if (! $decryptData) {
            throw new CryptMessageException('解密失败', 403);
        }

        return  $decryptData;
    }

    /**
     * 数组签名
     * @param array $params
     * @return string
     * @author Jerry
     * @date 2020/4/22
     */
    public function signData($params = [])
    {
        $data = array_filter($params, function ($v) {
            return $v !== '';
        });

        ksort($data);

        $data['secret_key'] = config('erp.secret_key');
        $query_tmp = '';

        foreach ($data as $k => $v) {
            $query_tmp .= "{$k}={$v}&";
        }

        return md5(rtrim($query_tmp, '&'));
    }
}
