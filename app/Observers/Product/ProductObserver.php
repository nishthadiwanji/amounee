<?php

namespace App\Observers\Product;

use App\Models\Product\Product;

class ProductObserver {
	public function created(Product $product) {
    	log_activity_by_user('product_log',$product,'Product has been added in the system');
	}
	public function updated(Product $product){
    	log_activity_by_user('product_log',$product,'Product details have been updated in the system');
	}
    public function deleted(Product $product){
        $product->photo()->delete();
    	log_activity_by_user('product_log',$product,'Product has been banned in the system');
    }
    public function restored(Product $product){
        $product->photo()->withTrashed()->restore();
    	log_activity_by_user('product_log',$product,'Product has been activated in the system');
    }
}
