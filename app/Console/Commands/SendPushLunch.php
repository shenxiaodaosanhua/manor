<?php

namespace App\Console\Commands;

use App\Services\NotifyService;
use Illuminate\Console\Command;

class SendPushLunch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:plant-lunch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送午餐水滴领取提醒';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param NotifyService $notifyService
     */
    public function handle(NotifyService $notifyService)
    {
        $notifyService->handleLunchWatersNotify();
        \Log::info('推送红包提醒');
        $this->info('success');
    }
}
