<?php

namespace App\Services;

use App\Models\Article;
use App\Repositories\ArticleRepository;
use App\Repositories\CommentRepository;
use App\Repositories\LikeRepository;
use App\Repositories\TagRepository;
use App\Services\Interfaces\ArticleServiceInterface;
use App\Traits\Base64Uploader;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ArticleService implements ArticleServiceInterface
{
    /**
     * Upload base64 format Image
     */
    use Base64Uploader;

    /**
     * @param ArticleRepository $articleRepository
     * @param TagRepository $tagRepository
     * @param CommentRepository $commentRepository
     * @param LikeRepository $likeRepository
     */
    public function __construct
    (
        protected ArticleRepository $articleRepository, protected TagRepository $tagRepository,
        protected CommentRepository $commentRepository, protected LikeRepository $likeRepository
    )
    {
    }

    /**
     * @param array $articleData
     * @param array $tagsData
     * @return ?Article
     */
    public function store(array $articleData, array $tagsData): ?Article
    {
        $articleData['image'] = $this->storeAndGetFIlePat($articleData['image'], 'images');

        $article = $this->articleRepository->store($articleData);

        $tags = [];

        foreach ($tagsData as $tagName) {
            $tag = $this->tagRepository->store($tagName);
            $tags[] = $tag;
        }

        $tagIds = collect($tags)->pluck('id')->toArray();
        $article->tags()->sync($tagIds);

        return $this->articleRepository->article($article->id);
    }

    /**
     * @param array $data
     * @return Article|null
     */
    public function comment(array $data): ?Article
    {
        $data['user_id'] = Auth::id();
        $this->commentRepository->store($data);

        return $this->articleRepository->article($data['article_id']);
    }

    /**
     * @param array $data
     * @return Article|null
     */
    public function like(array $data): ?Article
    {
        $data['user_id'] = Auth::id();
        $this->likeRepository->store($data);

        return $this->articleRepository->article($data['article_id']);
    }

    public function all(string $searchTerm = null): LengthAwarePaginator
    {
       return $this->articleRepository->all($searchTerm);
    }
}
