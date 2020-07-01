<?php

use Illuminate\Database\Seeder;

class BehaviorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => '庄园首页',
                'desc' => '访问美生庄园首页',
                'bhv_key' => 'home',
            ],
            [
                'name' => '浏览时长',
                'desc' => '用户单次访问浏览时长',
                'bhv_key' => 'stay',
            ],
            [
                'name' => '浇水',
                'desc' => '点击浇水图标时触发',
                'bhv_key' => 'watering',
            ],
            [
                'name' => '点击领水滴图标',
                'desc' => '点击领水滴图标时触发',
                'bhv_key' => 'task',
            ],
            [
                'name' => '点击领取每日免费水滴时触发',
                'desc' => '点击领取每日免费水滴时触发',
                'bhv_key' => 'today_waters',
            ],
            [
                'name' => '点击领取每日三餐水滴时触发',
                'desc' => '点击领取每日三餐水滴时触发',
                'bhv_key' => 'today_three_meals',
            ],
            [
                'name' => '点击去完成浏览美生商城时触发',
                'desc' => '点击去完成浏览美生商城时触发',
                'bhv_key' => 'see_shop_waters',
            ],
            [
                'name' => '点击去完成在美食中下单商品时触发',
                'desc' => '点击去完成在美食中下单商品时触发',
                'bhv_key' => 'food_order',
            ],
            [
                'name' => '点击开启每日免费领水滴提醒时触发',
                'desc' => '点击开启每日免费领水滴提醒时触发',
                'bhv_key' => 'today_bag_waters_click',
            ],
            [
                'name' => '成功开启每日免费领水滴',
                'desc' => '成功开启每日免费领水滴',
                'bhv_key' => 'today_bag_waters',
            ],
            [
                'name' => '点击分享按钮时触发',
                'desc' => '点击分享按钮时触发',
                'bhv_key' => 'share',
            ],
            [
                'name' => '点击签到按键时触发',
                'desc' => '点击签到按键时触发',
                'bhv_key' => 'today_continuous',
            ],
            [
                'name' => '完成连续5天签到',
                'desc' => '完成连续5天签到',
                'bhv_key' => 'sign_in_success',
            ],
            [
                'name' => '浇水完成，果树收获后触发',
                'desc' => '浇水完成，果树收获后触发',
                'bhv_key' => 'plant_mature',
            ],
            [
                'name' => '获得奖品',
                'desc' => '获得奖品',
                'bhv_key' => 'receive_goods',
            ],
            [
                'name' => '从用户首次浇水到最终获取奖品总计时长',
                'desc' => '从用户首次浇水到最终获取奖品总计时长',
                'bhv_key' => 'receive_goods_time',
            ],
        ];

        \Illuminate\Support\Facades\DB::table('behavior_type')
            ->insert($data);
    }
}
