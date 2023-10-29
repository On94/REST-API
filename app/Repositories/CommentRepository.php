<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository
{
    /**
     * @param Comment $model
     */
    public function __construct(protected Comment $model)
    {
    }

    /**
     * @param array $data
     * @return Comment
     */
    public function store(array $data):Comment
    {
        return $this->model->create($data);
    }
}
