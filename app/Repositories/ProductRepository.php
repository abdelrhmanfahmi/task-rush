<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    protected $dataSearch = [];
    private $product = null;

    public function __construct(Product $product)
    {
        $this->product = $product;
        parent::__construct($product , $this->dataSearch);
    }

    public function get($filters = false, $paginate = true , $with = [] , $withCount = [] , $count = 15 , $orderBy = 'created_at' , $orderType = 'asc' , $search = false)
    {
        $query = $this->model;

        $orderType = $orderType == '' ? 'asc' : $orderType;

        $query = ($filters == false ? $query : $this->filter($query,$filters));
        $query = ($search == false ? $query : $this->search($query,$search));
        
        $query = $query->with($with);
        $query = $query->orderBy($orderBy,$orderType);

        $query = ($paginate ? $query->paginate($count) : $query->all());
        return $query;
    }

}