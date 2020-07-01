<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class NotifyLog
 * @package App\Models
 */
class NotifyLog extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'notify_log';

    /**
     * @var string[]
     */
    protected $attributes = [
        'content' => '',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'content',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'content' => 'json',
    ];
}
