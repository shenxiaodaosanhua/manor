<?php

namespace App\Http\Controllers\V1;

use App\Events\BehaviorEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShareResource;
use App\Models\Behavior;
use App\Models\Share;
use App\Services\ShareService;
use Illuminate\Http\Request;

/**
 * Class PlantShareController
 * @package App\Http\Controllers\V1
 */
class PlantShareController extends ApiController
{
    /**
     * @param Request $request
     * @param ShareService $shareService
     * @return mixed
     */
    public function index(Request $request, ShareService $shareService)
    {
        $share = $shareService->findShareByType(Share::TYPE_PLANT);

        $data = [
            'user_id' => $request->user->id,
            'bhv_type' => 'share',
            'trace_id' => Behavior::TRACE_MANOR,
        ];
        event(new BehaviorEvent($data));

        return $this->item($share, ShareResource::class);
    }
}
