<?php

declare(strict_types=1);

use System\Core\Helpers\Config;

const BASE_DIR = __DIR__;

require_once BASE_DIR.'/vendor/autoload.php';

if (Config::get('APP_ENV') == 'local') {
    ini_set('display_errors', 'On');
    error_reporting(E_ALL | E_NOTICE);
}

$container = require_once BASE_DIR.'/application/config/containers/container.php';
$app = $container->get('app');

$app->handle();
