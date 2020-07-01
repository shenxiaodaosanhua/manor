<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BagLog
 * @package App\Models
 */
class BagLog extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'bag_log';

    /**
     * @var array
     */
    protected $attributes = [
        'user_id' => 0,
        'amount' => 0,
        'type' => 0,
        'is_last' => 0,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'is_last',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'amount' => 'decimal:2'
    ];

    /**
     * 签到
     */
    public const TYPE_SIGN = 1;

    /**
     * 提现
     */
    public const TYPE_WITHDRAW = 2;

    /**
     * @var string[]
     */
    public static $type = [
        self::TYPE_SIGN => '签到',
        self::TYPE_WITHDRAW => '提现',
    ];

    /**
     * 否
     */
    public const LAST_NOT = 0;

    /**
     * 是
     */
    public const LAST_YES = 1;

    /**
     * @var string[]
     */
    public static $last = [
        self::LAST_NOT => '否',
        self::LAST_YES => '是',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
