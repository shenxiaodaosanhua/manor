<?php

namespace App\Http\Controllers\V1;

use App\Events\Planting;
use App\Http\Resources\HomeResource;
use App\Http\Resources\TimeOutResource;
use App\Models\Plant;
use App\Services\PlantService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class PlantController
 * @package App\Http\Controllers\V1
 */
class PlantController extends ApiController
{
    /**
     * 种树
     * @param Request $request
     * @param PlantService $plantService
     * @return \Illuminate\Http\Response|mixed|void
     */
    public function planting(Request $request, PlantService $plantService)
    {
        try {
            $plant = $plantService->getMyPlant($request->user);

            event(new Planting($plant));

            return $this->item($plant, HomeResource::class);
        } catch (HttpException $exception) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        }
    }

    /**
     * 获取植物成熟后礼品领取结束时间
     * @param Request $request
     * @param PlantService $plantService
     * @return \Illuminate\Http\Response|void
     */
    public function timeOut(Request $request, PlantService $plantService)
    {
        try {
            $plant = $plantService->findPlantNewStatus($request->user);
            return $this->item($plant, TimeOutResource::class);
        } catch (HttpException $exception) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        }
    }
}
