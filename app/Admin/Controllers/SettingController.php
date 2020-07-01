<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\Settings\Basic;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Tab;

class SettingController extends Controller
{
    use HasResourceActions;

    public function setting(Content $content)
    {
        return $content
            ->title('玩法设置')
            ->body(Tab::forms([
                'basic' => Basic::class,
            ]));
    }
}
