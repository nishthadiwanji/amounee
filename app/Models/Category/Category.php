<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Encrypted;

class Category extends Model
{
    use SoftDeletes, Encrypted;

    protected $fillable = [
        // 'user_id',
        'parent_category_id',
        'category_name',
        'wocommerce_slug',
        'wocommerce_category_id',
        'commission'
    ];

    public function parent()
    {
        return $this->belongsTo('App\Models\Category\Category', 'parent_category_id', 'id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Category\Category', 'parent_category_id', 'id');
    }
    
    public function products()
    {
        return $this->hasMany('App\Models\Category\Category', 'category_id', 'id');
    }
    
    public function getAllChildCategories()
    {
        $children = collect();
        $sub_categories = $this->children()->get();
        if ($sub_categories->count()) {
            foreach ($sub_categories as $sub_category) {
                $children->push($sub_category);
                if ($sub_category->children()->count()) {
                    $children->push($this->getChildren($sub_category));
                }
            }
        }
        return $children;
    }

    private function getChildren($category)
    {
        $children = collect();
        foreach ($category->children()->get() as $child) {
            $children->push($child);
            if ($child->children()->count()) {
                $children->push($this->getChildren($child));
            }
        }
        return $children;
    }

    public function artisans()
    {
        return $this->hasMany('App\Models\Artisan\Artisan', 'category_id', 'id');
    }
    
    public function commissions()
    {
        return $this->hasMany('App\Models\Category\CommissionHistory', 'sub_category_id', 'id');
    }
}
