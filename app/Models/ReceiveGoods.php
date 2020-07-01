<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ReceiveGoodsService
 * @package App\Models
 */
class ReceiveGoods extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'receive_goods';

    /**
     * @var int[]
     */
    protected $attributes = [
        'user_id' => 0,
        'goods_id' => 0,
        'name' => '',
        'address' => '',
        'tracking_number' => '',
        'mobile' => '',
        'state' => 0,
        'plant_id' => 0,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'goods_id',
        'name',
        'address',
        'tracking_number',
        'mobile',
        'state',
        'plant_id',
    ];

    /**
     * 无
     */
    public const STATE_NOT = 0;

    /**
     * 已选择礼品
     */
    public const STATE_SELECT = 1;

    /**
     * 已下单
     */
    public const STATE_ORDER = 2;

    /**
     * 已发货
     */
    public const STATE_SHIP = 3;

    /**
     * 已完成
     */
    public const STATE_FULFILL = 4;

    /**
     * 已超时
     */
    public const STATE_TIME_OUT = 5;

    /**
     * @var string[]
     */
    public static $state = [
//        self::STATE_NOT => '无状态',
        self::STATE_SELECT => '未领取',
        self::STATE_ORDER => '待发货',
        self::STATE_SHIP => '已发货',
        self::STATE_FULFILL => '已完成',
        self::STATE_TIME_OUT => '已失效',
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
    public function goods()
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plant()
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function logistics()
    {
        return $this->hasOne(ReceiveGoodsLogistics::class, 'receive_id');
    }
}
