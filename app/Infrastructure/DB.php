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


    protected function __construct(string $table)
    {
        $this->table = $table;
        $this->connection = Connection::connect();
    }

    public static function table(string $table): DB
    {
        return new static($table);
    }

    public function select(string ...$columns): DB
    {
        if (count($columns) > 0) {
            $this->columns = $columns;
        }
        return $this;
    }

    public function limit(int $limit): DB
    {
        if ($limit > 0) {
            $this->limit = $limit;
        }
        return $this;
    }

    public function offset(int $offset): DB
    {
        if ($offset > 0) {
            $this->offset = $offset;
        }
        return $this;
    }

    public function where(array ...$wheres): DB
    {
        $wheres = $this->andMe($wheres);

        if (count($this->wheres) > 0) {
            $wheres = "AND {$wheres}";
        }

        $this->wheres[] = $wheres;
        return $this;
    }

    public function orWhere(array ...$wheres): DB
    {
        $wheres = $this->andMe($wheres);

        if (count($this->wheres) > 0) {
            $wheres = "OR {$wheres}";
        }

        $this->wheres[] = $wheres;
        return $this;
    }

    public function whereExists(string $statement, string $prefix = 'AND'): DB
    {
        $this->wheres[] = "{$prefix} EXISTS ({$statement})";
        return $this;
    }

    public function whereNotExists(string $statement, string $prefix = 'AND'): DB
    {
        $this->wheres[] = "{$prefix} NOT EXISTS ({$statement})";
        return $this;
    }

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

                $statement = "{$column} {$operator} {$value}";
            }
            $array[] = $statement;
        }

        $statement = implode(" {$prefix} ", $array);

        return "( {$statement} )";
    }

    public function orderBy(array $columns, string $direction = 'ASC'): DB
    {
        if (count($columns)) {
            $columns = implode(', ', $columns);
            $this->orderBy = "{$columns} {$direction}";
        }
        return $this;
    }

    public function latest(): DB
    {
        return $this->orderBy(['created_at'], 'DESC');
    }

    protected function buildStatement()
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

    public function toSql(): string
    {
        return $this->buildStatement();
    }

    public function get(): array
    {
        $statement = $this->buildStatement();

        return $this->connection->getAll($statement);
    }

    public function first(): array
    {
        $this->limit = 1;
        $this->offset = 0;

        $statement = $this->buildStatement();

        return $this->connection->getOne($statement);
    }

    public function count(): int
    {
        $this->columns = ['COUNT(*) AS count'];
        $statement = $this->buildStatement();

        return $this->connection->getOne($statement)['count'];
    }
}
