<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Encrypted;

class CommissionHistory extends Model
{
    use SoftDeletes, Encrypted;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'sub_category_id',
        'user_id',
        'commission'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category\Category', 'sub_category_id', 'id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\Auth\SentinelUser', 'id', 'user_id');
    }

}
