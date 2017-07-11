<?php

namespace App\Providers;

use App\Post;
use App\Repositories\PostRepository\Contracts\PostRepositoryCacheInterface;
use App\Repositories\PostRepository\Contracts\PostRepositoryInterface;
use App\Repositories\PostRepository\Eloquent\PostCacheRepository;
use App\Repositories\PostRepository\Eloquent\PostRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class PostRepositoryCacheProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PostRepositoryCacheInterface::class, function ($app) {
            return new PostCacheRepository(new Post, new Collection, new PostRepository(new Post, new Collection));
        });
    }
}
