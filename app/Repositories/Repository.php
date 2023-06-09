<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class Repository
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass;

    /**
     * Paginate default
     *
     * @var string
     */
    protected $paginate = 20;

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function newQuery()
    {
        return app()->make($this->modelClass)->newQuery();
    }

    /**
     * @param mixed|null $query
     * @param int $take
     * @param bool $paginate
     * @return \Illuminate\Support\Collection
     */
    public function doQuery($query = null, int $take = 20, bool $paginate = true)
    {
        if (!$query) {
            $query = $this->newQuery();
        }
        if ($paginate) {
            return $query->paginate($take);
        }
        if ($take) {
            return $query->take($take)->get();
        }
        return $query->get();
    }
    /**
     * @param int $take
     * @param bool $paginate
     * @return \Illuminate\Support\Collection
     */
    public function getAll(int $take = 20, bool $paginate = false)
    {
        return $this->doQuery(null, $take, $paginate);
    }

    /**
     * @param int $id
     * @param bool $fail
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findByID(int $id, bool $fail = true)
    {
        if ($fail) {
            return $this->newQuery()->findOrFail($id);
        }
        return $this->newQuery()->find($id);
    }

    /**
     * @param mixed $value
     * @return object|null
     */
    public function findOne($value)
    {
        return $this->newQuery()
            ->where(app()->make($this->modelClass)->getKeyName(), $value)
            ->first();
    }

    /**
     * @return object|null
     */
    public function getLast()
    {
        return $this->newQuery()
            ->orderBy(app()->make($this->modelClass)->getKeyName(), 'desc')
            ->first();
    }


    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function factory(array $data = [])
    {
        $model = $this->newQuery()->getModel()->newInstance();
        $this->fillModel($model, $data);
        return $model;
    }

    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function fillModel(Model $model, array $data = [])
    {
        $model->fill($data);
    }

    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(Model $model)
    {
        DB::transaction(function () use ($model) {
            $model->save();
        });
        return $model;
    }

    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function destroy(Model $model)
    {
        DB::transaction(function () use ($model) {
            $model->delete();
        });
        return $model;
    }

    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $model = $this->factory($data);
        return $this->save($model);
    }

    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(Model $model, array $data = [])
    {
        $this->fillModel($model, $data);
        return $this->save($model);
    }
}
