<?php

namespace App\Observers;

use App\Models\Raiders;
use Illuminate\Support\Facades\Cache;

/**
 * Class RaidersObserver
 * @package App\Observers
 */
class RaidersObserver
{

    /**
     * @param Raiders $raiders
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function created(Raiders $raiders)
    {
        Cache::delete('raiders');
    }


    /**
     * @param Raiders $raiders
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function updated(Raiders $raiders)
    {
        Cache::delete('raiders');
    }


    /**
     * @param Raiders $raiders
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function deleted(Raiders $raiders)
    {
        Cache::delete('raiders');
    }

    /**
     * Handle the raiders "restored" event.
     *
     * @param  \App\Raiders  $raiders
     * @return void
     */
    public function restored(Raiders $raiders)
    {
        //
    }

    /**
     * Handle the raiders "force deleted" event.
     *
     * @param  \App\Raiders  $raiders
     * @return void
     */
    public function forceDeleted(Raiders $raiders)
    {
        //
    }
}
