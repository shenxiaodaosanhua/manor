<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Config\ConfigModel;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

/**
 * Class ConfigController
 * @package App\Admin\Controllers
 */
class ConfigController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('配置')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Edit interface.
     *
     * @param int     $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('配置')
            ->description('编辑')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('配置')
            ->description('添加')
            ->body($this->form());
    }

    /**
     * @param $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('配置')
            ->description('详情')
            ->body(Admin::show(ConfigModel::findOrFail($id), function (Show $show) {
                $show->id('ID');
                $show->name('名称');
                $show->value('配置值');
                $show->description('配置描述');
                $show->updated_at('更新时间');
            }));
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        $grid = new Grid(new ConfigModel());

        $grid->id('ID')->sortable();
        $grid->description('配置描述');
        $grid->name('名称')->display(function ($name) {
            return "<a tabindex=\"0\" class=\"btn btn-xs btn-twitter\" role=\"button\" data-toggle=\"popover\" data-html=true title=\"Usage\" data-content=\"<code>config('$name');</code>\">$name</a>";
        });
        $grid->value('配置值');

        $grid->updated_at('更新时间');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name');
            $filter->like('value');
        });

        $grid->actions(function ($actions) {

            // 去掉删除
            $actions->disableDelete();

            // 去掉查看
            $actions->disableView();
        });

        return $grid;
    }

    /**
     * @return Form
     */
    public function form()
    {
        $form = new Form(new ConfigModel());

        $form->text('name', '名称')->rules('required');
        $form->textarea('value', '值')->rules('required');
        $form->textarea('description', '配置描述');

        $form->saving(function (Form $form) {
            Cache::delete('configs');
        });

        $form->saved(function (Form $form) {
            $configs = ConfigModel::all(['name', 'value']);
            Redis::set('configs', json_encode($configs));
        });

        return $form;
    }
}
