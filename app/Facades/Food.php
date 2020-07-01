<?php
declare (strict_types=1);


namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Extend\Food\FoodApi
 * Class ErpApi
 * @package App\Facades
 * @mixin \App\Extend\Food\FoodApi
 */
class Food extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Extend\Food\FoodApi::class;
    }
}
