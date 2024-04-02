<?php

date_default_timezone_set('Africa/Nairobi');

/**
 * class for database connection using PDO.
 */
class Database
{
    // database host
    public static $conn;

    /**
     * @return PDO
     */
    public static function getInstance()
    {
        if (!isset($conn)) {
            try {
                $dbConfig = config('database.connections.mysql');

                self::$conn = new PDO("mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};charset=utf8", $dbConfig['username'], $dbConfig['password']);
                self::$conn->exec('set names utf8');
            } catch (PDOException $e) {
                echo 'Connection error: '.$e->getMessage()." in file '".$e->getFile()."' on line ".$e->getLine();
            }
        }

        return self::$conn;
    }

    /**
     * @param $str string
     *
     * @return string
     */
    public static function clean($str)
    {
        $str = trim($str);
        $str = htmlspecialchars($str);
        $str = strip_tags($str);

        return $str;
    }
}
