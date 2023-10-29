<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ArticleRepository
{
    /**
     * @param Article $article
     */
    public function __construct(protected Article $article)
    {
    }

    /**
     * @param $data
     * @return Article
     */
    public function store($data): Article
    {
        return $this->article->create($data);
    }

    /**
     * @param int $articleId
     * @return Article|null
     */
    public function article(int $articleId): ?Article
    {
        return $this->article->where('id',$articleId)->with(['tags', 'likes' => function ($query) {
            $query->with('user', function ($q) {
                $q->select('id', 'last_name', 'email');
            });
        }, 'comments' => function ($query) {
            $query->with('user', function ($q) {
                $q->select('id', 'last_name', 'email');
            });
        }])->first();
    }

    /**
     * @param string|null $searchTerm
     * @return LengthAwarePaginator
     */
    public function all(string $searchTerm = null): LengthAwarePaginator
    {
        return $this->article->with(['tags', 'likes' => function ($query) {
            $query->with('user', function ($q) {
                $q->select('id', 'last_name', 'email');
            });
        }, 'comments' => function ($query) {
            $query->with('user', function ($q) {
                $q->select('id', 'last_name', 'email');
            });
        }])->when($searchTerm, function (Builder $query) use ($searchTerm) {
            return $query->whereHas('tags', function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%$searchTerm%");
            });
        })->paginate();
    }
}
