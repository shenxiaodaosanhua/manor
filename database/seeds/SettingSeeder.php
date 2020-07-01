<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plant = config('plant');
        $data = [
            'content' => json_encode($plant),
        ];

        \Illuminate\Support\Facades\DB::table('setting')
            ->insert($data);
    }
}
