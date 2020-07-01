<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\ActionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class ActionPodcast
 * @package App\Jobs
 */
class ActionPodcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $path = '';

    /**
     * ActionPodcast constructor.
     * @param User $user
     * @param $path
     */
    public function __construct(User $user, $path)
    {
        $this->user = $user;
        $this->path = $path;
    }


    public function handle(ActionService $actionService)
    {
        $actionService->drive($this->path)->handle($this->user);
    }
}
