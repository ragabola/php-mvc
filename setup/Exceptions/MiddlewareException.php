<?php

namespace Setup\Exceptions;

use Exception;

class MiddlewareException extends Exception
{
    public static function notFound(string $alias)
    {
        return new self("The middleware alias '{$alias}' isn't registered in the kernel");
    }
}