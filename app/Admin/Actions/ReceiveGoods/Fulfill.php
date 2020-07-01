<?php

namespace App\Admin\Actions\ReceiveGoods;

use App\Models\ReceiveGoods;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Fulfill
 * @package App\Admin\Actions\ReceiveGoods
 */
class Fulfill extends RowAction
{
    /**
     * @var string
     */
    public $name = '完成订单';

    /**
     * @param Model $model
     * @return \Encore\Admin\Actions\Response
     */
    public function handle(Model $model)
    {
        if ($model->state != ReceiveGoods::STATE_SHIP) {
            return $this->response()->error('当前订单状态无法操作');
        }

        $model->state = ReceiveGoods::STATE_FULFILL;
        $model->save();

        return $this->response()->success('订单操作成功')->refresh();
    }

    /**
     * 弹框确认
     */
    public function dialog()
    {
        $this->confirm('确定订单已完成？');
    }

}
