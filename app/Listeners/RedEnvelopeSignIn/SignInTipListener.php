<?php

namespace App\Listeners\RedEnvelopeSignIn;

use App\Events\RedEnvelopeSignIn\SignInTip;
use App\Services\PushServices;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SignInTipListener implements ShouldQueue
{
    protected $queue = '{manor}';

    protected $pushService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PushServices $pushService)
    {
        $this->pushService = $pushService;
    }

    /**
     * @param SignInTip $event
     * @author sunshine
     */
    public function handle(SignInTip $event)
    {
        try {
            $this->pushService->signInTip($event->to);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}

