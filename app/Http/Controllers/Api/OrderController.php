<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderRepository $orderRepository)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $orders = $this->orderRepository->get(false , true , ['products'] , [], 10);
        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();
        try{
            $data = $request->validated();
            $orderData = $request->only('name', 'phone' , 'address' , 'payment_type');
            $orderData['user_id'] = auth()->user()->id;
            $order = $this->orderRepository->store($orderData);

            $order->products()->attach($data['products']);
            DB::commit();
            return response()->json(['message' => 'order stored successfully'] , 201);
        }catch(\Exception $e){
            return $e;
        }
    }

    public function show($id)
    {
        return OrderResource::make($this->orderRepository->find($id));
    }

    public function update(UpdateOrderRequest $request , $id)
    {
        DB::beginTransaction();
        try{
            $data = $request->validated();
            $orderData = $request->only('name', 'phone' , 'address' , 'payment_type');
            $this->orderRepository->update($orderData,$id);

            $order = $this->orderRepository->find($id);
            $order->products()->sync($data['products']);
            DB::commit();
            return response()->json(['message' => 'order updated successfully'] , 202);
        }catch(\Exception $e){
            return $e;
        }
    }

    public function destroy($id)
    {
        $order = $this->orderRepository->find($id);
        $order->products()->detach();
        $this->orderRepository->destroy($id);
        return response()->json(['message' => 'order deleted successfully']);
    }
}
