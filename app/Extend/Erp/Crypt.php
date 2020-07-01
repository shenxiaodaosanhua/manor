<?php
declare (strict_types=1);

namespace App\Extend\Erp;

use App\Exceptions\CryptMessageException;

final class Crypt
{

    /**
     * 加密数据
     *
     * @param $data
     * @return string
     * @throws CryptMessageException
     * @author sunshine
     */
    final public static function encrypt($data)
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
    final public static function decrypt($data)
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
}
