<?php

namespace App\Console\Commands;

use App\Services\NotifyService;
use Illuminate\Console\Command;

class SendPushPlantReceive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:plant-receive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '推送礼品领取消息';

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
        $notifyService->handleMatureWatersNotify();
        $this->info('success');
    }
}
