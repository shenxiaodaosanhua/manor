<?php
declare (strict_types=1);


namespace App\Services;


use App\Facades\Food;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FoodValidOrderService
{
    public function isValidOrder($uid,$food_order)
    {
        $res = Food::isValidOrder($uid,$food_order);
        if(!$res) throw new HttpException(401,'不是有效订单');
    }
}
