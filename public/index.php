<?php

declare(strict_types=1);

use System\Core\Http\Kernel;
use System\Core\Http\Request;

define('BASE_DIR', dirname(__DIR__));

require_once BASE_DIR.'/vendor/autoload.php';

$kernel = new Kernel();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);

$response->send();
