<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function store(CreateUserRequest $request){
        $user = User::create($request->validated());
        return $this->apiResponse(new UserResource($user), self::STATUS_CREATED, __('site.created_successfully'));
    }

    public function show($user_id){
        $user = User::with([])->find($user_id);
        if($user)
            return $this->apiResponse(new UserResource($user),self::STATUS_OK,__('site.get_successfully'));
        return $this->apiResponse(null, self::STATUS_OK, __('site.there_is_no_data'));
    }

    public function index()
    {
        $users = User::with([])->paginate();
        if(count($users)>0) {
            $paginateData = $this->formatPaginateData($users);
            return $this->apiResponse(UserResource::collection($users), self::STATUS_OK, __('site.get_successfully'),$paginateData);
        }
        return $this->apiResponse([], self::STATUS_OK, __('site.there_is_no_data'));
    }
}
