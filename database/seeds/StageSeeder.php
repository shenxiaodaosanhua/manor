<?php

use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
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
                'name' => '小芽期',
                'stage' => 1,
                'number' => 2,
                'is_last' => 0,
                'waters' => 10,
            ],
            [
                'name' => '树苗期',
                'stage' => 2,
                'number' => 6,
                'is_last' => 0,
                'waters' => 10,
            ],
            [
                'name' => '大树期',
                'stage' => 3,
                'number' => 15,
                'is_last' => 0,
                'waters' => 10,
            ],
            [
                'name' => '开花期',
                'stage' => 4,
                'number' => 25,
                'is_last' => 0,
                'waters' => 10,
            ],
            [
                'name' => '结果期',
                'stage' => 5,
                'number' => 40,
                'is_last' => 0,
                'waters' => 10,
            ],
            [
                'name' => '成熟期',
                'stage' => 6,
                'number' => 100,
                'is_last' => 1,
                'waters' => 10,
            ],
        ];

        \Illuminate\Support\Facades\DB::table('stage')
            ->insert($data);
    }
}
