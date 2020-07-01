<?php

namespace App\Observers;

use App\Jobs\PlantPodcast;
use App\Models\Plant;
use App\Services\PlantService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;

/**
 * Class PlantObserver
 * @package App\Observers
 */
class PlantObserver
{

    /**
     * @var PlantService
     */
    protected $plantServer;

    /**
     * PlantObserver constructor.
     * @param PlantService $plantService
     */
    public function __construct(PlantService $plantService)
    {
        $this->plantServer = $plantService;
    }

    /**
     * Handle the plant "created" event.
     *
     * @param  \App\Plant  $plant
     * @return void
     */
    public function created(Plant $plant)
    {
        //
    }

    /**
     * Handle the plant "updated" event.
     *
     * @param  \App\Plant  $plant
     * @return void
     */
    public function updated(Plant $plant)
    {
        if ($plant->state == Plant::STATE_SUCCESS) {
            $this->plantServer->plantSuccess($plant);
        }

        return;
    }

    /**
     * Handle the plant "deleted" event.
     *
     * @param  \App\Plant  $plant
     * @return void
     */
    public function deleted(Plant $plant)
    {
        //
    }

    /**
     * Handle the plant "restored" event.
     *
     * @param  \App\Plant  $plant
     * @return void
     */
    public function restored(Plant $plant)
    {
        //
    }

    /**
     * Handle the plant "force deleted" event.
     *
     * @param  \App\Plant  $plant
     * @return void
     */
    public function forceDeleted(Plant $plant)
    {
        //
    }
}
