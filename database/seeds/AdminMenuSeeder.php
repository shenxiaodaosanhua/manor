<?php

use Illuminate\Database\Seeder;

class AdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $publicData = [
            [
                'title' => '推送设置',
                'icon' => 'fa-comment-o',
                'uri' => 'pushtemplates',
                'order' => 1,
                'parent_id' => 0,
            ],
            [
                'title' => '分享设置',
                'icon' => 'fa-cog',
                'uri' => 'share',
                'order' => 5,
                'parent_id' => 0,
            ],
            [
                'title' => '系统参数配置',
                'icon' => 'fa-cog',
                'uri' => 'config',
                'order' => 6,
                'parent_id' => 0,
            ],
        ];
        \Illuminate\Support\Facades\DB::table('admin_menu')->insert($publicData);

        $plantData = [
            'title' => '庄园',
            'icon' => 'fa-envira',
            'uri' => '',
            'order' => 2,
        ];
        $plantId = \Illuminate\Support\Facades\DB::table('admin_menu')->insertGetId($plantData);
        $plantParentData = [

            [
                'title' => '阶段管理',
                'icon' => 'fa-line-chart',
                'uri' => 'stage',
                'order' => 5,
                'parent_id' => $plantId,
            ],
            [
                'title' => '礼品管理',
                'icon' => 'fa-bars',
                'uri' => 'goods',
                'order' => 4,
                'parent_id' => $plantId,
            ],
            [
                'title' => '玩法设置',
                'icon' => 'fa-bars',
                'uri' => 'setting',
                'order' => 3,
                'parent_id' => $plantId,
            ],
            [
                'title' => '奖品列表',
                'icon' => 'fa-angellist',
                'uri' => 'receive',
                'order' => 2,
                'parent_id' => $plantId,
            ],
            [
                'title' => '攻略管理',
                'icon' => 'fa-align-center',
                'uri' => 'raiders',
                'order' => 1,
                'parent_id' => $plantId,
            ],
        ];
        \Illuminate\Support\Facades\DB::table('admin_menu')
            ->insert($plantParentData);

        $bagData = [
            'title' => '红包',
            'icon' => 'fa-cny',
            'uri' => '',
            'order' => 2,
        ];
        $bagId = \Illuminate\Support\Facades\DB::table('admin_menu')->insertGetId($bagData);
        $bagMenuData = [
            [
                'title' => '红包设置',
                'icon' => 'fa-align-center',
                'uri' => 'bag-setting',
                'order' => 1,
                'parent_id' => $bagId,
            ],
            [
                'title' => '提现记录',
                'icon' => 'fa-money',
                'uri' => 'withdraw',
                'order' => 2,
                'parent_id' => $bagId,
            ],
        ];
        \Illuminate\Support\Facades\DB::table('admin_menu')->insert($bagMenuData);
    }
}
