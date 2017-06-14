<?php
namespace App\Repositories\Crud\AbstractClass;

use App\Repositories\Criteria\AbstractClass\Criteria;
use App\Repositories\Criteria\InterfaceCriteria\CriteriaInterface;
use App\Repositories\Crud\InterfaceRepository\CrudRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * The Abstract Repository provides default implementations of the methods defined
 * in the base repository interface. These simply delegate static function calls
 * to the right eloquent model based on the $model.
 */
abstract class EloquentCrudRepository implements CrudRepositoryInterface, CriteriaInterface
{
    /**
     * The eloquent model
     * @var Model
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $criteria;

    /**
     * @var bool
     */
    protected $skipCriteria = false;

    /**
     * The eloquent model
     * @var
     */
    private $oldModel;

    /**
     * Prevents from overwriting same criteria in chain usage
     * @var bool
     */
    protected $preventCriteriaOverwriting = true;

    /**
     * EloquentCrudRepository constructor.
     * @param Model $model
     * @param Collection $collection
     */
    public function __construct($model, Collection $collection)
    {
        $this->model = $model;
        $this->oldModel = $model;
        $this->criteria = $collection;
        $this->resetScope();
    }

    /**
     * find method
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if (method_exists($this, $name)) {
            return call_user_func([$this, $name]);
        }

        $message = 'This method does not exist';

        throw new \Exception($message);
    }

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Update
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        return $this->model->where($attribute, '=', $id)->first()->update($data);
    }


    /**
     * Get all
     * @param array $columns
     * @return mixed
     */
    public function all(array $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->get($columns);
    }

