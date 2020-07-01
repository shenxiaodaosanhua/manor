<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * Class Goods
 * @package App\Models
 */
class Goods extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $attributes = [
        'name' => '',
        'desc' => null,
        'image' => '',
        'stock' => 0,
        'seller_name' => '',
        'state' => 0,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'desc',
        'image',
        'stock',
        'seller_name',
        'state',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'stock' => 'integer',
        'state' => 'integer',
    ];

    /**
     * 已上架
     */
    public const STATE_ON = 1;

    /**
     * 已下架
     */
    public const STATE_OFF = 0;

    /**
     * @var string[]
     */
    public static $state = [
        self::STATE_ON => '已上架',
        self::STATE_OFF => '已下架',
    ];

    /**
     * 图片cdn地址
     * @param $value
     * @return string
     */
    public function getImageUrlAttribute($value)
    {
        return Storage::url($this->image);
    }
}
