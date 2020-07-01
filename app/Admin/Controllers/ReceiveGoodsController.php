<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\ReceiveGoods\Fulfill;
use App\Admin\Actions\ReceiveGoods\Ship;
use App\Facades\Logistics;
use App\Models\ReceiveGoods;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class ReceiveGoodsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('奖品')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('奖品')
            ->description('详情')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('admin.edit'))
            ->description(trans('admin.description'))
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header(trans('admin.create'))
            ->description(trans('admin.description'))
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return GridShip
     */
    protected function grid()
    {
        $grid = new Grid(new ReceiveGoods);
        $grid->model()->whereNotIn('state', [ReceiveGoods::STATE_NOT]);

        $grid->column('name', '收货人信息');
        $grid->column('user.nickname', '用户昵称');
        $grid->column('goods.name', '礼品名称');
        $grid->column('tracking_number', '快递单号')->modal(function ($model) {

            $info = $model->logistics ? $model->logistics->content : [];

            if (! $info) {
                return new Table(['时间', '详情'], []);
            }

            $logs = Logistics::getWorkLog($info['number']);
            $list = $logs['result']['list'] ?? '';
            if (! $list) {
                return new Table(['时间', '详情'], []);
            }

            return new Table(['时间', '详情'], $list);
        });

        $grid->column('state', '状态')->display(function ($state) {
            return ReceiveGoods::$state[$state];
        })->label();
        $grid->column('plant.mature_date', '果树收获时间');
        $grid->created_at('奖品领取时间');

        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            $filter->like('user.nickname', '昵称');
            $filter->like('goods.name', '奖品名称');
            $filter->date('plant.mature_date', '果树收获时间');
            $filter->date('created_at', '领取时间');
            $filter->equal('state', '物流状态')->select(ReceiveGoods::$state);

        });

        $grid->actions(function ($actions) {
            $actions->add(new Ship);
            $actions->add(new Fulfill);

            // 去掉删除
            $actions->disableDelete();
            // 去掉编辑
            $actions->disableEdit();
        });
        $grid->disableCreateButton();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(ReceiveGoods::findOrFail($id));

        $show->created_at('领取时间');
        $show->name('收货人名称');
        $show->mobile('收货人手机号码');
        $show->address('收货地址');
        $show->tracking_number('快递单号');

        $show->logistics('物流信息', function ($logistics) use ($show) {
            $logistics->content('物流信息')->logList();

            $logistics->panel()
                ->tools(function ($tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });
        });

        $show->user('用户信息', function ($user) {
            $user->nickname('昵称');
            $user->panel()
                ->tools(function ($tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });
        });
        $show->goods('礼品信息', function ($goods) {
            $goods->name('礼品名称');
            $goods->image('礼品图片')->image();
            $goods->panel()
                ->tools(function ($tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });
        });

        $show->plant('果树信息', function ($plant) {
            $plant->mature_date('果树成熟时间');

            $plant->panel()
                ->tools(function ($tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });
        });

        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableDelete();
            });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ReceiveGoods);

        $form->display('ID');
        $form->text('uid', 'uid');
        $form->text('goods_id', 'goods_id');
        $form->text('tracking_number', '快递单号');
        $form->display(trans('admin.created_at'));
        $form->display(trans('admin.updated_at'));

        return $form;
    }
}
