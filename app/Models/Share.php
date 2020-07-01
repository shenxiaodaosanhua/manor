<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Share
 * @package App\Models
 */
class Share extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'share';

    /**
     * @var array
     */
    protected $attributes = [
        'type' => 0,
        'url' => '',
        'desc' => '',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'url',
        'desc',
    ];

    /**
     * 庄园
     */
    public const TYPE_PLANT = 1;

    /**
     * 红包
     */
    public const TYPE_BAG = 2;

    /**
     * @var array
     */
    public static $type = [
        self::TYPE_PLANT => '庄园',
        self::TYPE_BAG => '红包',
    ];
}
