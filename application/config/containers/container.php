<?php

declare(strict_types=1);

use Doctrine\DBAL\Connection as Dbal;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouteCollection;
use System\Abstracts\AbstractController;
use System\Application;
use System\Core\Database\Connection;
use System\Core\Helpers\Config;
use System\Core\Router\Interfaces\RouterInterface;
use System\Core\Router\CheckRoute;
use System\Core\Router\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$pathRoutes = BASE_DIR.Config::get('PATH_CACHE').'/routes.php';
$pathViews = BASE_DIR.Config::get('PATH_VIEWS');
$pathCacheViews = Config::get('PATH_CACHE_VIEWS') ? ['cache' => BASE_DIR.Config::get('PATH_CACHE_VIEWS')] : ['cache' => false];

$container = new Container();
$container->defaultToShared(true);
$container->delegate(new ReflectionContainer(true));

$container->add('response', Response::class);

$container->add('app', Application::class)->addArgument(RouterInterface::class)->addArgument(Request::class)->addArgument('response')->addArgument($container);
$container->add(CheckRoute::class)->addArgument($pathRoutes);
$container->add(RouterInterface::class, Router::class)->addArgument(CheckRoute::class)->addArgument('response')->addArgument(RouteCollection::class);

$container->addShared('twig-loader', FilesystemLoader::class)->addArgument(new StringArgument($pathViews));
$container->addShared('twig', Environment::class)->addArguments(['twig-loader', $pathCacheViews]);
$container->inflector(AbstractController::class)->invokeMethod('setContainer', [$container]);

$container->add('connection', Connection::class);
$container->addShared('db', concrete: function () use ($container): Dbal {
    return $container->get('connection')->create();
});

return $container;
