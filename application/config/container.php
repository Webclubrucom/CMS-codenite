<?php

declare(strict_types=1);

use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use System\Abstracts\AbstractHandler;
use System\Application;
use System\Core\Helpers\Config;
use System\Core\Router\Interfaces\RouterInterface;
use System\Core\Router\Router;
use System\Core\View\View;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$routes = require_once BASE_DIR . '/system/cache/routes.php';
$pathViews = BASE_DIR.Config::get('PATH_VIEWS');
$cacheViews = Config::get('CACHE_VIEWS') ? ['cache' => BASE_DIR . Config::get('CACHE_VIEWS')] : ['cache' => false];

$container = new Container();
$container->delegate(new ReflectionContainer(true));
$container->add('app', Application::class)->addArgument(RouterInterface::class)->addArgument(Request::class)->addArgument(Response::class)->addArgument($container);
$container->add(RouterInterface::class, Router::class)->addArgument(new ArrayArgument($routes));

$container->addShared('twig-loader', FilesystemLoader::class)->addArgument(new StringArgument($pathViews));
$container->addShared('twig', Environment::class)->addArguments(['twig-loader', $cacheViews]);
$container->addShared('view', View::class)->addArgument($container);
$container->inflector(AbstractHandler::class)->invokeMethod('setContainer', [$container]);


return $container;