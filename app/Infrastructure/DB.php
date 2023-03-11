<?php

namespace App\Infrastructure;

class DB
{
    private ConnectionLayer $connection;

    public string $statement;

    public string $table;

    public array $columns = ['*'];

    public array $wheres = [];

    public string $orderBy = '';

    public int $limit = 0;

    public int $offset = 0;


    /**
     * DB constructor.
     * @param string $table
     */
    protected function __construct(string $table)
    {
        $this->table = $table;
        $this->connection = Connection::connect();
    }

    public static function query(string $statement): array
    {
        $db = new static('');
        return $db->connection->getAll($statement);
    }

    /**
     * Set table name
     * @param string $table
     * @return DB
     */
    public static function table(string $table): DB
    {
        return new static($table);
    }

    /**
     * set columns
     * @param string ...$columns
     * @return DB
     */
    public function select(string ...$columns): DB
    {
        if (count($columns) > 0) {
            $this->columns = $columns;
        }
        return $this;
    }

    /**
     * set limit
     * @param int $limit
     * @return DB
     */
    public function limit(int $limit): DB
    {
        if ($limit > 0) {
            $this->limit = $limit;
        }
        return $this;
    }

    /**
     * set offset
     * @param int $offset
     * @return DB
     */
    public function offset(int $offset): DB
    {
        if ($offset > 0) {
            $this->offset = $offset;
        }
        return $this;
    }

    public function whereSql(string $sql, array $binding = []): DB
    {
        $where = $sql;
        array_walk($binding, function ($value) use (&$where) {
            $where = str_replace("?", $value, $where);
        });

        if (count($this->wheres) > 0) {
            $where = "AND {$where}";
        }
        $this->wheres[] = $where;
        return $this;
    }

    /**
     * Filter with wheres
     * @param array ...$wheres
     * @return DB
     */
    public function where(array ...$wheres): DB
    {
        if (count($wheres)) {
            $wheres = $this->andMe($wheres);

            if (count($this->wheres) > 0) {
                $wheres = "AND {$wheres}";
            }

            $this->wheres[] = $wheres;
        }
        return $this;
    }

    /**
     * Filter with or wheres
     * @param array ...$wheres
     * @return DB
     */
    public function orWhere(array ...$wheres): DB
    {
        if (count($wheres)) {
            $wheres = $this->andMe($wheres);

            if (count($this->wheres) > 0) {
                $wheres = "OR {$wheres}";
            }

            $this->wheres[] = $wheres;
        }
        return $this;
    }

    /**
     * Filter with Where Exists
     * @param string $statement
     * @param string $prefix
     * @return DB
     */
    public function whereExists(string $statement, string $prefix = 'AND'): DB
    {
        $this->wheres[] = "{$prefix} EXISTS ({$statement})";
        return $this;
    }

    /**
     * Filter with Where Not Exists
     * @param string $statement
     * @param string $prefix
     * @return DB
     */
    public function whereNotExists(string $statement, string $prefix = 'AND'): DB
    {
        $this->wheres[] = "{$prefix} NOT EXISTS ({$statement})";
        return $this;
    }

    /**
     * Format Where Statements into SQL
     * @param array $wheres
     * @param string $prefix
     * @return string
     */
    protected function andMe(array $wheres, $prefix = 'AND'): string
    {
        $array = [];

        foreach ($wheres as $key => $where) {
            if ($key == 'OR') {
                $prefix = 'OR';
            }
            [$column, $operator, $value] = $where;

            if (is_array($column)) {
                $statement = $this->andMe($where, $prefix);
            } else {
                if (is_array($value)) {
                    $values = array_map(function ($value) {
                        return is_string($value) ? "'{$value}'" : $value;
                    }, $value);
                    $value = '(' . implode(', ', $values) . ')';
                }
                if (!is_null($value) && is_string($value)) {
                    $value = "'{$value}'";
                }
                if (is_null($value)) {
                    $operator = $operator == '=' ? 'IS' : 'IS NOT';
                    $value = 'NULL';
                }
                $statement = "{$column} {$operator} {$value}";
            }
            $array[] = $statement;
        }

        $statement = implode(" {$prefix} ", $array);

        return "( {$statement} )";
    }

