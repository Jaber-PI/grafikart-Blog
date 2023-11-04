<?php

namespace App;

use Core\Database;
use PDO;

class QueryBuilder
{
    private $Qselect = '*';
    private $from;
    private $order;
    private $limit;
    private $offset;
    private $where;
    public $params = [];

    public function from($table, $alias = null): self
    {
        $this->from = "{$table}" . ($alias ? " {$alias}" : '');
        return $this;
    }
    public function orderBy($column, $direction = 'ASC'): self
    {
        if (!in_array(strtoupper($direction), ['ASC', 'DESC'])) {
            $direction = '';
        }
        if ($this->order) {
            $this->order .= ", {$column}" . ($direction ? " {$direction}" : '');
        } else {
            $this->order = "{$column}" . ($direction ? " {$direction}" : '');
        }
        return $this;
    }
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }
    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }
    public function page(int $page): self
    {
        $this->offset = ($page - 1) * $this->limit;
        return $this;
    }
    public function where(string $where): self
    {
        $this->where = $where;
        return $this;
    }
    public function setParam($key, $value): self
    {
        $this->params[$key] = $value;
        return $this;
    }
    public function select(...$select): self
    {
        if (is_array($select[0])) {
            $select = $select[0];
        }
        if ($this->Qselect !== '*') {
            $this->Qselect .= ', ' . implode(', ', $select);
        } else {
            $this->Qselect = implode(', ', $select);
        }
        return $this;
    }
    public function toSQL()
    {
        $q = "SELECT {$this->Qselect} FROM {$this->from}";
        if ($this->where) {
            $q .= " WHERE {$this->where}";
        }
        if ($this->order) {
            $q .= " ORDER BY {$this->order}";
        }
        if ($this->limit) {
            $q .= " LIMIT {$this->limit}";
        }
        if ($this->offset !== null) {
            $q .= " OFFSET {$this->offset}";
        }

        return $q;
    }
    public function fetch(PDO $pdo, $select): ?string
    {
        $this->Qselect = $select;
        $state = $pdo->prepare($this->toSQL());
        $state->execute($this->params);
        return ($state->fetch())[$select];
    }
    public function count(Database $pdo)
    {
        $state = $pdo->connection->prepare($this->toSQL());
        $state->execute($this->params);
        return count($state->fetchAll());
    }
}
