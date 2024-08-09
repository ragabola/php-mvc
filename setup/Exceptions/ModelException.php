<?php

namespace Setup\Exceptions;

use Exception;

class ModelException extends Exception
{
    public static function notFound()
    {
        return new self("Unable to find the model");
    }
}