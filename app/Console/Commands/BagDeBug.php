<?php

namespace App\Console\Commands;

use App\Services\BagLogService;
use App\Services\UserService;
use Illuminate\Console\Command;

class BagDeBug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:bag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '修复庄园数据问题';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle(UserService $userService, BagLogService $bagLogService)
    {
        $users = $userService->findUsers();
        if ($users->isEmpty()) {
            $this->error('暂无数据');
            return false;
        }

        $bagLogService->updateSignInDeBug($users);
    }
}
