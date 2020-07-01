<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\PlantService;
use App\Services\PlantTaskService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PlantTaskController extends ApiController
{
    public function getTasks(Request $request, PlantService $plantService, PlantTaskService $plantTaskService)
    {
        try {
            $plant = $plantService->findPlantByUserId($request->user->id);
            $tasks = $plantTaskService->getMyTasks($plant);
            return  $tasks;
        } catch (HttpException $httpException) {
            return $this->error($httpException->getMessage(), $httpException->getStatusCode());
        }
    }
}