    /**
     * Order By
     * @param array $columns
     * @param string $direction
     * @return DB
     */
    public function orderBy(array $columns, string $direction = 'ASC'): DB
    {
        if (count($columns)) {
            $columns = implode(', ', $columns);
            $this->orderBy = "{$columns} {$direction}";
        }
        return $this;
    }

    /**
     * Order By Latest
     * @return DB
     */
    public function latest(): DB
    {
        return $this->orderBy(['created_at'], 'DESC');
    }

    /**
     * Build Statement
     * @return string
     */
    protected function buildStatement(): string
    {
        $columns = implode(', ', $this->columns);
        $statement = "SELECT {$columns} FROM {$this->table}";

        if (count($this->wheres) > 0) {
            $wheres = implode(' ', $this->wheres);
            $statement .= " WHERE {$wheres}";
        }

        if ($this->orderBy) {
            $statement .= " ORDER BY {$this->orderBy}";
        }

        if ($this->limit) {
            $statement .= " LIMIT {$this->limit}";
        }

        if ($this->offset) {
            $statement .= " OFFSET {$this->offset}";
        }

        return $statement;
    }

    /**
     * Get SQL Statement
     * @return string
     */
    public function toSql(): string
    {
        return $this->buildStatement();
    }

    /**
     * Get All Rows
     * @return array
     */
    public function get(): array
    {
        $statement = $this->buildStatement();

        return $this->connection->getAll($statement);
    }

    /**
     * Get First Row
     * @return array|null
     */
    public function first(): ?array
    {
        $this->limit = 1;
        $this->offset = 0;

        $statement = $this->buildStatement();

        $result = $this->connection->getOne($statement);

        return $result ?: null;
    }

    /**
     * Get count rows
     * @return int
     */
    public function count(): int
    {
        $this->columns = ['COUNT(*) AS count'];
        $statement = $this->buildStatement();

        return (int) ($this->connection->getOne($statement)['count'] ?? 0);
    }

    /**
     * Check if row exists
     * @return bool
     */
    public function exists(): bool
    {
        $statement = "SELECT EXISTS ({$this->buildStatement()}) AS isExist";

        $this->connection->getOne($statement);
        return $this->connection->getOne($statement)['isExist'] ?? false;
    }

    /**
     * Insert rows
     * @param array $rows
     * @return int
     */
    public function insert(array $rows): int
    {
        $columns = array_keys($rows[0]);
        $columns = implode(', ', $columns);

        $values = [];
        foreach ($rows as $row) {
            $values[] = '(' . implode(', ', array_map(function ($value) {
                return is_string($value) ? "'{$value}'" : $value;
            }, $row)) . ')';
        }

        $values = implode(', ', $values);
        $statement = "INSERT INTO `{$this->table}` ({$columns}) VALUES {$values}";

        return $this->connection->insert($statement);
    }

    /**
     * Update rows
     * @param array $data
     * @return int
     */
    public function update(array $data): int
    {
        $data = array_map(function ($value) {
            return is_string($value) ? "'{$value}'" : $value;
        }, $data);

        $data = implode(', ', array_map(function ($key, $value) {
            return "{$key} = {$value}";
        }, array_keys($data), $data));

        $statement = "UPDATE `{$this->table}` SET {$data}";

        if (count($this->wheres) > 0) {
            $wheres = implode(' ', $this->wheres);
            $statement .= " WHERE {$wheres}";
        }

        return $this->connection->update($statement);
    }

    /**
     * Delete rows
     * @return int
     */
    public function delete(): int
    {
        $statement = "DELETE FROM `{$this->table}`";

        if (count($this->wheres) > 0) {
            $wheres = implode(' ', $this->wheres);
            $statement .= " WHERE {$wheres}";
        }

        return $this->connection->delete($statement);
    }
}
