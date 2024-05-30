<?php

declare(strict_types=1);

namespace System\Core\Router;

class Route
{
    public static function get(string $uri, string|callable $handler): array
    {
        return ['GET', $uri, $handler];
    }

    public static function post(string $uri, string|callable $handler): array
    {
        return ['POST', $uri, $handler];
    }
}
