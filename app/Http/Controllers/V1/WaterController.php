<?php

namespace App\Http\Controllers\V1;

use App\Events\BehaviorEvent;
use App\Http\Resources\HomeResource;
use App\Models\Behavior;
use App\Services\PlantService;
use App\Services\WaterService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class WaterController
 * @package App\Http\Controllers\V1
 */
class WaterController extends ApiController
{

    /**
     * @param Request $request
     * @param PlantService $plantService
     * @param WaterService $waterService
     * @return \Illuminate\Http\Response|void
     * @throws \Throwable
     */
    public function Watering(Request $request, PlantService $plantService, WaterService $waterService)
    {
        try {
            $plant = $plantService->findPlantByUserId($request->user->id);
            $result = $waterService->Watering($plant);
            return $this->item($result, HomeResource::class);
        } catch (ModelNotFoundException $exception) {
            return $this->error('请先种植植物', 403);
        } catch (HttpException $httpException) {
            return $this->error($httpException->getMessage(), $httpException->getStatusCode());
        } finally {
            $data = [
                'user_id' => $request->user->id,
                'bhv_type' => 'watering',
                'trace_id' => Behavior::TRACE_MANOR,
            ];
            event(new BehaviorEvent($data));
        }
    }
}
