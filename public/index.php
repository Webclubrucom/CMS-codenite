<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

define('BASE_DIR', dirname(__DIR__));

require_once BASE_DIR.'/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(BASE_DIR . '/application/config/.env');


$container = require_once BASE_DIR . '/application/config/container.php';

$kernel = $container->get('Kernel');
$kernel->handle();

