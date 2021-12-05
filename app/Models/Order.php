<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    CONST STATUS_WAIT = 0;     // 待接单
    CONST STATUS_ACCEPT = 1;   // 已接单
    CONST STATUS_PICKING = 2;  // 进行中
    CONST STATUS_FINISHED = 3; // 已完成
    CONST STATUS_CANCELED = 4; // 已取消


    CONST TYPE_SELF = 0;     // 独享订单
    CONST TYPE_SHARE = 1;    // 拼车订单


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'order_no',
        'user_id',
        'driver_id',
        'status',
        'type',
        'order_time',
        'start_time',
        'stop_time',
        'direction',
        'destinations',
        'passengers',
        'start',
        'start_text',
        'end',
        'end_text',
        'distance',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'direction' => 'json',
        'destinations' => 'json',
        'passengers' => 'json',
        'start' => 'json',
        'end' => 'json',
    ];

    public static function createNewOrderNo()
    {
        return "SO" . date("YmdHis") . mt_rand(1000, 9999);
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function driver()
    {
        return $this->hasOne('App\Models\Driver', 'id', 'driver_id');
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? date("Y-m-d H:i:s", strtotime($value)) : '';
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? date("Y-m-d H:i:s", strtotime($value)) : '';
    }

}
