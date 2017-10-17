<?php

namespace GeorgeHanson\LaravelPersisters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Arrayable;
use GeorgeHanson\LaravelPersisters\Contracts\Persister;
use GeorgeHanson\LaravelPersisters\Exceptions\PersisterException;

abstract class BasePersister implements Persister
{
    /**
     * The keys to automatically filter
     *
     * @var array
     */
    public $keys = [];

    /**
     * Persist the Model
     *
     * @param Arrayable|array $data The data to persist
     * @param Model|null $model The model to update
     *
     * @return mixed
     */
    public function persist($data, Model $model = null)
    {
        try {
            if ($data instanceof Arrayable) {
                return $this->persistArrayable($data, $model);
            }

            return $this->persistArray((array) $data, $model);
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * Persist the array data
     *
     * @param array $data
     * @param Model|null $model
     * @return Model
     */
    protected function persistArray(array $data, Model $model = null)
    {
        if ($model) {
            return $this->update($this->filterData($data), $model);
        }

        return $this->create($this->filterData($data));
    }

    /**
     * Persist the Arrayable
     *
     * @param Arrayable $data
     * @param Model|null $model
     * @return Model
     */
    protected function persistArrayable(Arrayable $data, Model $model = null)
    {
        if ($model) {
            return $this->update($this->filterData($data->toArray()), $model);
        }

        return $this->create($this->filterData($data->toArray()));
    }

    /**
     * Filter the data
     *
     * @param array $data
     * @return array
     */
    protected function filterData(array $data)
    {
        if (! empty($this->keys)) {
            $data = array_filter($data, function ($key) {
                return in_array($key, $this->keys);
            }, ARRAY_FILTER_USE_KEY);
        }

        array_walk($data, [$this, 'transform']);

        return $data;
    }

    /**
     * Create a new Model
     *
     * @param array $data
     * @return Model
     */
    abstract protected function create(array $data);

    /**
     * Transform the data which has been passed
     *
     * @param string $value The value of the data
     * @param string $key The key from the array
     * @return mixed
     */
    protected function transform(&$value, $key)
    {
        //
    }

    /**
     * Update the given Model
     *
     * @param array $data
     * @param Model $model
     * @return Model
     */
    abstract protected function update(array $data, Model $model);

    /**
     * Handle an exception being thrown
     *
     * @param \Exception $exception
     * @return void
     * @throws PersisterException
     */
    protected function handleException(\Exception $exception)
    {
        throw new PersisterException($exception->getMessage(), $exception->getCode());
    }
}
