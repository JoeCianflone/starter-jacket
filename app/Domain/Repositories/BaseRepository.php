<?php declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\BaseModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;

class BaseRepository
{
    public function __construct(protected Model $model)
    {
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function allowed(SupportCollection $request): array
    {
        return $request->only($this->model->getFillable())->toArray();
    }

    public function update(string | int $id, array $data)
    {
        return tap($this->model->where('id', $id)->first())->update($data);
    }

    public function updateModel(Model $model, array $fields)
    {
        foreach ($fields as $key => $value) {
            $model->{$key} = $value;
        }

        return $model->save();
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->get();
    }

    /**
     * @param string $id
     * @return Model
     */
    public function getByID(string $id): Model
    {
        return $this->model
                    ->where('id', $id)
                    ->first();
    }

    public function getAllIn(array $ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    public function deleteByID(string $id)
    {
        return $this->delete('id', $id);
    }

    public function delete(string $col, string $value)
    {
        return $this->model->where($col, $value)->first()->delete();
    }

    public function deleteAll(string $col, array $values)
    {
        return $this->model->whereIn($col, collect($values))->delete();
    }

    public function search(Model | Builder $model, ?string $terms, ?array $fields, ?int $count, string $orderBy = 'created_at', string $orderDirection = 'asc')
    {
        return $model
                ->byUserSearch(terms: $terms, fields: $fields)
                ->orderBy($orderBy, $orderDirection)
                ->paginate($count ?? 10);
    }
}
