<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BagHelp
 * @package App\Models
 */
class BagHelp extends Model
{
    /**
     * @var string
     */
    protected $table = 'bag_help';

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
        'content' => 'string',
    ];
}