    /**
     * Get all
     * @param array $columns
     * @return mixed
     */
    public function allOrderNew(array $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->orderBy('id', 'desc')->get($columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 9, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * Delete
     * @param $ids
     * @return mixed
     */
    public function delete($ids)
    {
        return $this->model->destroy(array($ids));
    }

    /**
     * Delete with to one
     * @param $id
     * @param array $relations
     * @return void
     */
    public function deleteWithOneTo($id, array $relations)
    {
        $parent_model = $this->find($id);
        foreach ($relations as $relation) {
            $parent_model->$relation()->delete();
        }
    }

    /**
     * Delete with to many
     * @param $id
     * @param array $relations
     * @return void
     */
    public function deleteWithManyTo($id, array $relations)
    {
        $parent_model = $this->find($id);

        foreach ($relations as $relation) {
            $parent_model->$relation()->detach();
        }
    }

    /**
     * find in query
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->find($id, $columns);
    }

    /**
     * Find by attribute where not
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param $order
     * @param $ascOrDesc
     * @param array $columns
     * @return mixed
     */
    public function findByOrder($attribute, $value, $order, $ascOrDesc, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->where($attribute, '=', $value)->orderBy($order, $ascOrDesc)->first($columns);
    }

    /**
     * Find by attribute where not
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findByMany($attribute, $value, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->where($attribute, '=', $value)->orderBy('id', 'desc')->get($columns);
    }

    /**
     * @param $column
     * @param $search
     * @param $selected
     * @return mixed
     */
    public function search($column, $search, $selected)
    {
        $this->applyCriteria();
        return $this->model->where($column, 'LIKE', "%{$search}%")->select($selected)->get();
    }


    /**
     * @param array $selected
     * @return $this
     */
    public function selected(array $selected)
    {
        $this->applyCriteria();

        $this->model->select($selected);
        return $this;
    }

    /**
     * Find by attribute ang get last result
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function getLastResult($attribute, $value)
    {
        $this->applyCriteria();
        return $this->model->where($attribute, '=', $value)->orderBy('id', 'desc')->first();
    }

    /**
     * Find by attribute ang get first result
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function getFirstResult($attribute, $value)
    {
        $this->applyCriteria();
        return $this->model->where($attribute, '=', $value)->orderBy('id', 'asc')->first();
    }

    /**
     * Get last result
     * @return mixed
     */
    public function last()
    {
        $this->applyCriteria();
        return $this->model->orderBy('id', 'desc')->first();
    }

    /**
     * Make a new instance of the entity to query on
     * @param array $with
     * @return mixed
     */
    public function make(array $with = array())
    {
        return $this->model->with($with);
    }

    /**
     * Return all results that have a required relationship
     * @param $relation
     * @param array $with
     * @return mixed
     */
    public function has($relation, array $with = array())
    {
        $entity = $this->make($with);

        return $entity->has($relation)->get();
    }

    /**
     *
     * Find a single entity by key value
     * @param $key
     * @param $value
     * @param array $with
     * @return mixed
     */
    public function getFirstBy($key, $value, array $with = array())
    {
        return $this->make($with)->where($key, '=', $value)->first();
    }

    /**
     * Find a single entity or fail by key value
     * @param $key
     * @param $value
     * @param array $with
     * @return mixed
     */
    public function getFirstOrFailBy($key, $value, array $with = array())
    {
        return $this->make($with)->where($key, '=', $value)->firstOrFail();
    }

    /**
     * Find a single entity by Id value
     * @param $id
     * @param array $with
     * @return mixed
     */
    public function getFirstById($id, array $with = array())
    {
        return $this->make($with)->where('id', '=', $id)->first();
    }

    /**
     * Find many entities by key value
     * @param $key
     * @param $value
     * @param array $with
     * @return mixed
     */
    public function getManyBy($key, $value, array $with = array())
    {
        return $this->make($with)->where($key, '=', $value)->get();
    }

    /**
     * Get all with relation
     * @param array $with
     * @return mixed
     */
    public function getManyWithAll(array $with = array())
    {
        $this->applyCriteria();
        return $this->make($with)->orderBy('id', 'ASC')->get();
    }

    /**
     * Find many entities by key value with dynamic statement
     * @param $attribute
     * @param $value
     * @param string $statement
     * @param array $columns
     * @return mixed
     */
    public function getManyByWithStatement($attribute, $value, $statement = '=', $columns = array('*'))
    {
        return $this->model->where($attribute, $statement, $value)->get($columns);
    }

    /**
     * Update To One Relation
     * @param $id
     * @param array $attributes
     * @param array $relations
     * @return void
     */
    public function relationToOneUpdate($id, array $attributes, array $relations)
    {
        $object = $this->find($id);

        foreach ($relations as $relation) {
            if ($object->$relation) {
                $object->$relation->update($attributes);
            }
        }
    }

    /**
     * @param $id
     * @param array $attributes
     * @param $relations
     * @return void
     */
    public function syncRelation($id, array $attributes, $relations)
    {
        $object = $this->find($id);

        $object->$relations()->sync($attributes);
    }

    /**
     * @param object $event
     * @param array $attributes
     * @param string $attachId
     * @param string $relations
     * @return void
     */
    public function attachRelation($event, $relations, $attachId, array $attributes = [])
    {
        $event->$relations()->attach($attachId, $attributes);
    }

    /**
     * @param $event
     * @param $relations
     */
    public function detachRelationAll($event, $relations)
    {
        $event->$relations()->detach();
    }

    /**
     * Update To One Relation
     * @param $id
     * @param array $attributes
     * @param string $relation
     * @return mixed
     */
    public function relationToOneCreated($id, array $attributes, $relation)
    {
        $object = $this->find($id);

        $credentials = $object->$relation()->create($attributes);

        return $credentials;
    }

    /**
     * @param array $key
     * @param $name
     * @return mixed
     */
    public function pluck($key, $name)
    {
        return $this->model->pluck($name, $key);
    }

    /**
     * @param $id
     * @param string $relation
     * @return mixed
     */
    public function getRelation($id, $relation)
    {
        $object = $this->find($id);

        return $object->$relation;
        
    }

    /**
     * @param $id
     * @param $key
     * @param $name
     * @param string $relation
     * @return mixed
     */
    public function getRelationPluck($id, $key, $name, $relation)
    {
        $object = $this->find($id);

        return $object->$relation()->pluck($name, $key);

    }
    
    public function relationUpdate($relation, array $attributes)
    {
        return $relation->update($attributes);
    }

    /**
     * @return $this
     */
    public function resetScope()
    {
        $this->skipCriteria(false);
        return $this;
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);
        return $this;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria)
    {
        if ($this->preventCriteriaOverwriting) {
            // Find existing criteria
            $key = $this->criteria->search(function ($item) use ($criteria) {
                return (is_object($item) and (get_class($item) == get_class($criteria)));
            });

            // Remove old criteria
            if (is_int($key)) {
                $this->criteria->offsetUnset($key);
            }
        }

        $this->criteria->push($criteria);
        return $this;
    }

    /**
     * @return $this
     */
    public function applyCriteria()
    {
        if ($this->skipCriteria === true) {
            $this->model = $this->oldModel;
            return $this;
        }

        foreach ($this->getCriteria() as $criteria) {
            if ($criteria instanceof Criteria) {
                $this->model = $criteria->apply($this->model, $this);
            }
        }
        return $this;
    }

    public function removeCriteriaAll()
    {
        $this->model = $this->oldModel;
        $this->criteria = new Collection();
        return $this;
    }
}
