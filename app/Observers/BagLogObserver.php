<?php

namespace App\Observers;

use App\Models\BagLog;
use App\Services\BagLogService;

/**
 * Class BagLogObserver
 * @package App\Observers
 */
class BagLogObserver
{

    /**
     * @var BagLogService
     */
    protected $bagLogService;

    /**
     * BagLogObserver constructor.
     * @param BagLogService $bagLogService
     */
    public function __construct(BagLogService $bagLogService)
    {
        $this->bagLogService = $bagLogService;
    }

    /**
     * Handle the bag log "created" event.
     *
     * @param  \App\Models\BagLog  $bagLog
     * @return void
     */
    public function created(BagLog $bagLog)
    {
        if ($bagLog->type == BagLog::TYPE_SIGN) {
            $this->bagLogService->addUserSignNumber($bagLog);
        }
    }

    /**
     * Handle the bag log "updated" event.
     *
     * @param  \App\Models\BagLog  $bagLog
     * @return void
     */
    public function updated(BagLog $bagLog)
    {
        //
    }

    /**
     * Handle the bag log "deleted" event.
     *
     * @param  \App\Models\BagLog  $bagLog
     * @return void
     */
    public function deleted(BagLog $bagLog)
    {
        //
    }

    /**
     * Handle the bag log "restored" event.
     *
     * @param  \App\Models\BagLog  $bagLog
     * @return void
     */
    public function restored(BagLog $bagLog)
    {
        //
    }

    /**
     * Handle the bag log "force deleted" event.
     *
     * @param  \App\Models\BagLog  $bagLog
     * @return void
     */
    public function forceDeleted(BagLog $bagLog)
    {
        //
    }
}
