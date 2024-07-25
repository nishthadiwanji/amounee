<?php

namespace Amounee\Http\Controllers\v1\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryRepository;
use Exception;
use Log;
use DB;

class CategoryController extends Controller
{

    protected $category_repo;

    public function __construct(CategoryRepository $category_repo)
    {
        $this->category_repo = $category_repo;
    }

    public function index()
    {
        try {
            $categories = $this->category_repo->getAllCategories()->select('id','category_name')->get();
            $categories = $categories->transform(function($item){
                return [
                    'id_token' => $item->id_token,
                    'name'  => $item->category_name
                ];
            });
            return $this->responseSuccessWithData([
                'categories' => $categories
            ]);
        } catch(Exception $e) {
            Log::error(__('log.getting_category_list'), [$e->getMessage()]);
            return $this->responseInternalServerError('Unable to load categories');
        }
    }
    
}