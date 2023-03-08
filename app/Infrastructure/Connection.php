<?php

namespace App\Infrastructure;

class Connection
{
    private ConnectionLayer $pdo;

    protected function __construct(string $connection)
    {
        $config = [
            'host'          => config("database.connections.{$connection}.host"),
            'port'          => config("database.connections.{$connection}.port"),
            'dbname'        => config("database.connections.{$connection}.database"),
            'unix_socket'   => config("database.connections.{$connection}.unix_socket"),
            'charset'       => config("database.connections.{$connection}.charset"),
        ];

        $username = config("database.connections.{$connection}.username");
        $password = config("database.connections.{$connection}.password");

        $dsn = "mysql:";
        array_walk($config, function ($value, $key) use (&$dsn) {
            $dsn .= "{$key}={$value};";
        });

        $this->pdo = new ConnectionLayer($dsn, $username, $password, [
            ConnectionLayer::ATTR_ERRMODE               => ConnectionLayer::ERRMODE_EXCEPTION,
            ConnectionLayer::ATTR_DEFAULT_FETCH_MODE    => ConnectionLayer::FETCH_ASSOC,
        ]);
    }

    public static function connect(string $connection = null): ConnectionLayer
    {
        $connection = $connection ?? config('database.default');
        $db = new static($connection);
        return $db->pdo;
    }
}
