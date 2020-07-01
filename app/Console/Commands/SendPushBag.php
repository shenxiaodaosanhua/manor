<?php

namespace App\Console\Commands;

use App\Services\NotifyService;
use Illuminate\Console\Command;

class SendPushBag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:bag-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '红包领取提现';

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
        $notifyService->handleBagNotify();
        $this->info('success');
    }
}
