<?php

namespace App\Models\Product;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Encrypted;

use Illuminate\Database\Eloquent\Model;

class ProuctPriceHistory extends Model
{
    //
    use SoftDeletes, Encrypted;
    protected $fillable = [
        'product_id',
        'user_id',
        'base_price',
        'applied_commision',
        'applied_commission_number',
        'applied_commision_type',
        'commision_amount',
        'selling_price',
        'tax',
        'mrp'
    ];
    public function user(){
        return $this->belongsTo('App\Models\Auth\SentinelUser', 'user_id', 'id');
    }

}
