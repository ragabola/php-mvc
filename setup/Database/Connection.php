<?php

namespace Setup\Database;

use PDO;
use PDOException;
use Setup\Exceptions\DatabaseException;

class Connection
{
    public PDO $connection;
    public $statement;

    public function __construct($driver, $config)
    {
        $dsn = $driver . ":" . http_build_query($config, '', ';');

        try{
            $this->connection = new PDO($dsn);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        }catch(PDOException $e){
            throw DatabaseException::pdo($e->getMessage());
        }
    }

    public function query(string $query, array $params = [])
    {
        $this->statement = $this->connection->prepare($query);
        $this->statement->execute($params);

        return $this;
    }

    public function fetch()
    {
        return $this->statement->fetch();
    }

    public function fetchAll()
    {
        return $this->statement->fetchAll();
    }

}