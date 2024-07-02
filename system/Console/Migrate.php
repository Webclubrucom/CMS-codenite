<?php

declare(strict_types=1);

namespace System\Console;

use Doctrine\DBAL\Exception;
use System\Core\Database\Connection;

class Migrate
{
    private const string MIGRATIONS_TABLE = 'migrations';
    private Connection $connection;

    public function __construct()
    {
        $this->connection = new Connection();
    }

    /**
     * @throws Exception
     */
    public function dropTable($table): void
    {
        $conn  = $this->connection->create();
        $sql = 'DROP TABLE IF EXISTS '.$table;
        $conn->executeQuery($sql);
    }

}