<?php
/**
 * Created by PhpStorm.
 * User: toni
 * Date: 23.5.16
 * Time: 14:52
 */

namespace App\Repositories\PostRepository\Eloquent;

use App\Repositories\PostRepository\Contracts\PostRepositoryInterface;
use App\Repositories\Crud\AbstractClass\EloquentCrudRepository;

class PostRepository extends EloquentCrudRepository implements PostRepositoryInterface
{

}
