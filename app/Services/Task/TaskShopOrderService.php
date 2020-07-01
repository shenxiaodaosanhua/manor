<?php


namespace App\Services\Task;


use App\Models\Plant;
use App\Traits\TaskStateTrait;

/**
 * Class TaskShopOrderService
 * @package App\Services\Task
 */
class TaskShopOrderService implements TaskInterface
{
    use TaskStateTrait;

    /**
     * @var string
     */
    protected $taskName = 'shop_order';

    /**
     * @param array $data
     * @param Plant $plant
     * @return string
     */
    public function getDynamic(array $data, Plant $plant)
    {
        return "浏览美生商城，水滴+{$data['water']}";
    }
}
