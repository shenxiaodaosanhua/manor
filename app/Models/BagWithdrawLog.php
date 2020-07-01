<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

/**
 * Class BagWithdrawLog
 * @package App\Models
 */
class BagWithdrawLog extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'bag_withdraw_log';

    /**
     * @var array
     */
    protected $attributes = [
        'no' => '',
        'amount' => 0,
        'user_id' => 0,
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'no',
        'amount',
        'user_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 监听模型创建事件自动生成订单的流水号
     */
    protected static function boot()
    {
        parent::boot();
        // 监听模型创建事件，在写入数据库之前触发
        static::creating(function ($model) {
            // 如果模型的 no 字段为空
            if (!$model->no) {
                // 调用 findAvailableNo 生成订单流水号
                $model->no = static::findAvailableNo();
                // 如果生成失败，则终止创建订单
                if (! $model->no) {
                    return false;
                }
            }
        });
    }

    /**
     * 获取流水号
     * @return bool|string
     * @throws \Exception
     */
    public static function findAvailableNo()
    {
        // 订单流水号前缀
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 6 位的数字
            $no = $prefix . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // 判断是否已经存在
            if (!static::query()->where('no', $no)->exists()) {
                return $no;
            }
        }
        Log::warning('find order no failed');

        return false;
    }

}
