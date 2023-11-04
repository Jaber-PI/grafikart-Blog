<?php

namespace App;

use App\Model\Post;
use App\Model\Category;
use Exception;
use PDO;

class Database
{
    /**
     * @var PDO
     */
    public $connection;
    /**
     * @var PDOStatement|false
     */
    public $statement;

    public function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=myBlog';
        $user = 'root';
        $pass = '0767@mysql';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        $this->connection = new PDO($dsn, $user, $pass, $options);
    }

    public function query($query, $params = []): self
    {
        $this->statement = $this->connection->prepare($query);
        $this->statement->execute($params);
        return $this;
    }
    public function insert(string $insertQuery, array $params)
    {
        $this->statement = $this->connection->prepare($insertQuery);
        $ok = $this->statement->execute($params);
        return $this->connection->lastInsertId();
    }
    public function count(string $table, ?string $where = null, $params = []): int
    {
        if ($where) {
            $where = 'WHERE ' . $where;
        }
        $this->query('SELECT COUNT(*) from ' . $table . ' ' . $where, $params);
        return $this->statement->fetchColumn();
    }
    public function delete(string $table, string $where, $params): void
    {
        if ($where) {
            $where = 'WHERE ' . $where;
        }
        $query = "DELETE FROM {$table} {$where}";
        $this->statement = $this->connection->prepare($query);
        $ok = $this->statement->execute($params);
        if ($ok === false) {
            throw new Exception('can\'t delete this item from table' . $table);
        }
    }

    public function get($fetchMode = null)
    {
        return $this->statement->fetchAll($fetchMode);
    }
    public function getObjects($calssname)
    {
        return $this->statement->fetchAll(PDO::FETCH_CLASS, $calssname);
    }
    public function getAll(string $table, string $calssname)
    {
        $this->query("SELECT * FROM {$table}");
        return $this->getObjects($calssname);
    }
    public function getObject($calssname)
    {
        $this->statement->setFetchMode(PDO::FETCH_CLASS, $calssname);
        return $this->statement->fetch();
    }
    public function find()
    {
        return $this->statement->fetch();
    }
}
