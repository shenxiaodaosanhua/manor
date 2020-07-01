<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Plant
 * @package App\Models
 */
class Plant extends Model
{
    use SoftDeletes;

    /**
     * 未完成
     */
    public const STATE_NOT = 0;

    /**
     * 已完成
     */
    public const STATE_SUCCESS = 1;

    /**
     * 未领取
     */
    public const STATE_SELECT = 2;

    /**
     * 已领取
     */
    public const STATE_RECEIVE = 3;

    /**
     * 已过期
     */
    public const STATE_TIMEOUT = 4;

    /**
     * @var string[]
     */
    public static $stateType = [
        self::STATE_NOT => '未完成',
        self::STATE_SELECT => '未领取',
        self::STATE_SUCCESS => '已完成',
        self::STATE_RECEIVE => '已领取',
        self::STATE_TIMEOUT => '已过期',
    ];

    /**
     * @var int[]
     */
    protected $attributes = [
        'waters' => 0,
        'water_number' => 0,
        'state' => 0,
        'time_out_at' => null,
        'mature_date' => null,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'water_number',
        'waters',
        'user_id',
        'state',
        'time_out_at',
        'mature_date',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'state' => 'integer',
        'water_number' => 'integer',
        'waters' => 'integer',
        'user_id' => 'integer',
        'time_out_at' => 'datetime',
        'mature_date' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Jerry
     */
    public function log()
    {
        return $this->hasMany(PlantLog::class, 'plant_log');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Jerry
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function level()
    {
        return $this->belongsTo(Stage::class, 'stage');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function receive()
    {
        return $this->hasOne(ReceiveGoods::class, 'plant_id');
    }

    /**
     * @return int
     */
    public function getPreviousWatersAttribute()
    {
        $previous = $this->stage - 1;
        $stage = Stage::where('stage', $previous)
            ->first();

        if (! $stage) {
            return 0;
        }

        return $stage->waters;
    }
}
