<?php


namespace App\Admin\Controllers;


use App\Admin\Actions\Limiter\Remove;
use App\Http\Controllers\Controller;
use App\Models\Limiter;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class LimiterController extends Controller
{
    /**
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        $grid   = new Grid(new Limiter());
        $grid->id("ID");
        $grid->key("redis key");
        $grid->ip("IP地址");
        $grid->expire("解禁时间");
        $grid->cnt("请求次数");
        $grid->quickSearch();
        $grid->disableFilter();
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableColumnSelector();
        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableView();
            $actions->disableDelete();
            $actions->add(new Remove);
        });
        return $content
            ->header('访问')
            ->description('列表')
            ->body($grid);
    }
}
