<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PlantLog
 * @package App\Models
 */
class PlantLog extends Model
{
    use SoftDeletes;

    /**
     * @var int[]
     */
    protected $attributes = [
        'type' => 0,
        'water' => 0,
        'task_id' => 0,
        'plant_id' => 0,
        'user_id' => 0,
        'task_name' => '',
    ];

    /**
     * 首次领取
     */
    public const LOG_FIRST = 1;

    /**
     * 任务领取
     */
    public const LOG_TASK = 2;

    /**
     * 浇水
     */
    public const LOG_WATERING = 3;

    /**
     * 升级赠送
     */
    public const LOG_UPGRADE = 4;

    /**
     * 果树收获
     */
    public const LOG_SUCCESS = 5;

    /**
     * 礼品领取
     */
    public const LOG_RECEIVE = 6;

    /**
     * 奖品失效
     */
    public const LOG_TIME_OUT = 7;

    public const LOG_NEW_RECEIVE = 8;

    /**
     * @var string[]
     */
    public static $logType = [
        self::LOG_FIRST => '首次领取',
        self::LOG_TASK => '任务领取',
        self::LOG_WATERING => '浇水',
        self::LOG_UPGRADE => '升级赠送',
        self::LOG_SUCCESS => '果树收获',
        self::LOG_RECEIVE => '礼品领取',
        self::LOG_TIME_OUT => '奖品失效',
        self::LOG_NEW_RECEIVE => '新一轮种植',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'plant_id',
        'user_id',
        'type',
        'water',
        'task_id',
        'task_name',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'deleted_at',
        'task_id',
    ];

    /**
     * @var string
     */
    protected $table = 'plant_log';

    /**
     * @var string[]
     */
    protected $casts = [
        'water' => 'integer',
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'plant'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Jerry
     */
    public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Jerry
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
