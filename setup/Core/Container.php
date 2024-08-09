<?php

namespace Setup\Core;

use Exception;

class Container 
{
    protected $bindings = [];

    public function bind($key, $value)
    {
        $this->bindings[$key] = $value;
    }

    public function resolve($key)
    {
        if(!array_key_exists($key, $this->bindings)) {
            throw new Exception("No {$key} is bound in the container.");
        }

        return call_user_func($this->bindings[$key]);
    }

    public function has($key)
    {
        return array_key_exists($key, $this->bindings) ? true : false;
    }
}