<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductRepository $productRepository)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $products = $this->productRepository->get(false , true , [] , [], 10);
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request)
    {
        try{
            $data = $request->validated();
            $this->productRepository->store($data);
            return response()->json(['message' => 'product stored successfully'] , 201);
        }catch(\Exception $e){
            return $e;
        }
    }

    public function show($id)
    {
        return ProductResource::make($this->productRepository->find($id));
    }

    public function update(UpdateProductRequest $request , $id)
    {
        try{
            $data = $request->validated();
            $this->productRepository->update($data,$id);
            return response()->json(['message' => 'product updated successfully'] , 202);
        }catch(\Exception $e){
            return $e;
        }
    }

    public function destroy($id)
    {
        $this->productRepository->destroy($id);
        return response()->json(['message' => 'product deleted successfully']);
    }
}
