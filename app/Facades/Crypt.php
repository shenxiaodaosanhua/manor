<?php


namespace App\Facades;


use App\Extend\Erp;
use Illuminate\Support\Facades\Facade;

class Crypt extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Erp::class;
    }
}
