<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Behavior
 * @package App\Models
 */
class Behavior extends Model
{
    /**
     * @var string
     */
    protected $table = 'behavior';

    /**
     * @var array
     */
    protected $attributes = [
        'bhv_type' => '',
        'bhv_value' => '',
        'platform' => '',
        'app_version' => '',
        'user_id' => 0,
        'bhv_desc' => '',
        'trace_id' => 0,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'bhv_type',
        'bhv_value',
        'platform',
        'app_version',
        'user_id',
        'bhv_desc',
        'trace_id',
    ];

    /**
     * 庄园
     */
    public const TRACE_MANOR = 1;

    /**
     * 红包
     */
    public const TRACE_BAG = 2;

    /**
     * @var string[]
     */
    public static $trace = [
        self::TRACE_MANOR => '庄园',
        self::TRACE_BAG => '红包',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(BehaviorType::class, 'bhv_type', 'bhv_key');
    }
}
