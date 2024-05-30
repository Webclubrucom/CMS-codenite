<?php

declare(strict_types=1);

namespace System\Core\Router;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Override;
use System\Core\Exceptions\MethodNotAllowedException;
use System\Core\Exceptions\RouteNotFoundException;
use System\Core\Http\Request;
use System\Core\Router\Interfaces\RouterInterface;

use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    /**
     * @throws RouteNotFoundException
     * @throws MethodNotAllowedException
     */
    #[Override]
    public function dispatch(Request $request): array
    {
        [$handler, $vars] = $this->extractRouteInfo($request);

        if (is_string($handler)) {
            [$controller, $method] = explode('@', $handler, 2);
            $controller = $this->searchController($controller);
            $handler = [new $controller, $method];
        }

        return [$handler, $vars];
    }

    /**
     * @throws RouteNotFoundException
     * @throws MethodNotAllowedException
     */
    private function extractRouteInfo(Request $request): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            $routes = require_once BASE_DIR.'/system/routes.php';
            foreach ($routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $infoRoute = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPath()
        );
        switch ($infoRoute[0]) {
            case Dispatcher::FOUND:
                return [$infoRoute[1], $infoRoute[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethod = implode(',', $infoRoute[1]);
                $e = new MethodNotAllowedException("Поддерживаемые HTTP  методы: $allowedMethod");
                $e->setStatusCode(405);
                throw $e;
            default:
                $e = new RouteNotFoundException('Класс не найден');
                $e->setStatusCode(404);
                throw $e;
        }
    }

    private function searchController($controller): string
    {
        define('MODULE', str_replace('Controller', '', $controller));

        return sprintf('\\Application\\Modules\\%s\\Controllers\\%s', MODULE, $controller);
    }
}
