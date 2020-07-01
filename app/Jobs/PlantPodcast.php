<?php

namespace App\Jobs;

use App\Exceptions\PlantTimeOutException;
use App\Models\Plant;
use App\Services\PlantService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Class PlantPodcast
 * @package App\Jobs
 */
class PlantPodcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    protected $plantId = 0;

    /**
     * PlantPodcast constructor.
     * @param int $plantId
     */
    public function __construct($plantId = 0)
    {
        $this->plantId = $plantId;
    }


    /**
     * @param PlantService $plantService
     * @throws PlantTimeOutException
     */
    public function handle(PlantService $plantService)
    {
        $plant = Plant::where('id', $this->plantId)
            ->first();
        $plantService->handlePlantTimeOut($plant);
    }

    /**
     * @param \Exception $exception
     */
    public function failed(\Exception $exception)
    {
        if ($exception instanceof PlantTimeOutException) {
            $this->delete();
        }
    }
}
