<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): Model|null
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool|null
    {
        $record = $this->find($id);
        return $record ? $record->update($data) : null;
    }

    public function delete(int $id): bool|null
    {
        $record = $this->find($id);
        return $record ? $record->delete() : null;
    }
}
