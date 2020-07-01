<?php

namespace App\Admin\Controllers;

use App\Models\Behavior;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class BehaviorController extends Controller
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
            ->header(trans('admin.index'))
            ->description(trans('admin.description'))
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
            ->header(trans('admin.detail'))
            ->description(trans('admin.description'))
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
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Behavior);

        $grid->id('ID');
        $grid->bhv_type('bhv_type');
        $grid->bhv_value('bhv_value');
        $grid->platform('platform');
        $grid->app_version('app_version');
        $grid->user_id('user_id');
        $grid->bhv_desc('bhv_desc');
        $grid->created_at(trans('admin.created_at'));
        $grid->updated_at(trans('admin.updated_at'));

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
        $show = new Show(Behavior::findOrFail($id));

        $show->id('ID');
        $show->bhv_type('bhv_type');
        $show->bhv_value('bhv_value');
        $show->platform('platform');
        $show->app_version('app_version');
        $show->user_id('user_id');
        $show->bhv_desc('bhv_desc');
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
        $form = new Form(new Behavior);

        $form->display('ID');
        $form->text('bhv_type', 'bhv_type');
        $form->text('bhv_value', 'bhv_value');
        $form->text('platform', 'platform');
        $form->text('app_version', 'app_version');
        $form->text('user_id', 'user_id');
        $form->text('bhv_desc', 'bhv_desc');
        $form->display(trans('admin.created_at'));
        $form->display(trans('admin.updated_at'));

        return $form;
    }
}
