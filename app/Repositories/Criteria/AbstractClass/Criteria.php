<?php
/**
 * Created by PhpStorm.
 * User: toni
 * Date: 14.6.16
 * Time: 13:55
 */

namespace App\Repositories\Criteria\AbstractClass;

use App\Repositories\Crud\InterfaceRepository\CrudRepositoryInterface;

abstract class Criteria
{
    abstract public function apply($model, CrudRepositoryInterface $repository);
}