<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Raiders
 * @package App\Models
 */
class Raiders extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $attributes = [
        'title' => '',
        'content' => '',
        'state' => 0,
        'order' => 0,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'content',
        'state',
        'order',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'state' => 'integer',
        'order' => 'integer',
    ];

    /**
     * 启用
     */
    public const STATE_ENABLE = 1;

    /**
     * 禁用
     */
    public const STATE_DISABLE = 0;

    /**
     * @var string[]
     */
    public static $state = [
        self::STATE_DISABLE => '禁用',
        self::STATE_ENABLE => '启用',
    ];
}
