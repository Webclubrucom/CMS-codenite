<?php

declare(strict_types=1);

namespace System\Core\Router;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use System\Core\Exceptions\RouteNotFoundException;
use System\Core\Helpers\Config;
use System\Core\Helpers\SearchClass;
use System\Core\Router\Interfaces\RouterInterface;

class Router implements RouterInterface
{
    private RouteCollection $routeCollection;

    private Response $response;

    public function __construct(CheckRoute $routes, Response $response, RouteCollection $routeCollection)
    {
        $routes = require_once $routes->routes;
        $this->response = $response;
        $this->routeCollection = $routeCollection;
        $this->initRoutes($routes);
    }

    /**
     * @throws RouteNotFoundException
     * @throws Exception
     */
    public function match(string $route): array
    {

        if (! $this->routeCollection->get($route)) {
            $handler = SearchClass::get('Error');
            if (Config::get('APP_ENV') == 'local') {
                $this->response->setStatusCode(Response::HTTP_NOT_FOUND)->send();
                throw new RouteNotFoundException('Route not found!');
            } elseif ($handler) {
                $errorRoute = new Route($route, ['handler' => $handler, 'action' => 'index'], methods: 'GET');
                $this->routeCollection->add($route, $errorRoute);
                $this->response->setStatusCode(Response::HTTP_NOT_FOUND)->send();
            } else {
                $this->response->setContent('Error 404. Page not found!')->setStatusCode(Response::HTTP_NOT_FOUND)->send();
                exit();
            }
        }

        $context = new RequestContext();
        $matcher = new UrlMatcher($this->routeCollection, $context);

        return $matcher->match($route);
    }

    private function initRoutes($routes): void
    {
        foreach ($routes as $itemRoute) {
            $routeData = explode('@', $itemRoute[2], 2);
            $path = $itemRoute[1];
            $handler = SearchClass::get($routeData[0]);
            $action = $routeData[1];
            $method = $itemRoute[0];

            $route = new Route($path, ['handler' => $handler, 'action' => $action], methods: $method);
            $this->routeCollection->add($path, $route);
        }
    }
}
