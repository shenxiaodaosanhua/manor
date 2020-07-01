<?php

namespace App\Admin\Controllers;

use App\Models\PushTemplates;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class PushTemplatesController extends Controller
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
            ->header('推送')
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
            ->header('推送')
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
            ->header('推送')
            ->description('编辑')
            ->body($this->editForm()->edit($id));
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
            ->header('推送')
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
        $grid = new Grid(new PushTemplates);

        $grid->id('ID');
        $grid->module_type('模块')->display(function($value){
            return PushTemplates::MODULE_TYPES[$value] ?? '';
        });
        $grid->scene_code('场景code');
        $grid->title('标题');
        //$grid->content('推送文案');
        $grid->scene_desc('推送场景描述');
        $grid->jump_url('跳转链接');
        $grid->push_type('推送方式')->display(function($value){
            return PushTemplates::PUSH_TYPES[$value] ?? '';
        });
        $grid->push_group_type('推送群体')->display(function($value){
            return PushTemplates::PUSH_GROUP_TYPES[$value] ?? '';
        });
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
        $show = new Show(PushTemplates::findOrFail($id));

        $show->module_type('模块')->as(function($value){
            return PushTemplates::MODULE_TYPES[$value] ?? '';
        });
        $show->scene_code('推送场景code');
        $show->title('标题');
        $show->scene_desc('推送场景描述');
        $show->push_type('推送方式')->as(function($value){
            return PushTemplates::PUSH_TYPES[$value] ?? '';
        });
        $show->push_group_type('推送群体')->as(function($value){
            return PushTemplates::PUSH_GROUP_TYPES[$value] ?? '';
        });
        $show->jump_url('跳转链接');
        $show->content('推送文案');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PushTemplates);

        $form->select('module_type', '模块')->required()->options(PushTemplates::MODULE_TYPES);
        $form->text('scene_code', '推送场景code')->required();
        $form->text('scene_desc', '推送场景描述')->required();
        $form->text('title', '标题')->required();
        $form->select('push_type', '推送方式')->required()->options(PushTemplates::PUSH_TYPES)->default(PushTemplates::PUSH_TYPE_SITE_VALUE);
        $form->select('push_group_type', '推送群体')->required()->options(PushTemplates::PUSH_GROUP_TYPES)->default(PushTemplates::PUSH_GROUP_TYPE_SINGLE);
        $form->text('jump_url', '跳转链接');
        $form->textarea('content', '推送文案')->required();

        return $form;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function editForm()
    {
        $form = new Form(new PushTemplates);

        $form->select('module_type', '模块')->options(PushTemplates::MODULE_TYPES);;
        $form->text('title', '标题')->required();
        $form->text('scene_desc', '推送场景描述')->required();
        $form->select('push_type', '推送方式')->required()->options(PushTemplates::PUSH_TYPES);
        $form->select('push_group_type', '推送群体')->required()->options(PushTemplates::PUSH_GROUP_TYPES);
        $form->text('jump_url', '跳转链接');
        $form->textarea('content', '推送文案')->required();

        return $form;
    }
}
