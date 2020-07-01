<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BehaviorType
 * @package App\Models
 */
class BehaviorType extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'behavior_type';
}
