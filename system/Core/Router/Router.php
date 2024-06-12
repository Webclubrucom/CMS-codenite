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
            $routes = require_once BASE_DIR.'/application/routes.php';
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
                $e = new RouteNotFoundException('Страница не найдена');
                $e->setStatusCode(404);
                throw $e;
        }
    }

    private function searchController($controller): string
    {
        $classes = $this->globClasses(BASE_DIR.'/application');

        return array_search($controller, $classes);
    }

    private function globClasses(string $path): array
    {
        $out = [];
        foreach (glob($path.'/*') as $file) {

            if (is_dir($file)) {
                $out = array_merge($out, $this->globClasses($file));
            } else {
                if ($this->getExtension($file) === 'php') {
                    $nsClass = $this->convertingStringClass($file);
                    $out[$nsClass] = basename($nsClass);
                }
            }
        }

        return $out;
    }

    private function convertingStringClass($file): array|string
    {
        $nsClass = substr($file, 0, strrpos($file, '.'));
        $nsClass = str_replace(BASE_DIR, '', $nsClass);
        $str = $this->getStringBetween($nsClass);
        $nsClass = str_replace($str, ucfirst($str), $nsClass);

        return str_replace('/', '\\', $nsClass);
    }

    private function getExtension($filename): string
    {
        return substr(strrchr($filename, '.'), 1);
    }

    private function getStringBetween($string): string
    {
        $string = ' '.$string;
        $ini = strpos($string, '/');
        if ($ini == 0) {
            return '';
        }
        $ini += strlen('/');
        $len = strpos($string, '/', $ini) - $ini;

        return substr($string, $ini, $len);
    }
}
