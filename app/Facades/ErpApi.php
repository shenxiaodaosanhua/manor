<?php
declare (strict_types=1);


namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Extend\Erp\Api
 * Class ErpApi
 * @package App\Facades
 * @mixin \App\Extend\Erp\Api
 */
class ErpApi extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Extend\Erp\Api::class;
    }
}
