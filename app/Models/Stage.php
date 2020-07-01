<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Stage
 * @package App\Models
 */
class Stage extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'stage';

    /**
     * 否
     */
    public const LAST_NOT = 0;

    /**
     * 是
     */
    public const LAST_SUCCESS = 1;

    /**
     * @var string[]
     */
    public static $is_last = [
        self::LAST_NOT => '否',
        self::LAST_SUCCESS => '是',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'name' => '',
        'stage' => 0,
        'number' => 0,
        'is_last' => 0,
        'waters' => 0,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'stage',
        'number',
        'is_last',
        'waters',
    ];
}
