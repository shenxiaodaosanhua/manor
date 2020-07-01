<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TaskState
 * @package App\Models
 */
class TaskState extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'task_state';

    /**
     * @var array
     */
    protected $attributes = [
        'name' => '',
        'waters' => 0,
        'user_id' => 0,
        'plant_id' => 0,
        'is_last' => 0,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'waters',
        'user_id',
        'plant_id',
        'is_last',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }
}
