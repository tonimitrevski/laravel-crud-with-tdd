<?php

namespace App\Providers;

use App\Post;
use App\Repositories\PostRepository\Contracts\PostRepositoryInterface;
use App\Repositories\PostRepository\Eloquent\PostRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class PostRepositoryProvider extends ServiceProvider
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
        $this->app->bind(PostRepositoryInterface::class, function ($app) {
            return new PostRepository(new Post, new Collection);
        });
    }
}
