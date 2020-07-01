<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\Help\BagHelpForm;
use App\Admin\Forms\Settings\BagSettingForm;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Layout\Content;

class BagSettingController extends Controller
{
    use HasResourceActions;

    public function index(Content $content)
    {
        return $content
            ->title('红包设置')
            ->body(Tab::forms([
                'setting' => BagSettingForm::class,
                'help' => BagHelpForm::class,
            ]));
    }
}
