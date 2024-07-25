<?php

namespace App\Models\Product;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Encrypted;


use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    //
    use SoftDeletes, Encrypted;
    protected $fillable = [
        'product_id',
        'file_id',
        'file_type'
    ];

    public function fileInfo()
    {
        return $this->hasOne('App\Models\FileInfo\FileInfo', 'id', 'file_id');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product\Product', 'product_id', 'id');
    }
}
