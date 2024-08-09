<?php

namespace Setup\Exceptions;

use Exception;

class RouteException extends Exception
{
    public static function notFound(string $route)
    {
        return new self("The provided route '{$route}' not found");
    }
}