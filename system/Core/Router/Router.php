<?php

declare(strict_types=1);

namespace System\Core\Router;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use System\Core\Router\Interfaces\RouterInterface;

class Router implements RouterInterface
{
    private RouteCollection $routeCollection;

    public function __construct(array $routes)
    {
        $this->routeCollection = new RouteCollection();
        $this->initRoutes($routes);
    }

    public function match(string $route): array
    {
        $context = new RequestContext();
        $matcher = new UrlMatcher($this->routeCollection, $context);

        return $matcher->match($route);
    }

    private function initRoutes($routes): void
    {
        foreach ($routes as $itemRoute) {

            $routeData = explode('@', $itemRoute[2], 2);
            $path = $itemRoute[1];
            $handler = $this->searchHandler($routeData[0]);
            $action = $routeData[1];
            $method = $itemRoute[0];

            $route = new Route($path, ['handler' => $handler, 'action' => $action], methods: $method);
            $this->routeCollection->add($path, $route);
        }
    }

    private function searchHandler($handler): false|int|string
    {
        $classes = $this->globClasses(BASE_DIR.'/application');

        return array_search($handler, $classes);
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
