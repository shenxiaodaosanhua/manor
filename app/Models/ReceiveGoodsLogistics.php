<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ReceiveGoodsLogistics
 * @package App\Models
 */
class ReceiveGoodsLogistics extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'receive_goods_logistics';

    /**
     * @var array
     */
    protected $attributes = [
        'receive_id' => 0,
        'content' => '',
        'status' => 0,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'receive_id',
        'content',
        'status',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'content' => 'json',
        'status' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receive()
    {
        return $this->belongsTo(ReceiveGoods::class, 'receive_id');
    }
}
