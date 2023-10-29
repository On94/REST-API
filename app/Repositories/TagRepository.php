<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository
{
    /**
     * @param Tag $model
     */
    public function __construct(protected Tag $model)
    {
    }

    /**
     * @param string $name
     * @return Tag
     */
    public function store(string $name): Tag
    {
        return $this->model->firstOrCreate(['name' => $name]);
    }
}
