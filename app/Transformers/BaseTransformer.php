<?php

namespace App\Transformers;

abstract class BaseTransformer{
    protected $transformed_results;
    public function __construct(){
        $this->transformed_results = array();
    }
    abstract function transform($single_result);
    public function transformCollection($collection){
        foreach($collection as $result){
            $this->transformed_results = array_merge($this->transformed_results, array($this->transform($result)));
        }
        return $this->transformed_results;
    }
}
