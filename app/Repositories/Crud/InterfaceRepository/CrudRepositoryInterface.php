<?php

namespace App\Repositories\Crud\InterfaceRepository;

/**
 * RepositoryInterface provides the standard crud operations to be expected of ANY
 * repository.
 */
interface CrudRepositoryInterface
{
    public function __get($name);

    public function all(array $columns = array('*'));

    public function allOrderNew(array $columns = array('*'));

    public function paginate($perPage = 9, $columns = array('*'));

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function deleteWithOneTo($id, array $relations);

    public function deleteWithManyTo($id, array $relations);

    public function find($id, $columns = array('*'));

    public function findBy($field, $value, $columns = array('*'));

    public function findByOrder($attribute, $value, $order, $ascOrDesc, $columns = array('*'));

    public function findByMany($attribute, $value, $columns = array('*'));

    public function getLastResult($attribute, $value);

    public function getFirstResult($attribute, $value);

    public function last();

    public function make(array $with = array());

    public function has($relation, array $with = array());

    public function getFirstBy($key, $value, array $with = array());

    public function getFirstOrFailBy($key, $value, array $with = array());

    public function getFirstById($id, array $with = array());

    public function getManyBy($key, $value, array $with = array());

    public function getManyWithAll(array $with = array());

    public function getManyByWithStatement($attribute, $value, $statement = '=', $columns = array('*'));

    public function relationToOneUpdate($id, array $attributes, array $relations);

    public function syncRelation($id, array $attributes, $relations);

    public function attachRelation($event, $relations, $attachId, array $attributes);

    public function detachRelationAll($event, $relations);

    public function relationToOneCreated($id, array $attributes, $relation);

    public function pluck($key, $name);

    public function getRelation($id, $relation);

    public function getRelationPluck($id, $key, $name, $relation);

    public function selected(array $selected);
}
