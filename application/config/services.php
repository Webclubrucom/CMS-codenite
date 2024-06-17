<?php

declare(strict_types=1);

use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Container;
use System\Core\Http\Kernel;
use System\Core\Router\Interfaces\RouterInterface;
use System\Core\Router\Router;

$routes = require_once BASE_DIR.'/application/routes.php';

$container = new Container();
$container->add(RouterInterface::class, Router::class);
$container->add('Kernel', Kernel::class)->addArgument(RouterInterface::class);
$container->extend(RouterInterface::class)->addMethodCall('registerRoutes', [new ArrayArgument($routes)]);

return $container;
