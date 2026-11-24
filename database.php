<?php

class Database {

    private static $host = "localhost";
    private static $user = "root";
    private static $password = "";
    private static $database = "db_toko_pakaian";

    private static $connection;

    public static function connect() {

        if (!self::$connection) {

            self::$connection = new mysqli(
                self::$host,
                self::$user,
                self::$password,
                self::$database
            );

            if (self::$connection->connect_error) {
                die("Koneksi gagal: " . self::$connection->connect_error);
            }
        }

        return self::$connection;
    }
}