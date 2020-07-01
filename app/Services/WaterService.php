<?php


namespace App\Services;


use App\Exceptions\StageNotException;
use App\Models\Plant;
use App\Models\PlantLog;
use App\Models\Stage;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class WaterService
 * @package App\Services
 */
class WaterService
{

    /**
     * @var PlantLogService
     */
    public $plantLogService;

    /**
     * WaterService constructor.
     * @param PlantLogService $plantLogService
     */
    public function __construct(PlantLogService $plantLogService)
    {
        $this->plantLogService = $plantLogService;
    }

    /**
     * @param Plant $plant
     * @return mixed
     * @throws \Throwable
     * @author Jerry
     */
    public function Watering(Plant $plant)
    {
        return DB::transaction(function () use ($plant) {
            $this->checkWatering($plant);

            $plantLog = $this->plantLogService->createPlantLog([
                'user_id' => $plant->user_id,
                'type' => PlantLog::LOG_WATERING,
                'water' => config('watering-number'),
                'plant_id' => $plant->id,
            ]);


            $plant->decrement('waters', config('watering-number'));
            $plant->increment('water_number', 1);

            $plant->refresh();


            return $plant;
        });
    }

    /**
     * 检查是否可浇水
     * @param Plant $plant
     * @throws StageNotException
     */
    protected function checkWatering(Plant $plant)
    {
        if ($plant->waters < config('watering-number')) {
            throw new HttpException('403', '水量不足');
        }

        $stage = Stage::where('stage', $plant->stage)->first();
        if (! $stage) {
            throw new StageNotException('系统设置错误，请联系管理', 500);
        }

        if ($stage->is_last && ($plant->stage_last_number == 0)) {
            throw new HttpException(403, '植物已成熟');
        }
    }
}
