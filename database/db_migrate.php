<?php

use App\Infrastructure\Connection;

echo "--- :: Migrating :: ---" . PHP_EOL . PHP_EOL;

require __DIR__ . '/../vendor/autoload.php';

$tables = include database_path('db_tables.php');

$connection = Connection::connect();
$db_name = env("DB_DATABASE", "main_db");

foreach ($tables as $table => $columns) {
    $tableExists = $connection->getOne("SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_schema = '{$db_name}' AND table_name = '{$table}'")['count'];
    if ($tableExists > 0) {
        echo "Skipped:: {$table} Table Exists"  . PHP_EOL;
        continue;
    }
    $columns = implode(', ', $columns);
    $sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` ({$columns})" . " ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    $connection->execute($sql);
    echo "Created:: {$table} Table"  . PHP_EOL;
}

echo PHP_EOL . "--- :: Migrated :: ---" . PHP_EOL;
