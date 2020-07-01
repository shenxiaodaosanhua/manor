<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BagNotify
 * @package App\Models
 */
class BagNotify extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'bag_notify';

    /**
     * @var int[]
     */
    protected $attributes = [
        'user_id' => 0,
        'state' => 0,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'state',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'user_id' => 'integer',
        'state' => 'integer',
    ];

    /**
     * 关闭
     */
    public const STATE_OFF = 0;

    /**
     * 开启
     */
    public const STATE_ON = 1;

    /**
     * @var string[]
     */
    public static $state = [
        self::STATE_OFF => '关闭',
        self::STATE_ON => '开启',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
