<?php

use App\Infrastructure\Connection;

echo "--- :: Seeding :: ---" . PHP_EOL . PHP_EOL;

require __DIR__ . '/../vendor/autoload.php';

$tables = include database_path('db_seeders.php');

$connection = Connection::connect();


// Seed the tables with fake data
foreach ($tables as $table => $rows) {
    $count = $connection->getOne("SELECT COUNT(*) AS count FROM `{$table}`")['count'];
    if ($count > 0) {
        echo "Skipped:: {$table} Table"  . PHP_EOL;
        continue;
    }
    $columns = array_keys($rows[0]);
    $columns = implode(', ', $columns);
    $values = [];
    foreach ($rows as $row) {
        $values[] = '(' . implode(', ', array_map(function ($value) {
            return is_string($value) ? "'{$value}'" : $value;
        }, $row)) . ')';
    }
    $values = implode(', ', $values);
    $sql = "INSERT INTO `{$table}` ({$columns}) VALUES {$values}";
    $connection->execute($sql);
    echo "Seeded:: {$table} Table"  . PHP_EOL;
}

echo PHP_EOL . "--- :: Seeded :: ---" . PHP_EOL;
