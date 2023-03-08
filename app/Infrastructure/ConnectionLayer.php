<?php

namespace App\Infrastructure;

use PDO;
use PDOStatement;

class ConnectionLayer extends PDO
{
    /**
     * Get all records
     * @param string $statement
     * @return array
     */
    public function getAll(string $statement): array
    {
        $stmt = $this->execute($statement);
        return $stmt->fetchAll();
    }

    /**
     * Get first record
     * @param string $statement
     * @return array
     */
    public function getOne(string $statement): array
    {
        $stmt = $this->execute($statement);
        return $stmt->fetch();
    }

    /**
     * Insert record
     * @param string $statement
     * @return int
     */
    public function insert(string $statement): int
    {
        $this->execute($statement);
        return $this->lastInsertId();
    }

    /**
     * Update record
     * @param string $statement
     * @return int
     */
    public function update(string $statement): int
    {
        $stmt = $this->execute($statement);
        return $stmt->rowCount();
    }

    /**
     * Delete record
     * @param string $statement
     * @return int
     */
    public function delete(string $statement): int
    {
        $stmt = $this->execute($statement);
        return $stmt->rowCount();
    }

    public function execute(string $statement): PDOStatement|false
    {
        $stmt = $this->prepare($statement);
        $stmt->execute();
        return $stmt;
    }
}
