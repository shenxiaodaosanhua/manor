<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Setting
 * @package App\Models
 */
class Setting extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'setting';

    /**
     * @var \string[][]
     */
    public static $dateBetween = [
        [
            'start' => '7:00',
            'end' => '9:00',
        ],
        [
            'start' => '12:00',
            'end' => '14:00',
        ],
        [
            'start' => '18:00',
            'end' => '21:00',
        ]
    ];

    /**
     * @var array
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
