<?php

declare(strict_types=1);

namespace System\Core\Database;

use Doctrine\DBAL\Connection as Dbal;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use System\Core\Helpers\Config;

class Connection
{
    private array $connectionParams = [];

    public function __construct()
    {
        $this->connectionParams = [
            'dbname' => Config::get('DB_DATABASE'),
            'user' => Config::get('DB_USERNAME'),
            'password' => Config::get('DB_PASSWORD'),
            'host' => Config::get('DB_HOST'),
            'driver' => Config::get('DB_CONNECTION'),
            'port' => Config::get('DB_PORT'),
            'charset ' => Config::get('DB_CHARSET'),
        ];

    }

    /**
     * @throws Exception
     */
    public function create(): Dbal
    {
        return DriverManager::getConnection($this->connectionParams);
    }
}
