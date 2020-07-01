<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BagSettingForm
 * @package App\Models
 */
class BagSetting extends Model
{
    /**
     * @var string
     */
    protected $table = 'bag_setting';

    /**
     * @var string[]
     */
    protected $fillable = [
        'content',
    ];

    /**
     * @var string[]
     */
    protected $attributes = [
        'content' => '',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'content' => 'json',
    ];
}
