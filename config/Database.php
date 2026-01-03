<?php

class Database
{
    private static $host = "localhost";
    private static $user = "root";
    private static $pass = "";
    private static $dbname = "hms_db";

    private static $conn = null;

    // =========================
    // CREATE DATABASE CONNECTION
    // =========================
    public static function connect()
    {
        if (self::$conn === null) {
            self::$conn = new mysqli(
                self::$host,
                self::$user,
                self::$pass,
                self::$dbname
            );

            // Check connection
            if (self::$conn->connect_error) {
                die("Database Connection Failed: " . self::$conn->connect_error);
            }
        }

        return self::$conn;
    }
}
