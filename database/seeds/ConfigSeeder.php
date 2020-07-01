<?php

use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
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
                'name' => 'watering-number',
                'value' => '10',
                'description' => '每次浇水数量',
            ],
            [
                'name' => 'first-water-number',
                'value' => '60',
                'description' => '首次领取时水量'
            ],
            [
                'name' => 'new-receive-waters',
                'value' => '60',
                'description' => '新一轮种植奖励水滴数'
            ],
            [
                'name' => 'prize-exp',
                'value' => '3',
                'description' => '奖品有效时间（单位：天）'
            ],
            [
                'name' => 'withdraw-number',
                'value' => '10',
                'description' => '	红包提现金额配置（单位：元）'
            ],
        ];
        \Illuminate\Support\Facades\DB::table('admin_config')
            ->insert($data);
    }
}
