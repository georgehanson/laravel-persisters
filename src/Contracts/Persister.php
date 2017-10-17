<?php

namespace GeorgeHanson\LaravelPersisters\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Arrayable;

interface Persister
{
    /**
     * Create the resource
     *
     * @param Arrayable|array $data The data to persist
     * @param Model|null $model The model to update
     * @return mixed
     */
    public function persist($data, Model $model = null);
}
