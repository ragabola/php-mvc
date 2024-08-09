<?php

namespace Setup\Database;

use Exception;

class QueryBuilder
{
    protected string $table = '';
    protected string $sql = '';
    protected array $bindings = [];

    public function __construct(protected Connection $db){}

    public function table(string $table)
    {
        $this->table = $table;
    }

    public function insert(array $keys, array $values)
    {
        $columns = implode(',', $keys);
        $injects = implode(',', array_fill(0, count($keys), '?'));

        $this->sql = "INSERT INTO {$this->table} ($columns) VALUES ($injects) ";
        $this->bindings = $values;

        $this->db->query($this->sql, $this->bindings);
    }

    public function update(array $keys, array $values)
    {
        $injects = implode('= ?, ', $keys) . '= ?';

        $this->sql = "UPDATE {$this->table} SET {$injects} ";
        $this->bindings = $values;

        return $this;
    }

    public function delete()
    {
        $this->sql = "DELETE FROM {$this->table} ";

        return $this;
    }

    public function select()
    {
        $columns = implode(',', func_get_args()) ?: '*';
        // $columns = $columns ?: '*';
        $this->sql = "SELECT {$columns} FROM {$this->table} ";

        return $this;
    }

    public function where(string $column, string $operator, $value)
    {
        $this->sql .= "WHERE {$column} {$operator} ? ";
        $this->bindings[] = $value;

        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC')
    {
        $this->sql .= "ORDER BY {$column} {$direction} ";

        return $this;
    }

    public function limit(int $limit)
    {
        $this->sql .= "LIMIT {$limit} ";

        return $this;
    }

    public function groupBy(string $column)
    {
        $this->sql .= "GROUP BY {$column} ";

        return $this;
    }

    public function get()
    {
        return $this->db->query($this->sql, $this->bindings)->fetchAll();
    }

    public function find()
    {
        return $this->db->query($this->sql, $this->bindings)->fetch();
    }

    public function dd()
    {
        dd([
            'sql' => $this->sql,
            'bindings' => $this->bindings
        ]);
    }
}