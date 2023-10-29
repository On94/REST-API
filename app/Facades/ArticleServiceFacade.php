<?php

namespace App\Facades;

use App\Models\Article;
use App\Services\Interfaces\ArticleServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Article|null store(array $articleData, array $tagsData)
 * @method static Article|null comment(array $data)
 * @method static Article|null like(array $data)
 * @method static LengthAwarePaginator all(string $searchTerm = null)
 */
class ArticleServiceFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ArticleServiceInterface::class;
    }
}
