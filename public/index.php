<?php

declare(strict_types=1);

use System\Core\Http\Kernel;
use System\Core\Http\Request;
use System\Core\Router\Router;

define('BASE_DIR', dirname(__DIR__));

require_once BASE_DIR.'/system/rules.php';
require_once BASE_DIR.'/vendor/autoload.php';

$router = new Router();
$kernel = new Kernel($router);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);

$response->send();
