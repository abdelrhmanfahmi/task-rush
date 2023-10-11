<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = $this->userRepository->get(false , true , ['orders'] , [], 10);
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        try{
            $data = $request->validated();
            $user = $this->userRepository->store($data);
            $user->assignRole('customer');
            return response()->json(['message' => 'user stored successfully'] , 201);
        }catch(\Exception $e){
            return $e;
        }
    }

    public function show($id)
    {
        return UserResource::make($this->userRepository->find($id));
    }

    public function update(UpdateUserRequest $request , $id)
    {
        try{
            $data = $request->validated();
            $this->userRepository->update($data,$id);
            return response()->json(['message' => 'user updated successfully'] , 202);
        }catch(\Exception $e){
            return $e;
        }
    }

    public function destroy($id)
    {
        $this->userRepository->destroy($id);
        return response()->json(['message' => 'user deleted successfully']);
    }
}
