<?php

namespace App\Admin\Controllers;

use App\Models\Goods;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GoodsController extends Controller
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
            ->header('礼品')
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
            ->header('礼品')
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
            ->header('礼品')
            ->description('编辑')
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
            ->header('礼品')
            ->description('创建')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Goods);

        $grid->id('序号');
        $grid->name('商品名称');
        $grid->seller_name('卖家');
        $grid->column('stock', '商品库存')->label();
        $grid->image('商品图片')->image();

        $states = [
            'on'  => ['value' => 1, 'text' => '已上架', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => '已下架', 'color' => 'default'],
        ];
        $grid->column('state', '商品状态')->switch($states);
        $grid->updated_at(trans('admin.created_at'));

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
        $show = new Show(Goods::findOrFail($id));

        $show->name('商品名称');
        $show->desc('描述');
        $show->stock('商品库存');
        $show->seller_name('卖家');
        $show->image('商品图片')->image();
        $show->created_at(trans('admin.created_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Goods);

        $form->text('name', '商品名称')->rules('required|max:12', [
            'required' => '请输入标题',
            'max' => '商品名称为1-12个文字',
        ]);
        $form->text('seller_name', '卖家')->required();
        $form->image('image', '商品图片')->uniqueName()->required();
        $form->number('stock', '商品库存')->min(0)->required();

        $states = [
            'on'  => ['value' => Goods::STATE_ON, 'text' => Goods::$state[Goods::STATE_ON], 'color' => 'primary'],
            'off' => ['value' => Goods::STATE_OFF, 'text' => Goods::$state[Goods::STATE_OFF], 'color' => 'default'],
        ];
        $form->switch('state', '状态')->states($states)->default(0);

        $form->textarea('desc', '描述')->rules('max:30', [
            'max' => '最多30个字符串',
        ]);

        return $form;
    }
}
