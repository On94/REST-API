<?php

namespace App\Http\Controllers;

use App\Facades\ArticleServiceFacade;
use App\Http\Requests\ArticleAllRequest;
use App\Http\Requests\ArticleCommentRequest;
use App\Http\Requests\ArticleLikeDislikeRequest;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Resources\ApiResponse;


class ArticleController extends Controller
{

    /**
     * @param ArticleStoreRequest $request
     * @return ApiResponse
     */
    public function store(ArticleStoreRequest $request): ApiResponse
    {
        return ApiResponse::make([
            'data'=> ArticleServiceFacade::store($request->except('tags'),$request->only('tags')['tags'] ?? [])
        ]);
    }

    /**
     * @param ArticleCommentRequest $request
     * @return ApiResponse
     */
    public function comment(ArticleCommentRequest $request): ApiResponse
    {
        return ApiResponse::make([
            'data'=> ArticleServiceFacade::comment($request->validated())
        ]);
    }

    /**
     * @param ArticleLikeDislikeRequest $request
     * @return ApiResponse
     */
    public function like(ArticleLikeDislikeRequest $request): ApiResponse
    {
        return ApiResponse::make([
            'data'=> ArticleServiceFacade::like($request->validated())
        ]);
    }

    /**
     * @param ArticleAllRequest $request
     * @return ApiResponse
     */
    public function all(ArticleAllRequest $request): ApiResponse
    {
        return ApiResponse::make([
            'data'=> ArticleServiceFacade::all($request->tag)
        ]);
    }
}
