<?php

declare(strict_types=1);

namespace System\Core\Router\Interfaces;

interface RouterInterface
{
    public function match(string $route, string|array $method): array;
}
