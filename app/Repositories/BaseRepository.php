<?php


namespace App\Repositories;


abstract class BaseRepository implements RepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    abstract public function getModel();

    public function setModel()
    {
        $this->model = app()->make($this->getModel());
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getAllTrash()
    {
        return $this->model->withTrashed()->get();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function store($attributes = [])
    {
        return $this->model->create($attributes);
    }

    public function update($id, $attributes = [])
    {
        $result = $this->model->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }
        return false;
    }

    public function destroy($id)
    {
        $result = $this->model->find($id);
        if ($result) {
            $result->delete();
            return true;
        }
        return false;
    }

    public function paginate($perPage)
    {
        return $this->model->paginate($perPage);
    }

    public function search($word)
    {
//
    }
}
