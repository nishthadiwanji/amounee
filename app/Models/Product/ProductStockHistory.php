<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Encrypted;
use Illuminate\Database\Eloquent\Model;

class ProductStockHistory extends Model
{
    //
    use SoftDeletes, Encrypted;
    protected $fillable = [
        'product_id',
        'user_id',
        'stock_status',
        'stock'
    ];
    public function user(){
        return $this->belongsTo('App\Models\Auth\SentinelUser', 'user_id', 'id');
    }
}
