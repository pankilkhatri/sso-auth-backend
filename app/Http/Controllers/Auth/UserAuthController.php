<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\CustomerCreationException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Error;
use ErrorException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{   
    use ApiResponseTrait;
    
    public function register(Request $request)
    {   
        try {
            $data = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ]);    
            $data['password'] = bcrypt($request->password);
            $user = User::create($data);    
            $user['token'] = $user->createToken(env('APITOKENKEY'))->accessToken;    
            return $this->successResponse($user,"List");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function login(Request $request) : JsonResponse
    {   
        try {
            $data = $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);
            $credentials = $request->only('email', 'password');
    
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $user['token'] = $user->createToken(env('APITOKENKEY'));
                return response()->json(['error' => false,'user' => $user], 200);
            } else {
                return response()->json(['error' => true,'message' => 'Unauthorized'], 401);
            }
        } catch (\Throwable $th) {
            throw $th;
        }       
    }
}
