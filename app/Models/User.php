<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App\Models
 */
class User extends Model
{
    use Notifiable;

    /**
     * @var int[]
     */
    protected $attributes = [
        'amount' => 0,
        'sign_number' => 0,
        'openid' => '',
        'avatar' => '',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'nickname',
        'amount',
        'sign_number',
        'openid',
        'avatar',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bag()
    {
        return $this->hasMany(BagLog::class, 'user_id');
    }
}
