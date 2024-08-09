<?php

namespace App\Models;

use Exception;
use ReflectionClass;
use Setup\Database\Builder;

abstract class Model
{
    protected string $table;
    protected Builder $builder;

    public function __construct()
    {
        $this->builder = new Builder($this->rawTable());
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = new static;
        return $instance->$name(...$arguments);
    }

    public function __call($name, $arguments)
    {
        return $this->builder->$name(...$arguments);
    }

    public function rawTable() : string 
    {
        return $this->table ?? strtolower((new ReflectionClass(get_called_class()))->getShortName()) . 's';
    }
}