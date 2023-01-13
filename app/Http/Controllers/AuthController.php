<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
    public function register(AuthRegisterRequest $request)
    {
        $user = User::create($request->validated());
        $token = Auth::login($user);

        return $this->apiResponse(['user'=>new UserResource($user),'token'=>$token],self::STATUS_OK,__('site.code_correct'));
    }
    public function login(AuthLoginRequest $request) {

        $credentials = $request->only(['email', 'password']);

        $token = Auth::attempt($credentials);

        if (!$token) {
            return $this->apiResponse(null, self::STATUS_NOT_FOUND, __('site.credentials_not_match_records'));
        }
        return $this->apiResponse(['user' => new UserResource(Auth::user()), 'token' => $token ], self::STATUS_OK, __('site.successfully_logged_in'));
    }

    public function logout()
    {
        Auth::logout();
        return $this->apiResponse(null,self::STATUS_OK,__('site.logout_success'));
    }
    public function refresh()
    {
        try {
            $token = Auth::refresh();
            return $this->apiResponse(['user' => new UserResource(Auth::user()), 'token' => $token ],self::STATUS_OK,__('site.refresh_success'));
        } catch (\Exception $exception){
            return $this->apiResponse(null,self::STATUS_UNAUTHORIZED,__('site.invalid_token'));
        }
    }
}
