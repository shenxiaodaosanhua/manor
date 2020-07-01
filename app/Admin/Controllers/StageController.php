<?php

namespace App\Admin\Controllers;

use App\Models\Stage;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class StageController extends Controller
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
            ->header('阶段')
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
            ->header('阶段')
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
            ->header('阶段')
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
            ->header('阶段')
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
        $grid = new Grid(new Stage);
        $grid->model()->orderBy('stage', 'desc');

        $grid->name('阶段名称');
        $grid->stage('阶段');
        $grid->number('升级条件');
        $grid->column('waters', '可领取水滴奖励');
        $grid->column('is_last', '是否最后阶段')->display(function ($stage) {
            return Stage::$is_last[$stage];
        })->label();

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
        $show = new Show(Stage::findOrFail($id));

        $show->name('名称');
        $show->stage('阶段');
        $show->number('升级下阶段次数');
        $show->waters('可领取水滴奖励');
        $show->is_last('是否最后阶段');
        $show->created_at(trans('admin.created_at'));
        $show->updated_at(trans('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Stage);

        $form->text('name', '名称');
        $form->number('stage', '阶段')->required();
        $form->number('number', '升级下阶段次数')->required();
        $form->number('waters', '可领取水滴奖励');
        $form->switch('is_last', '是否最后阶段')->states(Stage::$is_last);

        return $form;
    }
}
