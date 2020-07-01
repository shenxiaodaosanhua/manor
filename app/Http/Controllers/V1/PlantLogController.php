<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlantLogResource;
use App\Services\PlantLogService;
use App\Services\PlantService;
use Illuminate\Http\Request;

/**
 * Class PlantLogController
 * @package App\Http\Controllers\V1
 */
class PlantLogController extends ApiController
{
    /**
     * @param Request $request
     * @param PlantLogService $plantLogService
     * @return \Illuminate\Http\Response|void
     */
    public function index(Request $request, PlantLogService $plantLogService)
    {
        try {
            $logs = $plantLogService->getMyLog($request->user);
            return $this->json($logs);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }
}
