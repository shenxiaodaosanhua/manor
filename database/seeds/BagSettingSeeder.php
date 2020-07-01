<?php

use Illuminate\Database\Seeder;

class BagSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $content = config('bag.setting');
        $data = [
            'content' => json_encode($content),
        ];

        \Illuminate\Support\Facades\DB::table('bag_setting')->insert($data);
    }
}
