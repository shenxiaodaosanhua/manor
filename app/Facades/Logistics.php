<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class Logistics extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Extend\Logistics::class;
    }
}
