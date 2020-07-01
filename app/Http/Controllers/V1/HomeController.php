<?php


namespace App\Http\Controllers\V1;


use App\Events\BehaviorEvent;
use App\Http\Resources\HomeResource;
use App\Models\Behavior;
use App\Services\PlantService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class HomeController
 * @package App\Http\Controllers\V1
 */
class HomeController extends ApiController
{
    /**
     * @param Request $request
     * @param PlantService $plantService
     * @return \Illuminate\Http\Response|mixed|void
     * @author Jerry
     */
    public function index(Request $request, PlantService $plantService)
    {
        try {
            $userId = $request->user->id;
            $plant = $plantService->findPlantByUserId($userId);

            return $this->item($plant, HomeResource::class);
        } catch (ModelNotFoundException $exception) {
            return $this->error('未种植', 405);
        } catch (HttpException $exception) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        } finally {
            $data = [
                'user_id' => $request->user->id,
                'bhv_type' => 'home',
                'trace_id' => Behavior::TRACE_MANOR,
            ];
            event(new BehaviorEvent($data));
        }
    }

    /**
     * 获取用户植物状态
     * @param Request $request
     * @param PlantService $plantService
     * @return \Illuminate\Http\Response|void
     */
    public function status(Request $request, PlantService $plantService)
    {
        try {
            $status = $plantService->getPlantStatus($request->user);
            return $this->json($status);
        } catch (HttpException $exception) {
            return $this->error($exception->getMessage(), $exception->getStatusCode());
        }
    }
}
