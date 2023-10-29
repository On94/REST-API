<?php

namespace App\Repositories;

use App\Models\ArticleLike;

class LikeRepository
{
    /**
     * @param ArticleLike $model
     */
    public function __construct(protected ArticleLike $model)
    {
    }

    /**
     * @param array $data
     * @return ArticleLike
     */
    public function store(array $data): ArticleLike
    {
        return $this->model->updateOrCreate(
            ['user_id' => $data['user_id'], 'article_id' => $data['article_id']],
            ['like' => $data['like']]);
    }
}
