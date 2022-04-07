<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'order_id',
        'user_id',
        'start',
        'start_text',
        'end',
        'end_text',
        'distance',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'start' => 'json',
        'end' => 'json',
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
