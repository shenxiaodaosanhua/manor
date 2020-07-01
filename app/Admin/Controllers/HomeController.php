<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

/**
 * Class HomeController
 * @package App\Admin\Controllers
 */
class HomeController extends Controller
{
    /**
     * @param Content $content
     * @return Content
     * @author Jerry
     */
    public function index(Content $content)
    {
        return $content
            ->title('首页')
            ->description('系统信息')
            ->row(function (Row $row) {

                $row->column(6, function (Column $column) {
                    $column->append(Dashboard::environment());
                });

                $row->column(6, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });
            });
    }
}
