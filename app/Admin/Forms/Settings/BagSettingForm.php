<?php


namespace App\Admin\Forms\Settings;


use Encore\Admin\Facades\Admin;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;
use App\Models\BagSetting;

class BagSettingForm extends Form
{
    public $title = '金额配置';

    /**
     * Handle the form request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        try {
            BagSetting::create([
                'content' => $request->all(),
            ]);
            admin_success('更新成功');
        } catch (\Exception $exception) {
            admin_error($exception->getMessage());
        }


        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {

        $this->fieldset('前六日红包随机配额区间', function (Form $form) {
            $form->currency('ago_six.min', '最小')->symbol('￥')->help('后一数值须大于前一数值，例：0.01-2.88')->required();
            $form->currency('ago_six.max', '最大')->symbol('￥')->help('后一数值须大于前一数值，例：0.01-2.88')->required();
        });

        $this->fieldset('第七日红包随机配额区间', function (Form $form) {
            $form->currency('to_seven.min', '最小')->symbol('￥')->help('后一数值须大于前一数值，例：0.01-2.88')->required();
            $form->currency('to_seven.max', '最大')->symbol('￥')->help('后一数值须大于前一数值，例：0.01-2.88')->required();
        });

        $this->confirm('确定提交吗？', 'update');
    }

    /**
     * The data of the form.
     *
     * @return array $data
     */
    public function data()
    {
        $setting = BagSetting::orderBy('id', 'desc')->first();
        return  $setting->content ?? config('bag.setting');
    }
}
