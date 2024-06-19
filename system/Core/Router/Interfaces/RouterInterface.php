<?php

declare(strict_types=1);

namespace System\Core\Router\Interfaces;

use League\Container\Container;
use System\Core\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request, Container $container);

    public function registerRoutes(array $routes): void;
}
