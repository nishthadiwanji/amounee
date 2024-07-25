<?php

namespace App\Repositories;

use App\Interfaces\EloquentDBInterface;

abstract class EloquentDBRepository implements EloquentDBInterface{

    /**
	 * Name of the model associated with this repository
	 * @var string
	 */
	protected $model;

	/**
	 * Array of method names of which to include as relationships
	 * @var array
	 */

	protected $relationships = [];

	public function __construct(){
		$this->model = app()->make($this->model);
	}


	/**
	 * Get all items
	 * @param  string $columns specific columns to select
	 * @param  string $orderBy column to sort by
	 * @param  string $sort    sort direction
	 * @return Illuminate\Database\Eloquent\Collection
	 */
	public function getAll($columns = null, $orderBy = 'created_at', $sort = 'DESC')
	{

        if($columns){
            return $this->model
	                ->with($this->relationships)
	                ->orderBy($orderBy, $sort)
	                ->get($columns);
        }
        else{
            return $this->model
                ->with($this->relationships)
                ->orderBy($orderBy, $sort)
                ->get();
        }
	    
	}

    public function getAllWithRelationship($relationship = [],$columns = null, $orderBy = 'created_at', $sort = 'DESC'){
        return $this->model
	                ->with($this->relationships)
	                ->orderBy($orderBy, $sort)
	                ->get($columns);
    }
    /**
     * Get paged items
     * @param  integer $paged   Items per page
     * @param  string  $orderBy Column to sort by
     * @param  string  $sort    Sort direction
     * @return Illuminate\Pagination\Paginator
     */
    public function getPaginated($paged = 15, $orderBy = 'created_at', $sort = 'DESC')
    {
        return $this->model
                    ->with($this->relationships)
                    ->orderBy($orderBy, $sort)
                    ->paginate($paged);
    }

    /**
     * Items for select options
     * @param  string $data    column to display in the option
     * @param  string $key     column to be used as the value in option
     * @param  string $orderBy column to sort by
     * @param  string $sort    sort direction
     * @return array           array with key value pairs
     */
    public function getForSelect($data, $key = 'id', $orderBy = 'created_at', $sort = 'DESC')
    {
        return $this->model
                    ->with($this->relationships)
                    ->orderBy($orderBy, $sort)
                    ->pluck($data,$key);
//                    ->lists($data, $key);
    }

    /**
     * Get item by its id
     * @param  integer $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getById($id, $relationship = [])
    {
        return $this->model
                    ->with($relationship)
                    ->find($id);
    }

    /**
     * Get instance of model by column
     * @param  mixed $term    search term
     * @param  string $column column to search
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getByColumn($term, $column = 'slug', $relationship = [])
    {
        return $this->model
                    ->with($relationship)
                    ->where($column,'=',$term)
                    ->get();
    }

     /**
     * Get instance of model by column By Zip Array
     * @param  mixed $term    search term
     * @param  string $column column to search
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getByColumnByZipArray($term, $column = 'slug')
    {
        return $this->model
                    ->with($this->relationships)
                    ->whereIn($column, $term)
                    ->get();
    }

    /**
     * Get item by id or column
     * @param  mixed  $term   id or term
     * @param  string $column column to search
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getActively($term, $column = 'slug')
    {
        if(is_numeric($term))
        {
            return $this->getById($term);
        }
        return $this->getByColumn($term, $column);
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->model, $method], $args);
    }

}
