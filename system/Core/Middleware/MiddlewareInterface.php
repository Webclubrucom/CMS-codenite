<?php

declare(strict_types=1);

namespace System\Core\Middleware;

use Symfony\Component\HttpFoundation\Request;
use System\Core\Http\RequestHandlerInterface;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler);
}