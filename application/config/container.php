<?php

declare(strict_types=1);

use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use System\Abstracts\AbstractController;
use System\Core\Helpers\Config;
use System\Core\Http\Kernel;
use System\Core\Router\Interfaces\RouterInterface;
use System\Core\Router\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$routes = require_once BASE_DIR.'/application/routes.php';
$pathViews = BASE_DIR.Config::get('PATH_VIEWS');

$container = new Container();
$container->delegate(new ReflectionContainer(true));
$container->add(RouterInterface::class, Router::class);
$container->extend(RouterInterface::class)->addMethodCall('registerRoutes', [new ArrayArgument($routes)]);
$container->add('kernel', Kernel::class)->addArgument(RouterInterface::class)->addArgument($container);
$container->addShared('twig-loader', FilesystemLoader::class)->addArgument(new StringArgument($pathViews));
$container->addShared('twig', Environment::class)->addArgument('twig-loader');
$container->inflector(AbstractController::class)->invokeMethod('setContainer', [$container]);

return $container;
