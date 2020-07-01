<?php
declare (strict_types=1);

namespace App\Extend\Erp;

/**
 * Interface ApiInterface
 * @package App\Services\Erp
 */
interface ApiInterface
{
    public function output(string $result);

    public function getSign(array $params) : string;

    public function post(string $uri, array $data, array $header = [], int $timeout = 30);
}
