<?php

declare(strict_types=1);

namespace System\Core\Router\Facades;

class Route
{
    public static function get(string|array $uri, string|callable $handler): array
    {
        return ['GET', $uri, $handler];
    }

    public static function post(string|array $uri, string|callable $handler): array
    {
        return ['POST', $uri, $handler];
    }

    public static function put(string|array $uri, string|callable $handler): array
    {
        return ['PUT', $uri, $handler];
    }

    public static function delete(string|array $uri, string|callable $handler): array
    {
        return ['DELETE', $uri, $handler];
    }

    public static function search(string|array $uri, string|callable $handler): array
    {
        return ['SEARCH', $uri, $handler];
    }

    public static function head(string|array $uri, string|callable $handler): array
    {
        return ['HEAD', $uri, $handler];
    }

    public static function patch(string|array $uri, string|callable $handler): array
    {
        return ['PATCH', $uri, $handler];
    }

    public static function options(string|array $uri, string|callable $handler): array
    {
        return ['OPTIONS', $uri, $handler];
    }

    public static function connect(string|array $uri, string|callable $handler): array
    {
        return ['CONNECT', $uri, $handler];
    }

}
