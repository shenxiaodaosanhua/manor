<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BehaviorRequest;
use App\Services\BehaviorService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class BehaviorController
 * @package App\Http\Controllers\V1
 */
class BehaviorController extends ApiController
{
    /**
     * @param BehaviorRequest $request
     * @param BehaviorService $behaviorService
     * @return \Illuminate\Http\Response
     */
    public function store(BehaviorRequest $request, BehaviorService $behaviorService)
    {
        $name = $request->get('bhv_name', '');
        $value = $request->get('bhv_value', '');

        $behaviorService->isApiStore($name)->apiInsert($request->user, $name, $value);
        return $this->created();
    }
}
