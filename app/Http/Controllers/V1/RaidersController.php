<?php

namespace App\Http\Controllers\V1;

use App\Http\Resources\RaidersResource;
use App\Models\Raiders;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class RaidersController
 * @package App\Http\Controllers\V1
 */
class RaidersController extends ApiController
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $raiders = Cache::rememberForever('raiders', function () {
                return Raiders::where('state', Raiders::STATE_ENABLE)
                    ->orderBy('order', 'desc')
                    ->get();
            });

            return $this->collection($raiders, RaidersResource::class);
        } catch (ModelNotFoundException $exception) {
            return $this->error('暂无数据', 403);
        } catch (HttpException $httpException) {
            return  $this->error($httpException->getMessage(), 403);
        }
    }
}
