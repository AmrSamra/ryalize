<?php

namespace App\Infrastructure;

abstract class Model extends Relations
{
    public string $table;

    protected array $fillable = [];

    protected array $hidden = [];

    protected array $data = [];

    protected DB $builder;

    /**
     * Model constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            $this->data[$key] = $value;
        }
        $this->builder = $this->builder();
    }

    public function __get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public function __set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Create new model
     * @param array $data
     * @return object
     */
    public function create(array $data): object
    {
        $fillable = [];
        array_walk(array_keys($data), function (string $attribute) use ($data, &$fillable) {
            if (in_array($attribute, $this->fillable)) {
                $fillable[$attribute] = $data[$attribute];
            }
        });
        $id = $this->builder()->insert([$fillable]);
        return Self::find($id);
    }

    /**
     * Update model
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        $fillable = [];
        array_walk(array_keys($data), function (string $attribute) use ($data, &$fillable) {
            if (in_array($attribute, $this->fillable)) {
                $fillable[$attribute] = $data[$attribute];
            }
        });
        return $this->builder->where(['id', '=', $this->id])
            ->update([$fillable]);
    }

    /**
     * Set where clause
     * @param string|array|callable $column
     * @param mixed $operator
     * @param mixed $value
     * @return object
     */
    public function where(string|array|callable $column, mixed $operator = null, mixed $value = "`UNDEFINED`"): object
    {
        if (is_array($column)) {
            $this->builder->where($column);
        }
        if (is_callable($column)) {
            $this->where($column($this->builder));
        }
        if ($value == "`UNDEFINED`") {
            $this->builder->where([$column, '=', $operator]);
        } else {
            $this->builder->where([$column, $operator, $value]);
        }

        return $this;
    }

    /**
     * Set where in clause
     * @param string $column
     * @param array $values
     * @return object
     */
    public function latest(): object
    {
        $this->builder->latest();
        return $this;
    }

    /**
     * Get first model
     * @return object|null
     */
    public function first(): ?object
    {
        $data = $this->builder->first();
        if ($data && isset($data['id'])) {
            return new static($data);
        }
        return null;
    }

    /**
     * Get model by id
     * @param int $id
     * @return object|null
     */
    public function find(int $id): ?object
    {
        return $this->where('id', $id)->first();
    }

    /**
     * Get all models
     * @return array
     */
    public function get(): array
    {
        $data = $this->builder->get();
        $models = [];
        foreach ($data as $item) {
            $models[] = new static($item);
        }
        return $models;
    }

    /**
     * Delete model
     * @return bool
     */
    public function delete(): bool
    {
        return $this->builder->delete();
    }

    /**
     * Get model data as array
     * @return array
     */
    public function toArray(): array
    {
        $data = [];
        foreach ($this->data as $key => $value) {
            if (!in_array($key, $this->hidden)) {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    /**
     * Get table query builder
     * @return DB
     */
    public function builder(): DB
    {
        return DB::table($this->table);
    }
}
