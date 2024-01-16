<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    public function __construct(protected Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $group = $this->find($id);
        if ($group) {
            $group->update($attributes);
            return $group;
        }
        return null;
    }

    public function delete($id)
    {
        $group = $this->find($id);
        if ($group) {
            return $group->delete();
        }
        return false;
    }
}
