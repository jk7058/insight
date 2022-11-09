<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class UserController extends Controller
{
    public function loginUser(Request $request)
    {
        $credentials = $request->only('userEmail', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'userEmail' => 'required|email',
            'password' => 'required|string'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        //Request is validated
        //Crean token
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Login credentials are invalid.',
                    'data' => [],
                ], 400);
            }

        } catch (JWTException $e) {

            return response()->json([
                'status' => 401,
                'message' => 'Could not create token.',
            ], 500);
        }

        //Token created, return with success response and jwt token
        return response()->json([
            'status' => 200,
            'message' => 'Login Successful.',
            'token' => $token,
            'data' => Auth::user()
        ]);
    }


    public function logout(Request $request)
    {

        try {

            auth()->logout(true);

            JWTAuth::invalidate(JWTAuth::getToken());

            JWTAuth::parseToken()->invalidate();


            return response()->json([
                'status' => 200,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'status' => 201,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function refresh()
    {
        return  JWTAuth::refresh(JWTAuth::getToken());;
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            //'expires_in' => auth()->factory()->getTTL() * 60
            'expires_in' => auth('api')->factory()->getTTL() * 60000
        ]);
    }
}
