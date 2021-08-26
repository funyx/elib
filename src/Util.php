<?php

namespace funyx\elib;

class Util
{
    public static function getDB(): \PDO {
        $user = $_ENV['POSTGRES_USER'] ?? 'username';
        $password = $_ENV['POSTGRES_PASSWORD'] ?? 'password';
        $database = $_ENV['POSTGRES_DB'] ?? 'default_database';
        $port = $_ENV['POSTGRES_PORT'] ?? '5432';
        $host = $_ENV['POSTGRES_HOST'] ?? '127.0.0.1';
        $conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
            $host,
            $port,
            $database,
            $user,
            $password);
        $pdo = new \PDO($conStr);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}
