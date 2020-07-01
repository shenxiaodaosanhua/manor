<?php

namespace App\Admin\Forms\Settings;

use App\Models\PlantTask;
use App\Models\Setting;
use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;

class Basic extends Form
{
    /**
     * The form title.
     *
     * @var string
     */
    public $title = '玩法设置';

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
            $setting = Setting::create([
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
        $states = [
            'on'  => ['value' => 'on', 'text' => '打开', 'color' => 'success'],
            'off' => ['value' => 'off', 'text' => '关闭', 'color' => 'danger'],
        ];



        $this->fieldset('每日可免费领水滴数权重', function (Form $form) use ($states) {
            $form->hidden('today_waters.today_number')->value('today_waters.today_number');
            $form->hidden('today_waters.uri')->value('today_waters.uri');
            $form->text('today_waters.image', '图标');
            $form->text('today_waters.value.10', '10G权重');
            $form->text('today_waters.value.15', '15G权重');
            $form->text('today_waters.value.20', '20G权重');
            $form->switch('today_waters.state', '状态')->states($states);
            $form->text('today_waters.title', '任务名称')->value('today_waters.title');
            $form->textarea('today_waters.desc', '任务描述')->value('today_waters.desc');
        });

        $this->fieldset('每日三餐福袋可领取水滴数权重设置', function (Form $form) use ($states) {
            $form->hidden('today_three_meals.today_number')->value('today_three_meals.today_number');
            $form->hidden('today_three_meals.uri')->value('today_three_meals.uri');
            $form->text('today_three_meals.image', '图标');
            $form->text('today_three_meals.value.30', '30G');
            $form->text('today_three_meals.value.40', '40G');
            $form->text('today_three_meals.value.50', '50G');
            $form->switch('today_three_meals.state', '状态')->states($states);
            $form->text('today_three_meals.title', '任务名称')->value('today_three_meals.title');
            $form->textarea('today_three_meals.desc', '任务描述')->value('today_three_meals.desc');
        });

        $this->fieldset('浏览商城可领取水滴数', function (Form $form) use ($states) {
            $form->text('see_shop_waters.app_id', 'app_id')->value('see_shop_waters.app_id');
            $form->text('see_shop_waters.image', '图标');
            $form->text('see_shop_waters.uri', '浏览地址')->value('see_shop_waters.uri');
            $form->text('see_shop_waters.value', '水滴数');
            $form->text('see_shop_waters.today_number', '每天可领取次数');
            $form->switch('see_shop_waters.state', '状态')->states($states);
            $form->text('see_shop_waters.title', '任务名称')->value('see_shop_waters.title');
            $form->textarea('see_shop_waters.desc', '任务描述')->value('see_shop_waters.desc');
        });

        $this->fieldset('商城下单可领取水滴数', function (Form $form) use ($states) {
            $form->text('shop_order.uri', '浏览地址')->value('shop_order.uri');
            $form->text('shop_order.app_id', 'app_id')->value('shop_order.app_id');
            $form->text('shop_order.image', '图标');
            $form->text('shop_order.value', '水滴数');
            $form->text('shop_order.today_number', '每天可领取次数');
            $form->switch('shop_order.state', '状态')->states($states);
            $form->text('shop_order.title', '任务名称')->value('shop_order.title');
            $form->textarea('shop_order.desc', '任务描述')->value('shop_order.desc');
        });

        $this->fieldset('美食下单可领取水滴数', function (Form $form) use ($states) {
            $form->hidden('food_order.uri')->value('food_order.uri');
            $form->text('food_order.app_id', 'app_id')->value('food_order.app_id');
            $form->text('food_order.image', '图标');
            $form->text('food_order.value', '水滴数');
            $form->text('food_order.today_number', '每天可领取次数');
            $form->switch('food_order.state', '状态')->states($states);
            $form->text('food_order.title', '任务名称')->value('food_order.title');
            $form->textarea('food_order.desc', '任务描述')->value('food_order.desc');
        });

        $this->fieldset('连续签到可领取水滴数', function (Form $form) {
            $form->hidden('today_continuous.today_number')->value('today_continuous.today_number');
            $form->hidden('today_continuous.uri')->value('today_continuous.uri');
            $form->text('today_continuous.image', '图标');
            $form->text('today_continuous.value.1', '第一天');
            $form->text('today_continuous.value.2', '第二天');
            $form->text('today_continuous.value.3', '第三天');
            $form->text('today_continuous.value.4', '第四天');
            $form->text('today_continuous.value.5', '第五天');
            $form->hidden('today_continuous.state')->value('on');
            $form->text('today_continuous.title', '任务名称')->value('today_continuous.title');
            $form->textarea('today_continuous.desc', '任务描述')->value('today_continuous.desc');
        });

        $this->fieldset('开启每日领取红包提醒可领取水滴数', function (Form $form) use ($states) {
            $form->hidden('today_bag_waters.today_number')->value('today_bag_waters.today_number');
            $form->hidden('today_bag_waters.uri')->value('today_bag_waters.uri');
            $form->text('today_bag_waters.image', '图标');
            $form->text('today_bag_waters.value', '水滴数');
            $form->switch('today_bag_waters.state', '状态')->states($states);
            $form->text('today_bag_waters.title', '任务名称')->value('today_bag_waters.title');
            $form->textarea('today_bag_waters.desc', '任务描述')->value('today_bag_waters.desc');
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
        $setting = Setting::orderBy('id', 'desc')->first();
        return  $setting->content ?? config('plant');
    }
}
