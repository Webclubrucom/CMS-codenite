<?php

declare(strict_types=1);

namespace System\Core\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use System\Core\Http\RequestHandlerInterface;
use System\Core\Middleware\MiddlewareInterface;

class Authentication implements MiddlewareInterface
{
    private bool $authenticated = true;

    public function process(Request $request, RequestHandlerInterface $handler): ?Response
    {
        if (! $this->authenticated) {
            return (new Response('Ошибка авторизации', 401))->send();
        }

        return $handler->handle($request);
    }
}