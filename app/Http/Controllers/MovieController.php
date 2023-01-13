<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMovieRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Traits\FileHandler;
use Illuminate\Http\Request;

class MovieController extends Controller {
    use FileHandler;

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function store(CreateMovieRequest $request) {
        $movie = Movie::create($request->validated());
        if ($request->has('image')) {
            $movie->image = $this->storeFile($request->image, 'movies', false);
        }
        $movie->load(['category']);
        return $this->apiResponse(new MovieResource($movie), self::STATUS_CREATED, __('site.created_successfully'));
    }

    public function show($movie_id) {
        $movie = Movie::with([])->find($movie_id);
        if ($movie)
            return $this->apiResponse(new MovieResource($movie), self::STATUS_OK, __('site.get_successfully'));
        return $this->apiResponse(null, self::STATUS_OK, __('site.there_is_no_data'));
    }

    public function update(UpdateMovieRequest $request, $movie_id) {
        $movie = Movie::with([])->find($movie_id);
        $movie->fill($request->validated());
        if ($request->has('image')) {
            $movie->image = $this->updateFile($request->image, $movie->image, 'movies', false);
        }
        $movie->save();
        if ($movie)
            return $this->apiResponse(new MovieResource($movie->load(['category'])), self::STATUS_OK, __('site.updated_successfully'));
        return $this->apiResponse(null, self::STATUS_OK, __('site.there_is_no_data'));
    }

    public function destroy($movie_id) {
        $movie = Movie::destroy($movie_id);
        if ($movie)
            return $this->apiResponse(true, self::STATUS_OK, __('site.deleted_successfully'));
        return $this->apiResponse(null, self::STATUS_OK, __('site.there_is_no_data'));
    }

    public function index() {
        $movies = Movie::with(['category'])->paginate();
        if (count($movies) > 0) {
            $paginateData = $this->formatPaginateData($movies);
            return $this->apiResponse(MovieResource::collection($movies), self::STATUS_OK, __('site.get_successfully'), $paginateData);
        }
        return $this->apiResponse([], self::STATUS_OK, __('site.there_is_no_data'));
    }

    public function search(SearchRequest $request) {
        $search_statement = $request->search_statement;
        $filtered_categories = [];
        if ($request->has('filter_category'))
            foreach ($request->filter_category as $key=>$filtered_category) {
                $filtered_categories[] = $filtered_category;
            }
        $query = Movie::with('category')->where('title', 'like', '%' . $search_statement . '%');

        if ($filtered_categories)
            $query->whereIn('category_id', $filtered_categories);

        if ($request->has('min_rate'))
            $query->whereBetween('rate',[$request->min_rate,5]);

        if ($request->has('sort_by')) {

            if ($request->sort_by == 'newest') {
                $movies = $query->orderBy('created_at')->paginate(10);
            } else {
                if ($request->sort_by == 'alphabet_desc') {
                    $movies = $query->orderBy('title', 'desc')->paginate(10);
                } else {
                    if ($request->sort_by == 'alphabet_asc') {
                        $movies = $query->orderBy('title')->paginate(10);
                    }
                }
            }
        } else {
            $movies = $query->orderBy('created_at')->paginate(10);
        }
        if (count($movies) > 0) {
            $paginateData = $this->formatPaginateData($movies);
            return $this->apiResponse(MovieResource::collection($movies), self::STATUS_OK, __('site.get_successfully'), $paginateData);
        }
        return $this->apiResponse([], self::STATUS_OK, __('site.there_is_no_data'));
    }
}

