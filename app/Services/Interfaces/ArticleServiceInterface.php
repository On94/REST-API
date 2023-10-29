<?php

namespace App\Services\Interfaces;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ArticleServiceInterface
{
    /**
     * @param array $articleData
     * @param array $tagsData
     * @return Article|null
     */
    public function store(array $articleData, array $tagsData): ?Article;

    /**
     * @param array $data
     * @return Article|null
     */
    public function comment(array $data): ?Article;

    /**
     * @param array $data
     * @return Article|null
     */
    public function like(array $data): ?Article;

    /**
     * @param string|null $searchTerm
     * @return LengthAwarePaginator
     */
    public function all(string $searchTerm = null): LengthAwarePaginator;
}
