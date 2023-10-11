<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['login' , 'register']]);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'message' => 'Wrong credentials'
            ]);
        }

        $token = $user->createToken('my-token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'Type' => 'Bearer',
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create($data);

        if($user){
            $user->assignRole('customer');
            $userSaved = $user->createToken('Access Token');
            $token = $userSaved->plainTextToken;

            return response()->json([
            'message' => 'user registered successfully',
            'accessToken'=> $token,
            ],201);
        }else{
            return response()->json(['error'=>'something went wrong']);
        }
    }

    public function logout()
    {
        // Auth::logout();
        auth()->guard('web')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }
}
