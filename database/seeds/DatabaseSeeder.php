<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
             AdminMenuSeeder::class,
             ConfigSeeder::class,
             SettingSeeder::class,
             StageSeeder::class,
             PushTemplateSeeder::class,
             BagSettingSeeder::class,
             ShareSeeder::class,
             BehaviorTypeSeeder::class,
         ]);
    }
}
