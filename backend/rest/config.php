<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

class Config
{
    public static function DB_NAME()
    {
        
        return Config::get_env("DB_NAME", "pawpal_system");
    }

    public static function DB_PORT()
    {
        
        return Config::get_env("DB_PORT", 25060);
    }

    public static function DB_USER()
    {
        
        return Config::get_env("DB_USER", "doadmin");
    }

    public static function DB_PASSWORD()
    {
        
        return Config::get_env("DB_PASSWORD", "");
    }

    public static function DB_HOST()
    {
        
        return Config::get_env(
            "DB_HOST",
            "db-mysql-nyc3-19093-do-user-31089678-0.l.db.ondigitalocean.com"
        );
    }

    public static function JWT_SECRET()
    {
        return Config::get_env(
            "JWT_SECRET",
            "pawpal_secret_key_2024_secure_random_string_change_in_production"
        );
    }

    public static function get_env($name, $default)
    {
        return isset($_ENV[$name]) && trim($_ENV[$name]) !== ""
            ? $_ENV[$name]
            : $default;
    }
}

class Database
{
    private static $connection = null;

    public static function connect()
    {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host=" . Config::DB_HOST() .
                       ";port=" . Config::DB_PORT() .
                       ";dbname=" . Config::DB_NAME() .
                       ";charset=utf8mb4";

                self::$connection = new PDO(
                    $dsn,
                    Config::DB_USER(),
                    Config::DB_PASSWORD(),
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());
                throw new Exception("Database connection failed.");
            }
        }

        return self::$connection;
    }
}
