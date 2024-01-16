<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    public function __construct(protected Model $model)
    {
    }

    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param int $id
     * 
     * @return mixed
     * 
     */
    public function find(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * @param array $attributes
     * 
     * @return mixed
     * 
     */
    public function create(array $attributes): mixed
    {
        return $this->model->create($attributes);
    }

    /**
     * @param int $id
     * @param array $attributes
     * 
     * @return mixed
     * 
     */
    public function update(int $id, array $attributes): mixed
    {
        $group = $this->find($id);
        if ($group) {
            $group->update($attributes);
            return $group;
        }
        return null;
    }

    /**
     * @param int $id
     * 
     * @return mixed
     * 
     */
    public function delete(int $id): mixed
    {
        $group = $this->find($id);
        if ($group) {
            return $group->delete();
        }
        return false;
    }

    public function model()
    {
        return $this->model;
    }

    /**
     * @param array $attributes
     * @param array $values
     * 
     * @return mixed
     * 
     */
    public function firstOrCreate(array $attributes, array $values = []): mixed
    {
        return $this->model->firstOrCreate($attributes, $values);
    }
}
