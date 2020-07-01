<?php
declare (strict_types=1);

use Illuminate\Database\Seeder;

class PushTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = date("Y-m-d H:i:s");
        $data = [
            [
                'module_type'     => 1,
                'scene_code'      => 'daily_water_drop_remind_lunch',
                'title'           => '美生庄园',
                'content'         => '【美生庄园】滴~您订阅的水滴到啦~午餐水滴限时发放中，快去领取吧>>',
                'scene_desc'      => '开启每日免费领水滴提醒后 每日中午12：30',
                'push_type'       => 3,
                'push_group_type' => 1,
                'created_at'      => $time,
                'updated_at'      => $time
            ],
            [
                'module_type'     => 1,
                'scene_code'      => 'daily_water_drop_remind_dinner',
                'title'           => '美生庄园',
                'content'         => '【美生庄园】滴~您订阅的水滴到啦~晚餐水滴限时发放中，快去领取吧>>',
                'scene_desc'      => '开启每日免费领水滴提醒后 每日下午18：30',
                'push_type'       => 3,
                'push_group_type' => 1,
                'created_at'      => $time,
                'updated_at'      => $time
            ],
            [
                'module_type'     => 1,
                'scene_code'      => 'harvest_within_seventy_two_hours',
                'title'           => '美生庄园',
                'content'         => '【美生庄园】恭喜！您的果树收获了！快去领取奖品吧！果树收获后72小时内未领奖品将失效，点击领取奖品>>',
                'scene_desc'      => '果树收获后72小时内 每天中午12:30给用户推送消息通知，用户领取后不再推送',
                'push_type'       => 3,
                'push_group_type' => 1,
                'created_at'      => $time,
                'updated_at'      => $time
            ],
            [
                'module_type'     => 2,
                'scene_code'      => 'sign_in_tip',
                'title'           => '红包签到',
                'content'         => '【红包签到】今天的红包可以领取啦~每天领一笔，累计可提现，快去签到吧>>',
                'scene_desc'      => '开启签到提醒后 每天上午9:30，用户在上午9:30前已领取签到红包，则不推送',
                'push_type'       => 2,
                'push_group_type' => 1,
                'created_at'      => $time,
                'updated_at'      => $time
            ],
        ];
        \Illuminate\Support\Facades\DB::table('push_template')
            ->insert($data);
    }
}
