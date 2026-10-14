<?php
declare(strict_types=1);

final class Database
{
    public static function connect(): PDO
    {
        $path = getenv('DB_PATH') ?: dirname(__DIR__) . '/database/biblioteka.db';
        $directory = dirname($path);

        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException('Nie można utworzyć katalogu bazy danych.');
        }

        $pdo = new PDO('sqlite:' . $path, options: [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec('PRAGMA foreign_keys = ON');
        $schema = file_get_contents(dirname(__DIR__) . '/database/schema.sql');

        if ($schema === false) {
            throw new RuntimeException('Nie można odczytać schematu bazy danych.');
        }

        $pdo->exec($schema);

        return $pdo;
    }
}