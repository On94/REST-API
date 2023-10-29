<?php

namespace App\Providers;

use App\Repositories\ArticleRepository;
use App\Repositories\CommentRepository;
use App\Repositories\LikeRepository;
use App\Repositories\TagRepository;
use App\Services\ArticleService;
use App\Services\Interfaces\ArticleServiceInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ArticleServiceInterface::class, function ($app) {
            return new ArticleService(
                $app->make(ArticleRepository::class),
                $app->make(TagRepository::class),
                $app->make(CommentRepository::class),
                $app->make(LikeRepository::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('base64image', function ($attribute, $value, $parameters, $validator) {
            if (str_starts_with($value, 'data:image/')) {
                $base64Data = substr($value, strpos($value, ',') + 1);
                $decoded = base64_decode($base64Data, true);
                if ($decoded === false || !@getimagesizefromstring($decoded)) {
                    return false;
                }
                return true;
            }
            return false;
        });
    }
}
