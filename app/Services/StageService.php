<?php


namespace App\Services;


use App\Events\BehaviorEvent;
use App\Models\Behavior;
use App\Models\Plant;
use App\Models\PlantLog;
use App\Models\Stage;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class StageService
 * @package App\Services
 */
class StageService
{
    /**
     * @var PlantLogService
     */
    protected $plantLogService;

    /**
     * StageService constructor.
     * @param PlantLogService $plantLogService
     */
    public function __construct(PlantLogService $plantLogService)
    {
        $this->plantLogService = $plantLogService;
    }

    /**
     * @param int $stage
     * @return mixed
     * @author Jerry
     */
    public function findStageByStage($stage = 0)
    {
        return Stage::where('stage', $stage)
            ->firstOrFail();
    }

    /**
     * @param Plant $plant
     * @return mixed
     * @throws \Throwable
     */
    public function initPlantStage(Plant $plant)
    {
        return DB::transaction(function () use ($plant) {
            $stage = $this->findStageByStage(1);
            $plant->stage_last_number = $stage->number;
            $plant->stage = $stage->stage;
            $plant->stage_number = 0;
            return $plant->save();
        });
    }

    /**
     * 升级植物
     * @param Plant $plant
     * @return Plant
     * @throws \Throwable
     */
    public function updatePlantStage(Plant $plant)
    {
        if ($plant->state == Plant::STATE_SUCCESS) {
            throw new HttpException(403, '植物已成熟，请及时领取奖品');
        }

        if ($plant->stage_last_number == 0) {
            throw new HttpException(403, '植物异常,请联系管理员');
        }

        $plant->increment('stage_number', 1);
        $plant->decrement('stage_last_number', 1);

        $stage = $this->findStageByStage($plant->stage);
//        升级植物
        if ((! $stage->is_last) && ($plant->stage_number == $stage->number)) {
            return $this->stageUpgrade($plant, $stage);
        }

//      植物成熟
        if ($stage->is_last && $plant->stage_last_number == 0) {
            return $this->updatePlantStageState($plant, $stage);
        }

        return  $plant;
    }

    /**
     * 植物成熟，更改植物状态
     * @param Plant $plant
     * @param Stage $stage
     * @return Plant
     * @author Jerry
     */
    public function updatePlantStageState(Plant $plant, Stage $stage)
    {
        if (! $stage->is_last) {
            return $plant;
        }

        if ($stage->number !== $plant->stage_number) {
            return  $plant;
        }

        $plant->state = Plant::STATE_SUCCESS;
        $now = now();
        $plant->mature_date = $now;
        $plant->time_out_at = $now->addDays(config('prize-exp'));

        $this->plantLogService->createPlantLog([
            'user_id' => $plant->user_id,
            'plant_id' => $plant->id,
            'water' => 0,
            'type' => PlantLog::LOG_SUCCESS,
        ]);

        $plant->save();

        $behaviorData = [
            'user_id' => $plant->user_id,
            'bhv_type' => 'plant_mature',
            'trace_id' => Behavior::TRACE_MANOR,
        ];
        event(new BehaviorEvent($behaviorData));

        return $plant;
    }

    /**
     * 升级植物为下一阶段
     * @param Plant $plant
     * @param Stage $stage
     * @return Plant
     * @throws \Throwable
     */
    public function stageUpgrade(Plant $plant, Stage $stage)
    {
        if ($plant->stage_number !== $stage->number) {
            return $plant;
        }

//        获取下一阶段
        $nextStageId = $stage->where('stage', '>', $stage->stage)->min('id');
        $nextStage = $stage->where('id', $nextStageId)->first();

        $plant->stage = $nextStage->stage;
        $plant->stage_number = 0;
        $plant->stage_last_number = $nextStage->number;

//        升级是否奖励水滴
        if ($stage->waters > 0) {
            $this->stageUpgradeWaters($plant, $stage);
        }

        $plant->save();

        return  $plant;
    }

    /**
     * 升级赠送水滴
     * @param Plant $plant
     * @param Stage $stage
     * @throws \Throwable
     */
    public function stageUpgradeWaters(Plant $plant, Stage $stage)
    {
        DB::transaction(function () use ($stage, $plant) {
//            添加领取记录
            $this->plantLogService->createPlantLog([
                'type' => PlantLog::LOG_UPGRADE,
                'water' => $stage->waters,
                'user_id' => $plant->user_id,
                'plant_id' => $plant->id,
            ]);
//            增加水滴
            $plant->increment('waters', $stage->waters);
        });

    }
}
