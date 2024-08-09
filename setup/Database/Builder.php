<?php

namespace Setup\Database;

use Exception;
use Setup\Exceptions\ModelException;

class Builder
{
    protected QueryBuilder $queryBuilder;

    public function __construct(protected string $table)
    {
        $this->queryBuilder = new QueryBuilder(new Connection(
            config('database.driver'),
            config('database.connections.' . config('database.driver'))
        ));

        $this->queryBuilder->table($table);
    }

    public function create(array $data)
    {
        $this->queryBuilder->insert(array_keys($data), array_values($data));
    }

    public function update(array $data)
    {
        return $this->queryBuilder->update(array_keys($data), array_values($data));
    }

    public function find($id)
    {
        return $this->queryBuilder->where('id', '=', $id)->find();
    }

    public function findOrFail($id)
    {
        return $this->find($id) ?? throw ModelException::notFound();
    }

    public function first()
    {
        return $this->queryBuilder->limit(1)->find();
    }

    public function firstOrFail()
    {
        return $this->first() ?? throw ModelException::notFound();
    }


    public function __call($name, $arguments)
    {
        return $this->queryBuilder->$name(...$arguments);
    }
}