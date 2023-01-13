<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function store(CategoryRequest $request){
        $category = Category::create($request->validated());
            return $this->apiResponse(new CategoryResource($category), self::STATUS_CREATED, __('site.created_successfully'));
    }

    public function show($category_id){
        $category = Category::with([])->find($category_id);
        if($category)
            return $this->apiResponse(new CategoryResource($category),self::STATUS_OK,__('site.get_successfully'));
        return $this->apiResponse(null, self::STATUS_OK, __('site.there_is_no_data'));
    }

    public function update(CategoryRequest $request,$category_id){
        $category =Category::with([])->find($category_id);
        $category->fill($request->validated());
        $category->save();
        if($category)
            return $this->apiResponse(new CategoryResource($category),self::STATUS_OK,__('site.updated_successfully'));
        return $this->apiResponse(null, self::STATUS_OK, __('site.there_is_no_data'));
    }

    public function destroy($category_id){
        $category = Category::destroy($category_id);
        if($category)
            return $this->apiResponse(true,self::STATUS_OK,__('site.deleted_successfully'));
        return $this->apiResponse(null, self::STATUS_OK, __('site.there_is_no_data'));
    }

    public function index()
    {
        $categories = Category::with([])->paginate();
        if(count($categories)>0) {
            $paginateData = $this->formatPaginateData($categories);
            return $this->apiResponse(CategoryResource::collection($categories), self::STATUS_OK, __('site.get_successfully'),$paginateData);
        }
        return $this->apiResponse([], self::STATUS_OK, __('site.there_is_no_data'));
    }
}
