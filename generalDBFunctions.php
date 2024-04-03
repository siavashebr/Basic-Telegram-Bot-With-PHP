<?php
namespace bot\generalDBFunctions;

ini_set('display_errors',1);

use PDO;
use PDOException;

require_once __DIR__ . '/generalFunctions.php';

define("host", 'localhost');
define("dbName", 'dbName');
define("dbUser", 'dbUser');
define("dbPass", 'dbPass');
try {
    define("db", new PDO('mysql:host='.host.';dbname='.dbName, dbUser, dbPass));
    db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    db->query("SET NAMES 'utf8mb4'");
    db->query("SET CHARACTER SET 'utf8mb4'");
    db->query("SET SESSION collation_connection = 'utf8mb4_unicode_ci'");
} catch (PDOException $e) {
    print_r($e);
}
class generalDBFunctions{

    public function __construct()
    {

    }
    public function insertToDB($table, $columns, $values): void
    {
        //insertToDB("admins", ["telegram_id", "telegram_username", "access"], ["10", "11", "12"]
        $columnsStr = implode(',', $columns);
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $sql = "INSERT INTO $table ($columnsStr) VALUES ($placeholders)";
        $stmt = db->prepare($sql);
        try {
            $stmt->execute($values);
        } catch (PDOException $e) {
            $values = json_encode($values);
            //sendLog($e, $sql . "Values:$values");
        }
    }
    public function updateToDB($table, $columns, $values, $condition): void
    {
    //updateToDB("admins",["telegram_id","telegram_username","access"],["0","11","22"],"id=3")
        $setStr = '';
        foreach ($columns as $column) {
            $setStr .= "$column = ?, ";
        }
        $setStr = rtrim($setStr, ', ');
        $sql = "UPDATE $table SET $setStr WHERE $condition";
        $stmt = db->prepare($sql);
        try {
            $stmt->execute($values);
        } catch (PDOException $e) {
            $values = json_encode($values);
            //sendLog($e, $sql . "Values:$values");
        }
    }
    public function selectFromDB($table, $columns, $condition = NULL): false|array|string
    {
        // $selectedValues = selectFromDB("admins", ["*"],"id=4");
        // $selectedValues = selectFromDB("admins", ["telegram_id","telegram_username"],"id=4");
        $columns = implode(',', $columns);
        if (is_null($condition)) {
            $sql = "SELECT $columns FROM $table";
        }
        else {
            $sql = "SELECT $columns FROM $table WHERE $condition";
        }
        //echo $sql;
        $stmt = db->prepare($sql);
        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            //sendLog($e, $sql);
            return "";
        }
    }
    public function deleteFromDB($table, $condition)
    {
        //deleteFromDB("admins","id=4");

        $sql = "DELETE FROM $table WHERE $condition";
        $stmt = db->prepare($sql);
        try {
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            //sendLog($e, $sql);
            return "";
        }
    }
}








