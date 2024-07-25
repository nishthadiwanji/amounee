<?php
namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BanUser;
use App\Traits\Encrypted;

class Product extends Model
{
    use SoftDeletes, Encrypted ,BanUser;

    protected $dates = ['deleted_at'];

    protected $fillable = [
            'woocoomerce_product_id',
            'artisan_id',
            'category_id',
            'sub_category_id' ,
            'product_name',
            'sku',
            'short_desc' ,
            'long_desc' ,
            'material' ,
            'stock_status',
            'stock' ,
            'base_price' ,
            'product_comm_number',
            'product_comm_type',
            'category_comm_number',
            'category_comm_type',
            'artisan_comm_number',
            'artisan_comm_type',
            'global_comm_number',
            'global_comm_type',
            'selling_price',
            'commision_amount',
            'tax_status',
            'tax_class',
            'tax_amount',
            'mrp',
            'hsn_code' ,
            // 'product_image',
            'status',
            'delisted'
    ];
    public function artisan()
    {
        return $this->belongsTo('App\Models\Artisan\Artisan','artisan_id','id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category\Category','category_id','id');
    }

    public function sub_category()
    {
        return $this->belongsTo('App\Models\Category\Category','sub_category_id','id');
    }

    public function photo(){
        return $this->belongsTo('App\Models\FileInfo\FileInfo','product_image','id');
    }

    public function productImages(){
        return $this->hasMany('App\Models\Product\ProductGallery', 'product_id', 'id');
    }
    public function productPriceHistories()
    {
        return $this->hasMany('App\Models\Product\ProuctPriceHistory', 'product_id', 'id');
    }
    public function productStockHistories()
    {
        return $this->hasMany('App\Models\Product\ProductStockHistory', 'product_id', 'id');
    }
    public function scopeRejected($query)
    {
        $query->where('status', 'rejected')->where('delisted', 0);
    }

    public function scopeApprove($query)
    {
        $query->where('status','approved')->where('delisted', 0);
    }
    
    public function scopePending($query)
    {
        $query->where('status','pending');
    }

    public function scopeDelisted($query)
    {
        $query->where('delisted', 1);
    }
}
