<?php

namespace App\Repositories\Category;

use App\Models\Category\Category;
use App\Repositories\EloquentDBRepository;
use Sentinel;

class CategoryRepository extends EloquentDBRepository
{

    protected $category;

    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    public function getInformation($id){
        $category = $this->model->with(['artisans'])->find($id);
        return $category;
    }

    public function search($search = NULL) {
        return $this->model->whereNotNull('parent_category_id')
            ->when($search != NULL, function ($query) use ($search) {
                return $query->where('category_name', 'like', '%' . $search . '%')
                    ->orWhereHas('parent', function ($query) use ($search) {
                        return $query->where('category_name', 'like', '%' . $search . '%');
                    });
            });
    }

    public function createCommission(Category $category, $attributes)
    {
        $data = [
            'commission' => $attributes['commission'],
        ];
        $response = $category->update($data);
        return $response;
    }
    public function createCommissionHistory(Category $category, $attributes)
    {
        $data = [
            'commission' => $attributes['commission'],
            'sub_category_id' => $category->id,
            'user_id' => Sentinel::getUser()->id
        ];
        $response = $category->commissions()->create($data);
        return $response;
    }
    //api function
    public function getAllCategories($search = NULL) {
        return $this->model->whereNull('parent_category_id')
            ->when($search != NULL, function ($query) use ($search) {
                return $query->where('category_name', 'like', '%' . $search . '%')
                    ->orWhereHas('parent', function ($query) use ($search) {
                        return $query->where('category_name', 'like', '%' . $search . '%');
                    });
            });
    }
}