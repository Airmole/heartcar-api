<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'openid',
        'avatar',
        'nickname',
        'name',
        'mobile',
        'idcard',
        'status',
        'password',
        'car_model',
        'car_limit',
        'car_no'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function getUpdatedAtAttribute($value)
    {
        return $value ? date("Y-m-d H:i:s", strtotime($value)) : '';
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? date("Y-m-d H:i:s", strtotime($value)) : '';
    }

}
