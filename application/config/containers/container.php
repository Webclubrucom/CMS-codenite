<?php

declare(strict_types=1);

use Doctrine\DBAL\Connection as Dbal;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouteCollection;
use System\Application;
use System\Core\Abstracts\AbstractController;
use System\Core\Database\Connection;
use System\Core\Helpers\Config;
use System\Core\Http\Middleware\RouterDispatcher;
use System\Core\Http\RequestHandler;
use System\Core\Http\RequestHandlerInterface;
use System\Core\Router\CheckRoute;
use System\Core\Router\Interfaces\RouterInterface;
use System\Core\Router\Router;
use System\Core\Session\Session;
use System\Core\Session\SessionInterface;
use System\Core\Template\TwigFactory;

$pathRoutes = BASE_DIR.Config::get('PATH_CACHE').'/routes.php';
$pathViews = BASE_DIR.Config::get('PATH_VIEWS');
$pathCacheViews = Config::get('PATH_CACHE_VIEWS');

$container = new Container();
$container->delegate(new ReflectionContainer(true));

$container->addShared(SessionInterface::class, Session::class);
$container->add(Connection::class);
$container->add(CheckRoute::class)->addArgument($pathRoutes);

$container->add(RequestHandlerInterface::class, RequestHandler::class)
    ->addArgument($container);

$container->add(RouterInterface::class, Router::class)
    ->addArguments([
        CheckRoute::class,
        Response::class,
        RouteCollection::class
    ]);

$container->add(RouterDispatcher::class)
    ->addArguments([
        RouterInterface::class,
        $container
    ]);

$container->add('app', Application::class)
    ->addArguments([
        Request::class,
        RequestHandlerInterface::class,
        SessionInterface::class
    ]);

$container->add(TwigFactory::class)
    ->addArguments([
        new StringArgument($pathViews),
        new StringArgument($pathCacheViews),
        SessionInterface::class
    ]);

$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container])
    ->invokeMethod('setSession', [SessionInterface::class]);

$container->addShared('twig', function () use ($container) {
    return $container->get(TwigFactory::class)->create();
});

$container->addShared('db', concrete: function () use ($container): Dbal {
    return $container->get(Connection::class)->create();
});

return $container;
