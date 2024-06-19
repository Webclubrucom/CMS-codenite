<?php

declare(strict_types=1);

define('BASE_DIR', dirname(__DIR__));

require_once BASE_DIR.'/vendor/autoload.php';

$container = require_once BASE_DIR.'/application/config/container.php';
$container->get('kernel')->handle();
