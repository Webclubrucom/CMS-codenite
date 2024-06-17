<?php

declare(strict_types=1);

define('BASE_DIR', dirname(__DIR__));
const MODULES_DIR = BASE_DIR.'/application/Modules';

require_once BASE_DIR.'/vendor/autoload.php';

$container = require_once BASE_DIR.'/application/config/services.php';
$kernel = $container->get('Kernel');

$kernel->handle()->send();

