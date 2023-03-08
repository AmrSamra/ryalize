<?php

use App\Infrastructure\Connection;

echo "--- :: Dropping :: ---" . PHP_EOL . PHP_EOL;

require __DIR__ . '/../vendor/autoload.php';

$tables = include database_path('db_tables.php');

$connection = Connection::connect();
$db_name = env("DB_DATABASE", "main_db");

foreach (array_reverse($tables) as $table => $columns) {
    $tableExists = $connection->getOne("SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_schema = '{$db_name}' AND table_name = '{$table}'")['count'];
    if ($tableExists == 0) {
        echo "Skipped:: {$table} Table Does Not Exist"  . PHP_EOL;
        continue;
    }
    $sql = "DROP TABLE IF EXISTS `" . $table . "`";
    $connection->execute($sql);
    echo "Dropped:: {$table} Table " . $table . PHP_EOL;
}

echo PHP_EOL . "--- :: Dropped :: ---" . PHP_EOL;
