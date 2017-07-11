<?php
/**
 * Created by PhpStorm.
 * User: toni
 * Date: 23.5.16
 * Time: 14:52
 */

namespace App\Repositories\PostRepository\Eloquent;

use App\Post;
use App\Repositories\PostRepository\Contracts\PostRepositoryCacheInterface;
use App\Repositories\PostRepository\Contracts\PostRepositoryInterface;
use App\Repositories\Crud\AbstractClass\EloquentCrudRepository;
use Illuminate\Support\Collection;
use Cache;

class PostCacheRepository extends EloquentCrudRepository implements PostRepositoryCacheInterface
{
    /**
     * @var PostRepository
     */
    protected $postRepository;

    /**
     * PostCacheRepository constructor.
     * @param Post $model
     * @param Collection $collection
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(Post $model, Collection $collection, PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
        parent::__construct($model, $collection);
    }

    /**
     * Get all
     * @param array $columns
     * @return mixed
     */
    public function all(array $columns = array('*'))
    {
        return Cache::remember('users', 60, function () use ($columns) {
            return $this->postRepository->all($columns);
        });
    }

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        Cache::pull('users');
        return $this->postRepository->create($attributes);
    }

}
