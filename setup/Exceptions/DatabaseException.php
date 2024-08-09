<?php

namespace Setup\Exceptions;

use Exception;

class DatabaseException extends Exception
{
    public static function pdo(string $message)
    {
        return new self("PDO was unable to connect to the database: {$message}");
    }
}