<?php

use Illuminate\Database\Seeder;

class ShareSeeder extends Seeder
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
                'desc' => '分享链接',
                'url' => 'https://h5.cupsy.cn/register/767273',
                'type' => \App\Models\Share::TYPE_PLANT,
            ],
        ];

        \Illuminate\Support\Facades\DB::table('share')
            ->insert($data);
    }
}
