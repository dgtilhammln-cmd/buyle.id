<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$db = DB::connection('sqlite');
$tables = $db->select('SELECT name, sql FROM sqlite_master WHERE type="table" AND name NOT LIKE "sqlite_%"');

$sql = "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS = 0;\n\n";

foreach ($tables as $t) {
    $tableName = $t->name;
    $createSql = $t->sql;
    
    // Naive SQLite to MySQL conversion
    $createSql = str_replace('"', '`', $createSql);
    $createSql = preg_replace('/autoincrement/i', 'AUTO_INCREMENT', $createSql);
    $createSql = preg_replace('/integer primary key/i', 'INT PRIMARY KEY', $createSql);
    // Remove unsupported sqlite things if any
    
    $sql .= "DROP TABLE IF EXISTS `$tableName`;\n";
    $sql .= $createSql . ";\n\n";
    
    $rows = $db->table($tableName)->get();
    if (count($rows) > 0) {
        // chunk inserts
        $chunks = array_chunk($rows->toArray(), 50);
        foreach ($chunks as $chunk) {
            $sql .= "INSERT INTO `$tableName` (";
            $cols = array_keys((array)$chunk[0]);
            $escapedCols = array_map(function($c) { return "`$c`"; }, $cols);
            $sql .= implode(', ', $escapedCols) . ") VALUES \n";
            
            $valuesArr = [];
            foreach ($chunk as $row) {
                $row = (array) $row;
                $vals = array_values($row);
                $escapedVals = array_map(function($v) {
                    if ($v === null) return 'NULL';
                    return "'" . addslashes($v) . "'";
                }, $vals);
                $valuesArr[] = "(" . implode(', ', $escapedVals) . ")";
            }
            $sql .= implode(",\n", $valuesArr) . ";\n";
        }
        $sql .= "\n";
    }
}
$sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";
file_put_contents('database.sql', $sql);
echo "Database dumped to database.sql\n";
