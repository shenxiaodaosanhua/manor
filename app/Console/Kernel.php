<?php

namespace App\Console;

use App\Console\Commands\SendPushBag;
use App\Console\Commands\SendPushDinner;
use App\Console\Commands\SendPushLunch;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        红包签到提醒
        $schedule->command('push:bag-notify')
            ->dailyAt('9:30');

//        午餐水滴提醒
        $schedule->command('push:plant-lunch')
                ->dailyAt('12:30');
//        果树成熟提醒
        $schedule->command('push:plant-receive')
            ->dailyAt('12:30');
//        晚餐水滴提醒
        $schedule->command(SendPushDinner::class)
            ->dailyAt('18:30');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
