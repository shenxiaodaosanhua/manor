<?php

namespace App\Admin\Actions\ReceiveGoods;

use App\Models\ReceiveGoods;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class Ship
 * @package App\Admin\Actions\ReceiveGoods
 */
class Ship extends RowAction
{
    /**
     * @var string
     */
    public $name = '发货';

    /**
     * @param Model $model
     * @param Request $request
     * @return \Encore\Admin\Actions\Response
     */
    public function handle(Model $model, Request $request)
    {
        if ($model->state != ReceiveGoods::STATE_ORDER) {
            return $this->response()->error('当前订单状态无法操作');
        }

        $no = $request->get('tracking_number');
        $model->tracking_number = $no;
        $model->state = ReceiveGoods::STATE_SHIP;
        $model->save();

        return $this->response()->success('发货成功')->refresh();
    }

    /**
     * 发货表单
     */
    public function form()
    {
        $this->text('tracking_number', '快递单号')->required();
    }

}
