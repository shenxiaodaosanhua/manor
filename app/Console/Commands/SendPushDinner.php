<?php

namespace App\Console\Commands;

use App\Services\NotifyService;
use Illuminate\Console\Command;

class SendPushDinner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:plant-dinner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送晚餐通知';

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
        $notifyService->handleDinnerWatersNotify();
        $this->info('success');
    }
}
