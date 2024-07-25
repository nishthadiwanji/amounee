<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
	use SoftDeletes;

    protected $fillable = [
        'user_id',
        'device_id',
        'device_type',
        'reg_id'
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
    	return $this->belongsTo('App\Models\Auth\SentinelUser', 'user_id', 'id');
    }
}
