<?php

declare(strict_types=1);

namespace Infrastructure\Adapters\Persistence\MySql\Config;

use PDO;

final class Conection
{
    private string $host;
    private int $port;
    private string $database;
    private string $username;
    private string $password;
    private string $charset;

    public function __construct(
        string $host,
        int $port,
        string $database,
        string $username,
        string $password,
        string $charset = 'utf8mb4'   // corregido: era 'utf8mb64'
    ) {
        $this->host     = $host;
        $this->port     = $port;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->charset  = $charset;
    }

    public function createPdo(): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $this->host,
            $this->port,
            $this->database,
            $this->charset
        );

        return new PDO(
            $dsn,
            $this->username,
            $this->password,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    }
}
